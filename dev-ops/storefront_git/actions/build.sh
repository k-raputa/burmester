#!/usr/bin/env bash
#DESCRIPTION: Builds the project for production

cd vendor/value-logic/customer-burmester-frontend/_build && node ../node_modules/gulp/bin/gulp.js deploy
