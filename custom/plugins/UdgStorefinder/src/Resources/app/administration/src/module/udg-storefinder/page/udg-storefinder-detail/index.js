import template from './detail.html.twig';

const { Component, Mixin } = Shopware;

Component.register('udg-storefinder-detail', {
    template,

    inject: ['repositoryFactory'],

    mixins: [
        Mixin.getByName('notification'),
        Mixin.getByName('placeholder')
    ],

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    data() {
        return {
            entity: null,
            isLoading: false,
            processSuccess: false,
            repository: null,
            country: null
        };
    },

    created() {
        this.repository = this.repositoryFactory.create('udg_storefinder');
        this.getEntity();
    },

    computed: {
        countryRepository() {
            return this.repositoryFactory.create('country');
        },

        country: {
            get() {
                return this.entity.country;
            },

            set(countryId) {
                return this.entity.country = country;
            }
        }
    },

    methods: {
        getEntity() {
            this.repository
                .get(this.$route.params.id, Shopware.Context.api)
                .then((entity) => {
                    this.entity = entity;
                });
        },
        saveOnLanguageChange() {
            return this.onClickSave();
        },
        abortOnLanguageChange() {
            return this.repository.hasChanges(this.entity);
        },
        onChangeLanguage() {
            this.getEntity();
        },
        onClickSave() {
            this.isLoading = true;

            this.repository
                .save(this.entity, Shopware.Context.api)
                .then(() => {
                    this.getEntity();
                    this.isLoading = false;
                    this.processSuccess = true;
                }).catch((exception) => {
                    this.isLoading = false;
                    this.createNotificationError({
                        title: this.$tc('udg.storefinder.module.page.detail.error'),
                        message: exception
                    }
                );
            });
        },
        saveFinish() {
            this.processSuccess = false;
        },
    }
});
