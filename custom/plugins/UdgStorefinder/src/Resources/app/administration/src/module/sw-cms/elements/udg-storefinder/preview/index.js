import template from './sw-cms-el-preview-storefinder.html.twig';
import './sw-cms-el-preview-storefinder.scss';

const { Component } = Shopware;

Component.register(
    'sw-cms-el-preview-storefinder',
    {
        template
    }
);
