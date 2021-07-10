#!/usr/bin/env bash

BASEDIR=$(dirname "$0")
cd $BASEDIR/public && php -S localhost:8888 || exit 1
