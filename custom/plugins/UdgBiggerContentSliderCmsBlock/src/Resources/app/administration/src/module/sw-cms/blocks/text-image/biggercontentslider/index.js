import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'biggercontentslider',
    label: 'sw-cms.blocks.TextImageCMSBlock.BiggerContentSlider.label',
    category: 'text-image',
    component: 'sw-cms-block-biggercontentslider',
    previewComponent: 'sw-cms-preview-biggercontentslider',
    defaultConfig: {
        marginBottom: '0',
        marginTop: '0',
        marginLeft: '0',
        marginRight: '0',
        sizingMode: 'full_width'
    },
    slots: {
        'slidername': {
            type: 'text',
            default: {
                config: {
                    content: {
                        source: 'static',
                        value: `
                            Slider 1
                        `.trim()
                    }
                }
            }
        },
        'text': {
            type: 'text',
            default: {
                config: {
                    content: {
                        source: 'static',
                        value: `
                        <h1 class="headline-product">705</h1>
                        <h1>Lorem ipsum dolor sit amett.</h1>
                        <p>Stet clita kasd gubergren, no sea takimata sanctus est Lorem takimata.</p>
                        <a class="btn btn-link" href="#">Mehr erfahren</a>
                        `.trim()
                    }
                }
            }
        },
        'image32': {
            type: 'image',
            default: {
                config: {
                    displayMode: { source: 'static', value: 'cover' }
                },
                data: {
                    media: {
                        url: '/administration/static/img/cms/preview_camera_large.jpg'
                    }
                }
            }
        },
        'image11': {
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
        }
    }
});
