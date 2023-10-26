#!/usr/bin/env sh

set -e
set -x

vendor/bin/rector --version
vendor/bin/rector process --dry-run
