#!/bin/bash
set -euo pipefail
cd -- "$(dirname -- "${BASH_SOURCE[0]}")/.."
major=$1
engine=${CONTAINER_ENGINE:-podman}
"$engine" run --rm --network none --security-opt label=disable -v "$PWD:/src:ro" "docker.io/rockylinux/rockylinux:$major" bash -c '
set -euo pipefail
mkdir /tmp/bundle
tar -xzf /src/releases/1.2.0/commshack-1.2.0-el'"$major"'-noarch-offline.tar.gz -C /tmp/bundle
bash /tmp/bundle/install.sh
bash /usr/share/commshack/scripts/deploy.sh --wordpress-root /nonexistent && exit 1
test -f /usr/share/commshack/docs/ICD.md
test -f /usr/share/commshack/plugins/commshack-shortcodes/commshack-shortcodes.php
mkdir -p /tmp/wordpress/wp-content/{plugins,themes}
touch /tmp/wordpress/wp-load.php
commshack --wordpress-root /tmp/wordpress
test -f /tmp/wordpress/wp-content/plugins/commshack-shortcodes/commshack-shortcodes.php
rpm -e commshack
test -f /tmp/wordpress/wp-content/plugins/commshack-shortcodes/commshack-shortcodes.php
echo "PASS: offline RPM install, explicit WordPress integration and uninstall retention"
'
