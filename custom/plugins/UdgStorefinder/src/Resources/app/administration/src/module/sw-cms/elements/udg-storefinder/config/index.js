import template from './sw-cms-el-config-storefinder.html.twig';
import './sw-cms-el-config-storefinder.scss';

const { Component, Mixin } = Shopware;

Component.register(
    'sw-cms-el-config-storefinder',
    {
        template,

        mixins: [
            Mixin.getByName('cms-element')
        ],

        created() {
            this.createdComponent();
        },

        methods: {
            createdComponent() {
                this.initElementConfig('storefinder');
            },
        }
    }
);
