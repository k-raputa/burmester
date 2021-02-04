#!/usr/bin/env bash
#DESCRIPTION: install dependencies and build for production

INCLUDE: ./install-dependencies.sh
INCLUDE: ./build.sh
INCLUDE: ./copy-to-theme.sh
