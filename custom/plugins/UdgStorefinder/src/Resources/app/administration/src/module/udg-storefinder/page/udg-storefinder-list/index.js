import template from './list.html.twig';

const { Component, Mixin } = Shopware;
const { Criteria } = Shopware.Data;

Component.register('udg-storefinder-list', {
    template,

    inject: ['repositoryFactory'],

    mixins: [
        Mixin.getByName('listing')
    ],

    data() {
        return {
            isLoading: false,
            criteria: null,
            repository: null,
            entities: null,
            total: null,
            term: (this.$route.query ? this.$route.query.term : null)
        };
    },

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    computed: {
        columns() {
            return [{
                property: 'company',
                dataIndex: 'company',
                label: this.$tc('udg.storefinder.module.column.company'),
                routerLink: 'udg.storefinder.detail',
                inlineEdit: 'string',
                allowResize: true,
                primary: true
            }, {
                property: 'street',
                dataIndex: 'street',
                label: this.$tc('udg.storefinder.module.column.street'),
                inlineEdit: 'string',
                allowResize: true
            }, {
                property: 'location',
                dataIndex: 'location',
                label: this.$tc('udg.storefinder.module.column.location'),
                inlineEdit: 'string',
                allowResize: true
            }, {
                property: 'country.name',
                dataIndex: 'country.name',
                label: this.$tc('udg.storefinder.module.column.country'),
                allowResize: true
            }, {
                property: 'phone',
                dataIndex: 'phone',
                label: this.$tc('udg.storefinder.module.column.phone'),
                inlineEdit: 'string',
                allowResize: true
            }, {
                property: 'email',
                dataIndex: 'email',
                label: this.$tc('udg.storefinder.module.column.email'),
                inlineEdit: 'string',
                allowResize: true
            }, {
                property: 'web',
                dataIndex: 'web',
                label: this.$tc('udg.storefinder.module.column.web'),
                inlineEdit: 'string',
                allowResize: true
            }, {
                property: 'active',
                dataIndex: 'active',
                label: this.$tc('udg.storefinder.module.column.active'),
                inlineEdit: 'bool',
                allowResize: true
            }];
        }
    },

    created() {
        this.createdComponent();
    },

    methods: {
        onChangeLanguage(languageId) {
            this.createdComponent();
        },
        createdComponent() {
            this.repository = this.repositoryFactory.create('udg_storefinder');

            this.criteria = new Criteria();
            this.criteria.addSorting(Criteria.sort('company', 'ASC'));
            this.criteria.addSorting(Criteria.sort('createdAt', 'ASC'));
            this.criteria.addAssociation('country');

            if (this.term) {
                this.criteria.setTerm(this.term);
            }

            this.isLoading = true;

            const context = { ...Shopware.Context.api, inheritance: true };

            this.repository
                .search(this.criteria, context)
                .then((result) => {
                    this.entities = result;
                    this.total = result.total;
                    this.isLoading = false;
                });
        },
        onSearch(term) {
            this.criteria.setTerm(term);
            this.$route.query.term = term;
            this.$refs.listing.doSearch();
        }
    }
});
