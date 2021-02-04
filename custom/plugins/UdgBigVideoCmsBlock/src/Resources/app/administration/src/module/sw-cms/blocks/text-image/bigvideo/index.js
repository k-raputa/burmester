import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'bigvideo',
    label: 'sw-cms.blocks.TextImageCMSBlock.BigVideo.label',
    category: 'text-image',
    component: 'sw-cms-block-bigvideo',
    previewComponent: 'sw-cms-preview-bigvideo',
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
        'image21': {
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
        'videolightbox': {
            type: 'youtube-video',
            default: {
                config: {
                    displayMode: { source: 'static', value: 'cover' }
                }
            }
        },
        'videodesktop': {
            type: 'text'
        },
        'videomobile': {
            type: 'text'
        }
    }
});
