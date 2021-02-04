#!/usr/bin/env bash
#DESCRIPTION: Builds the project for production

cd vendor/udg/customer-burmester-frontend/_build && node ../node_modules/gulp/bin/gulp.js deploy
