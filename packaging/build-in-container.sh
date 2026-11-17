#!/bin/bash
set -euo pipefail
major=$1
dnf -y install rpm-build tar gzip zip php-cli
work=$(mktemp -d)
trap 'rm -rf "$work"' EXIT
source="$work/commshack-1.2.0"
mkdir "$source"
for item in plugins themes configs scripts docs tests packaging .github build.sh install.sh README.md HELP.md RELEASE_NOTES.md LICENSE .gitignore; do cp -a "/src/$item" "$source/"; done
cd "$source"
find plugins themes tests -name '*.php' -exec php -l {} \;
php tests/plugin-test.php
bash tests/deploy-test.sh
top="$work/rpmbuild"
mkdir -p "$top"/{SOURCES,SPECS,BUILD,BUILDROOT,RPMS,SRPMS}
tar -C "$work" -czf "$top/SOURCES/commshack-1.2.0.tar.gz" commshack-1.2.0
rpmbuild -bb --define "_topdir $top" --define "dist .el$major" packaging/commshack.spec
out=/src/releases/1.2.0
cp "$top"/RPMS/noarch/*.rpm "$out/"
cp "$top/SOURCES/commshack-1.2.0.tar.gz" "$out/commshack-1.2.0-el$major-source.tar.gz"
(cd plugins; zip -qr "$out/commshack-shortcodes-1.2.0-el$major.zip" commshack-shortcodes)
(cd themes; zip -qr "$out/commshack-child-1.2.0-el$major.zip" commshack-child)
bundle="$work/bundle"
mkdir "$bundle"
cp "$out/commshack-1.2.0-1.el$major.noarch.rpm" "$bundle/"
cp install.sh README.md RELEASE_NOTES.md "$bundle/"
(cd "$bundle"; sha256sum ./*.rpm install.sh README.md RELEASE_NOTES.md > SHA256SUMS)
tar -C "$bundle" -czf "$out/commshack-1.2.0-el$major-noarch-offline.tar.gz" .
