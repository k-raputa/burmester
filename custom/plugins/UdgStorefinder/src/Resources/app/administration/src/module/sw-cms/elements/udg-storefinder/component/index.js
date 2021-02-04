import template from './sw-cms-el-storefinder.html.twig';
import './sw-cms-el-storefinder.scss';

const { Component, Mixin } = Shopware;

Component.register(
    'sw-cms-el-storefinder',
    {
        template,

        mixins: [
            Mixin.getByName('cms-element')
        ],

        computed: {
            mapSrc() {
                return '';
            },
            mapApiKey() {
                return '';
            },
            mapWidth() {
                return '';
            },
            mapHeight() {
                return '';
            }
        },

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
