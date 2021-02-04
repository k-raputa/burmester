import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'storefinderteaser',
    label: 'sw-cms.blocks.TextImageCMSBlock.StorefinderTeaser.label',
    category: 'text-image',
    component: 'sw-cms-block-storefinderteaser',
    previewComponent: 'sw-cms-preview-storefinderteaser',
    defaultConfig: {
        cssClass: 'component-teaser-storefinder',
        marginBottom: '0',
        marginTop: '0',
        marginLeft: '0',
        marginRight: '0',
        sizingMode: 'boxed'
    },
    slots: {
        'top': {
            type: 'image',
            default: {
                config: {
                    displayMode: { source: 'static', value: 'cover' }
                },
                data: {
                    media: {
                        url: '/administration/static/img/cms/preview_glasses_large.jpg'
                    }
                }
            }
        },
        'bottom': {
            type: 'text',
            default: {
                config: {
                    content: {
                        source: 'static',
                        value: `
                            <h2 class="chapter-default">
                                Fachhändlersuche
                            </h2>
                            <h3 class="h1">
                                Jetzt Burmester live erleben
                            </h3>
                            <p class="text-lg">
                                Lorem Ipsum Dolorent debitaes Ihitatius debitaes verumen Ipsum Dolorent debitaes verumen duntur
                            </p>
                        `.trim()
                    }
                }
            }
        }
    }
});
