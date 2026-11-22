#!/bin/sh
# Rewrites composer.json (without installing) so the dev dependencies match one
# Flysystem major. composer.json's own require-dev is the Flysystem 3 set.
# usage: .github/scripts/use-flysystem.sh <1|2|3>
set -eu

V3_DEV="league/flysystem-async-aws-s3 league/flysystem-aws-s3-v3 league/flysystem-ftp \
league/flysystem-google-cloud-storage league/flysystem-sftp-v3 league/flysystem-webdav \
spatie/flysystem-dropbox"

case "${1:-}" in
  3)
    composer require --no-update "league/flysystem:^3.0"
    ;;
  2)
    composer remove --dev --no-update $V3_DEV
    composer require --no-update "league/flysystem:^2.1.1"
    composer require --dev --no-update \
      "league/flysystem-async-aws-s3:^2.0" \
      "league/flysystem-aws-s3-v3:^2.0" \
      "league/flysystem-ftp:^2.4" \
      "league/flysystem-google-cloud-storage:^2.0" \
      "league/flysystem-sftp:^2.0" \
      "spatie/flysystem-dropbox:^2.0"
    ;;
  1)
    composer remove --dev --no-update $V3_DEV
    composer require --no-update "league/flysystem:^1.1.4"
    composer require --dev --no-update \
      "aws/aws-sdk-php:^3.133.20" \
      "league/flysystem-aws-s3-v3:^1.0.24" \
      "league/flysystem-rackspace:^1.0" \
      "league/flysystem-sftp:^1.0" \
      "league/flysystem-webdav:^1.0" \
      "spatie/flysystem-dropbox:^1.2" \
      "srmklive/flysystem-dropbox-v2:^1.0" \
      "superbalist/flysystem-google-storage:^7.2"
    ;;
  *)
    echo "usage: $0 <1|2|3>" >&2
    exit 64
    ;;
esac
