import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'stage',
    label: 'sw-cms.blocks.TextImageCMSBlock.Stage.label',
    category: 'text-image',
    component: 'sw-cms-block-stage',
    previewComponent: 'sw-cms-preview-stage',
    defaultConfig: {
        marginBottom: '0',
        marginTop: '0',
        marginLeft: '0',
        marginRight: '0',
        sizingMode: 'full_width'
    },
    slots: {
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
        },
        'video': {
            type: 'youtube-video',
            default: {
                config: {
                    displayMode: { source: 'static', value: 'cover' }
                }
            }
        }
    }
});
