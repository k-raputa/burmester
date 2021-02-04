import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'image-13',
    label: 'sw-cms.blocks.ImageCMSBlock.Image13.label',
    category: 'image',
    component: 'sw-cms-block-image-13',
    previewComponent: 'sw-cms-preview-image-13',
    defaultConfig: {
        marginBottom: '20px',
        marginTop: '20px',
        marginLeft: '20px',
        marginRight: '20px',
        sizingMode: 'boxed'
    },
    slots: {
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
