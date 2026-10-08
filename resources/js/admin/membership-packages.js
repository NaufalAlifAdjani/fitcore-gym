function emptyPackageForm() {
    return {
        id: null,
        name: '',
        badge: '',
        tier: 'Basic',
        description: '',
        price: '',
        promo_price: '',
        duration_value: 12,
        duration_unit: 'Bulan',
        duration_in_days: 365,
        facilities: [],
        pt_sessions: 0,
        is_active: true,
    };
}

export function membershipPackages({
    hasErrors,
    oldForm,
    packageBaseUrl,
    storeUrl,
}) {
    return {
        showModal: false,
        isEditMode: false,
        newFeature: '',
        form: emptyPackageForm(),

        init() {
            if (!hasErrors) {
                return;
            }

            this.form = {
                ...emptyPackageForm(),
                ...oldForm,
                facilities: Array.isArray(oldForm.facilities) ? oldForm.facilities : [],
            };
            this.isEditMode = Boolean(this.form.id);
            this.showModal = true;
        },

        resetForm() {
            this.isEditMode = false;
            this.newFeature = '';
            this.form = emptyPackageForm();
        },

        openCreateModal() {
            this.resetForm();
            this.showModal = true;
        },

        openEditModal(packageData) {
            this.isEditMode = true;
            this.newFeature = '';
            this.form = {
                ...emptyPackageForm(),
                ...packageData,
                facilities: Array.isArray(packageData.facilities) ? [...packageData.facilities] : [],
            };
            this.showModal = true;
        },

        addFeature() {
            const feature = this.newFeature.trim();

            if (feature && !this.form.facilities.includes(feature)) {
                this.form.facilities.push(feature);
            }

            this.newFeature = '';
        },

        removeFeature(index) {
            this.form.facilities.splice(index, 1);
        },

        discountPercent() {
            const price = Number(this.form.price);
            const promoPrice = Number(this.form.promo_price);
            const hasPromoPrice = this.form.promo_price !== '' && this.form.promo_price !== null;

            if (price <= 0 || !hasPromoPrice || promoPrice < 0 || promoPrice >= price) {
                return 0;
            }

            return Math.round(((price - promoPrice) / price) * 100);
        },

        formAction() {
            if (!this.isEditMode) {
                return storeUrl;
            }

            return `${packageBaseUrl}/${encodeURIComponent(this.form.id)}`;
        },
    };
}

export function membershipPackageStatus({ url, isActive }) {
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
