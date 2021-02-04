#!/usr/bin/env bash
#DESCRIPTION: finish after deployment on server

INCLUDE: ./../../common/actions/cache.sh
bin/console plugin:refresh
bin/console plugin:install --activate --clearCache UdgPluginAutomation
bin/console plugin:update --clearCache UdgPluginAutomation
bin/console udg:pluginautomation:update:environment
bin/console database:migrate --all
bin/console scheduled-task:register

bin/console theme:refresh
bin/console bundle:dump
bin/console assets:install
bin/console theme:change --all UdgBurmesterTheme

INCLUDE: ./../../common/actions/cache.sh
bin/console cache:clear

rm -rf public/maintenance/maintenance.marker

bin/console dal:refresh:index
bin/console cache:warmup
bin/console http:cache:warm:up
