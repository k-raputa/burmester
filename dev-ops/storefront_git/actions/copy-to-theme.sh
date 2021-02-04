#!/usr/bin/env bash
#DESCRIPTION: Copy the files to the theme

cp vendor/value-logic/customer-burmester-frontend/dist/css/style.min.css custom/plugins/UdgBurmesterTheme/src/Resources/app/storefront/dist/storefront/css/style.min.scss
cp vendor/value-logic/customer-burmester-frontend/dist/js/* custom/plugins/UdgBurmesterTheme/src/Resources/app/storefront/dist/storefront/js
cp vendor/value-logic/customer-burmester-frontend/dist/js/* public/js
cp vendor/value-logic/customer-burmester-frontend/dist/favicon/* public
cp vendor/value-logic/customer-burmester-frontend/dist/assets/fonts/* custom/plugins/UdgBurmesterTheme/src/Resources/app/storefront/src/assets/fonts
