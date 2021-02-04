import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'bigcontentslider',
    label: 'sw-cms.blocks.TextImageCMSBlock.BigContentSlider.label',
    category: 'text-image',
    component: 'sw-cms-block-bigcontentslider',
    previewComponent: 'sw-cms-preview-bigcontentslider',
    defaultConfig: {
        marginBottom: '0',
        marginTop: '0',
        marginLeft: '0',
        marginRight: '0',
        sizingMode: 'boxed'
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
        'top': {
            type: 'text',
            default: {
                config: {
                    content: {
                        source: 'static',
                        value: `
                            <p class="chapter-default">
                            Systeme
                            </p>
                            <p class="h1">
                            System lorem ipsum
                            </p>
                        `.trim()
                    }
                }
            }
        },
        'middle': {
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
                            <p class="text-lg">
                            Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna.
                            </p>
                            <div>
                            <div class="button-group">
                            <a class="btn btn-link" href="https://www.burmester.de/">
                            Zu unserem Support-Center
                            </a>
                            </div>
                            </div>
                        `.trim()
                    }
                }
            }
        }
    }
});
