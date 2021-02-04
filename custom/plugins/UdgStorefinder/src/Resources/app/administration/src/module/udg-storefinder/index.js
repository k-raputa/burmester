import './page/udg-storefinder-list';
import './page/udg-storefinder-detail';
import './page/udg-storefinder-create';

import deDE from './snippet/de-DE.json';
import enGB from './snippet/en-GB.json';

const { Module } = Shopware;
const UdgStorefinderColor = '#ff68b4';

Module.register('udg-storefinder', {
    type: 'plugin',
    name: 'udg.storefinder.module.general.label',
    title: 'udg.storefinder.module.general.title',
    description: 'udg.storefinder.module.general.description',
    version: '1.0.0',
    targetVersion: '1.0.0',
    color: UdgStorefinderColor,
    icon: 'default-object-lab-flask',
    entity: 'udg_storefinder',

    snippets: {
        'de-DE': deDE,
        'en-GB': enGB
    },

    routes: {
        index: {
            components: {
                default: 'udg-storefinder-list'
            },
            path: 'index'
        },
        detail: {
            components: {
                default: 'udg-storefinder-detail'
            },
            path: 'detail/:id',
            meta: {
                parentPath: 'udg.storefinder.index'
            }
        },
        create: {
            components: {
                default: 'udg-storefinder-create'
            },
            path: 'create',
            meta: {
                parentPath: 'udg.storefinder.index'
            }
        }
    },

    navigation: [{
        id: 'udg-storefinder',
        label: 'udg.storefinder.module.general.navigation',
        color: UdgStorefinderColor,
        parent: 'sw-content',
        path: 'udg.storefinder.index',
        icon: 'default-object-lab-flask',
        position: 100
    }]
});
