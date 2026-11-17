#!/bin/bash
set -euo pipefail
cd -- "$(dirname -- "${BASH_SOURCE[0]}")/.."
fixture=$(mktemp -d)
trap 'rm -rf "$fixture"' EXIT
target="$fixture/wordpress test"
mkdir -p "$target/wp-content/plugins" "$target/wp-content/themes"
touch "$target/wp-load.php"
if bash scripts/deploy.sh; then echo 'Default deploy must refuse side effects.' >&2; exit 1; fi
if bash scripts/deploy.sh --wordpress-root "$fixture/missing"; then exit 1; fi
mkdir "$fixture/linked"
touch "$fixture/linked/wp-load.php"
ln -s "$target/wp-content" "$fixture/linked/wp-content"
if bash scripts/deploy.sh --wordpress-root "$fixture/linked"; then echo 'Must reject symlinked content.' >&2; exit 1; fi
bash scripts/deploy.sh --wordpress-root "$target"
test -f "$target/wp-content/plugins/commshack-shortcodes/commshack-shortcodes.php"
test -f "$target/wp-content/themes/commshack-child/style.css"
printf '%s' preserved > "$target/wp-content/plugins/commshack-shortcodes/operator-marker"
if bash scripts/deploy.sh --wordpress-root "$target"; then echo 'Must reject overwrite.' >&2; exit 1; fi
test "$(cat "$target/wp-content/plugins/commshack-shortcodes/operator-marker")" = preserved
echo 'PASS: explicit target, spaces, missing installation and overwrite protection'
