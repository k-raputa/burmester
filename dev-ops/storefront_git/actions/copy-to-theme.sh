#!/usr/bin/env bash
#DESCRIPTION: Copy the files to the theme

cp vendor/udg/customer-burmester-frontend/dist/css/style.min.css custom/plugins/UdgBurmesterTheme/src/Resources/app/storefront/dist/storefront/css/style.min.scss
cp vendor/udg/customer-burmester-frontend/dist/js/* custom/plugins/UdgBurmesterTheme/src/Resources/app/storefront/dist/storefront/js
cp vendor/udg/customer-burmester-frontend/dist/js/* public/js
cp vendor/udg/customer-burmester-frontend/dist/favicon/* public
cp vendor/udg/customer-burmester-frontend/dist/assets/fonts/* custom/plugins/UdgBurmesterTheme/src/Resources/app/storefront/src/assets/fonts
