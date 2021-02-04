#!/usr/bin/env bash
#DESCRIPTION: build package for deployment

INCLUDE: ../../administration/actions/install-dependencies.sh
PROJECT_ROOT=__PROJECT_ROOT__ npm run --prefix vendor/shopware/platform/src/Administration/Resources/app/administration/ build

INCLUDE: ../../storefront/actions/install-dependencies.sh
PROJECT_ROOT=__PROJECT_ROOT__/  npm --prefix vendor/shopware/platform/src/Storefront/Resources/app/storefront/ run production

INCLUDE: ./../../storefront_git/actions/init.sh
rm -rf vendor/value-logic/customer-burmester-frontend/
rm -rf vendor/shopware/platform/src/Administration/Resources/node_modules/
rm -rf vendor/shopware/platform/src/Administration/Resources/app/administration/node_modules/
rm -rf vendor/shopware/platform/src/Storefront/Resources/app/storefront/node_modules/

touch public/maintenance/maintenance.marker
