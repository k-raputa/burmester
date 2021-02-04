import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'text-slider',
    label: 'Text slider',
    category: 'text',
    component: 'sw-cms-block-text-slider',
    previewComponent: 'sw-cms-preview-text-slider',
    defaultConfig: {
        cssClass: 'theme-mine-shaft',
        marginBottom: '0',
        marginTop: '0',
        marginLeft: '0',
        marginRight: '0',
        sizingMode: 'boxed'
    },
    slots: {
        slide1: {
            type: 'text',
            default: {
                config: {
                    content: {
                        source: 'static',
                        value: `
                        <h3 class="text-quote">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid assumenda blanditiis est quidem voluptatum.
                        </h3>
                        <p class="chapter-secondary">
                            Lorem ipsum
                        </p>
                        `.trim()
                    }
                }
            }
        },
        slide2: {
            type: 'text',
            default: {
                config: {
                    content: {
                        source: 'static',
                        value: `
                        <h3 class="text-quote">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid assumenda blanditiis est quidem voluptatum.
                        </h3>
                        <p class="chapter-secondary">
                            Lorem ipsum
                        </p>
                        `.trim()
                    }
                }
            }
        },
        slide3: {
            type: 'text',
            default: {
                config: {
                    content: {
                        source: 'static',
                        value: `
                        <h3 class="text-quote">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid assumenda blanditiis est quidem voluptatum.
                        </h3>
                        <p class="chapter-secondary">
                            Lorem ipsum
                        </p>
                        `.trim()
                    }
                }
            }
        }
    }
});
