import './component';
import './config';
import './preview';

Shopware.Service('cmsService').registerCmsElement({
    name: 'udg-download',
    label: 'sw-cms.elements.udgDownload.label',
    component: 'sw-cms-el-udg-download',
    configComponent: 'sw-cms-el-config-udg-download',
    previewComponent: 'sw-cms-el-preview-udg-download',
    defaultConfig: {
        media: {
            source: 'static',
            value: null,
            required: true,
            entity: {
                name: 'media'
            }
        },
        displayMode: {
            source: 'static',
            value: 'standard'
        }
    }
});
