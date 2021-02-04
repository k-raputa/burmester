Shopware.Component.extend('udg-storefinder-create', 'udg-storefinder-detail', {
    methods: {
        getEntity() {
            this.entity = this.repository.create(Shopware.Context.api);
        },

        onClickSave() {
            this.isLoading = true;

            this.repository
                .save(this.entity, Shopware.Context.api)
                .then(() => {
                    this.isLoading = false;
                    this.$router.push({ name: 'udg.storefinder.detail', params: { id: this.entity.id } });
                }).catch((exception) => {
                    this.isLoading = false;

                    this.createNotificationError({
                        title: 'errors on create', //this.$tc('storefinder.detail.errorTitle'),
                        message: exception
                    }
                );
            });
        }
    }
});
