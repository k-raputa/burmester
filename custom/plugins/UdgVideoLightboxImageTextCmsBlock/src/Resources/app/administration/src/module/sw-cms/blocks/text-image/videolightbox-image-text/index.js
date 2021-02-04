import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'videolightbox-image-text',
    label: 'sw-cms.blocks.TextImageCMSBlock.LightboxImageText.label',
    category: 'text-image',
    component: 'sw-cms-block-videolightbox-image-text',
    previewComponent: 'sw-cms-preview-videolightbox-image-text',
    defaultConfig: {
        marginBottom: '0',
        marginTop: '0',
        marginLeft: '0',
        marginRight: '0',
        sizingMode: 'boxed'
    },
    slots: {
        'videolightbox': {
            type: 'youtube-video'
        },
        'left': {
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
        'right': {
            type: 'text',
            default: {
                config: {
                    content: {
                        source: 'static',
                        value: `
                        <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, 
                        sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
                        sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. 
                        Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
                        `.trim()
                    }
                }
            }
        }
    }
});
