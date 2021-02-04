import './component';
import './config';
import './preview';

import deDE from './snippet/de-DE.json';
import enGB from './snippet/en-GB.json';

Shopware.Locale.extend('de-DE', deDE);
Shopware.Locale.extend('en-GB', enGB);

Shopware.Service('cmsService').registerCmsElement({
    name: 'storefinder',
    label: 'sw-cms.elements.udg.storefinder.general.label',
    component: 'sw-cms-el-storefinder',
    configComponent: 'sw-cms-el-config-storefinder',
    previewComponent: 'sw-cms-el-preview-storefinder',
    defaultConfig: {
        mapSrc: {
            source: 'static',
            value: ''
        },
        mapApiKey: {
            source: 'static',
            value: ''
        },
        mapWidth: {
            source: 'static',
            value: '100%'
        },
        mapHeight: {
            source: 'static',
            value: '100%'
        },
    }
});
