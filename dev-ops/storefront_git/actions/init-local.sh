#!/usr/bin/env bash
#DESCRIPTION: Builds the project for production

rm composer.lock
rm -rf vendor/udg/customer-burmester-frontend/
composer install
cd vendor/udg/customer-burmester-frontend/_build && npm install
cd vendor/udg/customer-burmester-frontend/_build && node ../node_modules/gulp/bin/gulp.js deploy

INCLUDE: ./copy-to-theme.sh

INCLUDE: ./../../storefront/actions/build.sh

rm -rf public/frontend_dist/
mkdir public/frontend_dist/
cp -r vendor/udg/customer-burmester-frontend/dist/* public/frontend_dist/
