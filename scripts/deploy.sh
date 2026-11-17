#!/bin/bash
# Install integration files only; never apply network configuration.
set -euo pipefail
SOURCE=$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")/.." && pwd)
if [[ $# -ne 2 || "$1" != "--wordpress-root" ]]; then
  echo "Usage: sudo bash scripts/deploy.sh --wordpress-root /absolute/wordpress/path"
  echo "Installs plugin/theme into existing WordPress; refuses existing CommShack files."
  echo "No services, network configuration, database or activation settings are changed."
  exit 2
fi
[[ $EUID -eq 0 ]] || { echo 'Run with sudo.' >&2; exit 1; }
[[ "$2" = /* && "$2" != / ]] || { echo 'Use an absolute WordPress directory.' >&2; exit 1; }
target=$(realpath -e -- "$2")
[[ -f "$target/wp-load.php" && -d "$target/wp-content/plugins" && -d "$target/wp-content/themes" ]] || { echo 'WordPress installation not found.' >&2; exit 1; }
[[ ! -L "$target/wp-content" ]] || { echo 'Symlinked wp-content requires manual installation.' >&2; exit 1; }
for kind in plugins themes; do
  [[ ! -L "$target/wp-content/$kind" ]] || { echo 'Symlinked content directories require manual installation.' >&2; exit 1; }
done
for dest in "$target/wp-content/plugins/commshack-shortcodes" "$target/wp-content/themes/commshack-child"; do
  [[ ! -e "$dest" && ! -L "$dest" ]] || { echo "Refusing to overwrite $dest. Back up and upgrade manually." >&2; exit 1; }
done
umask 022
cp -R -- "$SOURCE/plugins/commshack-shortcodes" "$target/wp-content/plugins/"
cp -R -- "$SOURCE/themes/commshack-child" "$target/wp-content/themes/"
echo 'Files installed. Deactivate the old LiveWire plugin, then activate CommShack in WordPress.'
echo 'Install the Twenty Twenty-Four parent theme separately before activating the child theme.'
echo 'Review Tools > CommShack Help & About. Service configuration remains unchanged.'
