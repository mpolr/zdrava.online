#!/usr/bin/env sh

set -e
set -x

exec ./vendor/bin/psalm --no-cache
