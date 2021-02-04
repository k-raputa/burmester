import './component';
import './config';
import './preview';

Shopware.Service('cmsService').registerCmsElement({
    name: 'accordeon',
    label: 'Accordeon',
    component: 'sw-cms-el-accordeon',
    configComponent: 'sw-cms-el-config-accordeon',
    previewComponent: 'sw-cms-el-preview-accordeon',
    defaultConfig: {
        content: {
            source: 'static',
            value: `
                <p class="text-lg">
                   Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut.
                </p>
            `.trim()
        },
        header: {
            source: 'static',
            value: `Ipsum Lorem  dolor sit amet`
        }
    }
});
