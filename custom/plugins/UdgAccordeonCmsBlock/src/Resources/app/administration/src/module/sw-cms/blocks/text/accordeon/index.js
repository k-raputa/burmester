import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name: 'accordeon',
    label: 'Accordeon',
    category: 'text',
    component: 'sw-cms-block-accordeon',
    previewComponent: 'sw-cms-preview-accordeon',
    defaultConfig: {
        marginBottom: '0',
        marginTop: '0',
        marginLeft: '0',
        marginRight: '0',
        sizingMode: 'boxed'
    },
    slots: {
        slide1: {
            type: 'accordeon',
            default: {
                config: {
                    content: {
                        source: 'static',
                        value: `
                        <p class="text-lg">
                           Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.
                        </p>
                        `.trim()
                    }
                }
            }
        },
        slide2: {
            type: 'accordeon',
            default: {
                config: {
                    content: {
                        source: 'static',
                        value: `
                        <p class="text-lg">
                           Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.
                        </p>
                        `.trim()
                    }
                }
            }
        },
        slide3: {
            type: 'accordeon',
            default: {
                config: {
                    content: {
                        source: 'static',
                        value: `
                        <p class="text-lg">
                           Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.
                        </p>
                        `.trim()
                    }
                }
            }
        },
        slide4: {
            type: 'accordeon',
            default: {
                config: {
                    content: {
                        source: 'static',
                        value: `
                        <p class="text-lg">
                           Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.
                        </p>
                        `.trim()
                    }
                }
            }
        },
        slide5: {
            type: 'accordeon',
            default: {
                config: {
                    content: {
                        source: 'static',
                        value: `
                        <p class="text-lg">
                           Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.
                        </p>
                        `.trim()
                    }
                }
            }
        },
        slide6: {
            type: 'accordeon',
            default: {
                config: {
                    content: {
                        source: 'static',
                        value: `
                        <p class="text-lg">
                           Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.
                        </p>
                        `.trim()
                    }
                }
            }
        }
    }
});
