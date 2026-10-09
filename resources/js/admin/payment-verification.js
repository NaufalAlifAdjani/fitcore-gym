export default function paymentVerification({ approveUrlTemplate, rejectUrlTemplate }) {
    return {
        selectedPayment: null,
        showDetailModal: false,
        showRejectModal: false,
        showReceiptLightbox: false,
        isLoadingDetail: false,
        isSubmitting: false,
        rejectionReason: '',
        actionError: '',
        toast: {
            visible: false,
            message: '',
            type: 'success',
        },
        toastTimeout: null,

        async openDetails(url) {
            this.isLoadingDetail = true;
            this.actionError = '';

            try {
                const response = await fetch(url, {
                    headers: {
                        Accept: 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                });

                const payload = await response.json();

                if (!response.ok) {
                    throw new Error(payload.message || 'Rincian pembayaran gagal dimuat.');
                }

                this.selectedPayment = payload.data;
                this.showDetailModal = true;
            } catch (error) {
                this.showToast(error.message || 'Rincian pembayaran gagal dimuat.', 'error');
            } finally {
                this.isLoadingDetail = false;
            }
        },

        async approve() {
            if (!this.selectedPayment || this.isSubmitting || this.selectedPayment.status !== 'pending') {
                return;
            }

            await this.submitVerification(
                this.approveUrlTemplate.replace('__PAYMENT_ID__', encodeURIComponent(this.selectedPayment.id)),
                {},
                'Pembayaran berhasil diverifikasi.',
                'verified',
            );
        },

        async reject() {
            if (!this.selectedPayment || this.isSubmitting || this.selectedPayment.status !== 'pending') {
                return;
            }

            if (!this.rejectionReason.trim()) {
                this.actionError = 'Alasan penolakan wajib diisi.';
                return;
            }

            const rejected = await this.submitVerification(
                this.rejectUrlTemplate.replace('__PAYMENT_ID__', encodeURIComponent(this.selectedPayment.id)),
                { reason: this.rejectionReason.trim() },
                'Pembayaran berhasil ditolak.',
                'rejected',
            );

            if (rejected) {
                this.showRejectModal = false;
                this.rejectionReason = '';
            }
        },

        async submitVerification(url, data, successMessage, newStatus) {
            this.isSubmitting = true;
            this.actionError = '';

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify(data),
                });

                const payload = await response.json();

                if (!response.ok || !payload.success) {
                    const validationMessage = Object.values(payload.errors || {}).flat()[0];
                    throw new Error(validationMessage || payload.message || 'Status pembayaran gagal diperbarui.');
                }

                this.selectedPayment.status = newStatus;
                if (newStatus === 'rejected') {
                    this.selectedPayment.rejection_reason = data.reason;
                }
                window.dispatchEvent(new CustomEvent('payment-status-updated', {
                    detail: {
                        id: this.selectedPayment.id,
                        status: newStatus,
                        rejectionReason: data.reason || null,
                    },
                }));

                this.showDetailModal = false;
                this.showRejectModal = false;
                this.showReceiptLightbox = false;
                this.showToast(successMessage);

                return true;
            } catch (error) {
                this.actionError = error.message || 'Terjadi kesalahan saat memproses pembayaran.';
                return false;
            } finally {
                this.isSubmitting = false;
            }
        },

        useQuickReason(reason) {
            this.rejectionReason = reason;
            this.actionError = '';
        },

        showToast(message, type = 'success') {
            this.toast.message = message;
            this.toast.type = type;
            this.toast.visible = true;

            window.clearTimeout(this.toastTimeout);
            this.toastTimeout = window.setTimeout(() => {
                this.toast.visible = false;
            }, 3500);
        },

        closeModals() {
            if (this.isSubmitting) {
                return;
            }

            this.showReceiptLightbox = false;
            this.showRejectModal = false;
            this.showDetailModal = false;
            this.actionError = '';
        },

        formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0,
            }).format(amount);
        },

        formatDate(value) {
            if (!value) {
                return '-';
            }

            return new Intl.DateTimeFormat('id-ID', {
                dateStyle: 'medium',
                timeStyle: 'short',
            }).format(new Date(value));
        },

        printReceipt() {
            window.print();
        },
    };
}
