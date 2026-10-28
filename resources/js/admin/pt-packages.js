function emptyPtPackageForm() {
    return {
        id: null,
        name: '',
        pt_session_count: 4,
        price: '',
        min_membership_days: 0,
        validity_days: 30,
        is_active: true,
    };
}

export function ptPackages({
    hasErrors,
    oldForm,
    packageBaseUrl,
    storeUrl,
}) {
    return {
        showModal: false,
        isEditMode: false,
        form: emptyPtPackageForm(),

        init() {
            if (!hasErrors) {
                return;
            }

            this.form = {
                ...emptyPtPackageForm(),
                ...oldForm,
            };
            this.isEditMode = Boolean(this.form.id);
            this.showModal = true;
        },

        resetForm() {
            this.isEditMode = false;
            this.form = emptyPtPackageForm();
        },

        openCreateModal() {
            this.resetForm();
            this.showModal = true;
        },

        openEditModal(packageData) {
            this.isEditMode = true;
            this.form = {
                ...emptyPtPackageForm(),
                ...packageData,
                is_active: packageData.status === 'active' || packageData.is_active === true,
            };
            this.showModal = true;
        },

        perSessionPrice() {
            const price = Number(this.form.price);
            const sessions = Number(this.form.pt_session_count);
            if (price > 0 && sessions > 0) {
                return Math.round(price / sessions);
            }
            return 0;
        },

        formattedPerSessionPrice() {
            const val = this.perSessionPrice();
            return val > 0 ? 'Rp ' + val.toLocaleString('id-ID') : '-';
        },

        formAction() {
            if (!this.isEditMode) {
                return storeUrl;
            }

            return `${packageBaseUrl}/${encodeURIComponent(this.form.id)}`;
        },
    };
}

export function ptPackageStatus({ url, isActive }) {
    return {
        isActive,
        isSaving: false,
        error: '',

        async toggleStatus() {
            if (this.isSaving) {
                return;
            }

            const previousStatus = this.isActive;
            this.isActive = !previousStatus;
            this.isSaving = true;
            this.error = '';

            try {
                const response = await fetch(url, {
                    method: 'PATCH',
                    headers: {
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (!response.ok) {
                    throw new Error(`Status gagal diperbarui (HTTP ${response.status}).`);
                }

                const result = await response.json();
                this.isActive = result.is_active;
            } catch (error) {
                this.isActive = previousStatus;
                this.error = error.message || 'Terjadi kesalahan saat memperbarui status.';
            } finally {
                this.isSaving = false;
            }
        },
    };
}
