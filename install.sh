#!/bin/bash
set -euo pipefail
cd -- "$(dirname -- "${BASH_SOURCE[0]}")"
[[ $EUID -eq 0 ]] || { echo 'Run with sudo.' >&2; exit 1; }
# shellcheck source=/dev/null
source /etc/os-release
major=${VERSION_ID%%.*}
[[ "$ID" == rocky && ( "$major" == 9 || "$major" == 10 ) ]] || { echo 'Requires Rocky Linux 9 or 10.' >&2; exit 1; }
sha256sum --check --strict SHA256SUMS
dnf -y --disablerepo='*' --setopt=install_weak_deps=False --nogpgcheck install "./commshack-1.2.0-1.el$major.noarch.rpm"
echo 'CommShack integration installed under /usr/share/commshack.'
echo 'Existing WordPress/PHP/database and the parent theme are required separately.'
echo 'No live service configuration changed. Read /usr/share/commshack/docs/INSTALL.md.'
