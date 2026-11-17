# CommShack

WordPress communications portal integration. Formerly LiveWire Portal.
Version 1.2.0 · Mike Anderson · MIT · Cyber Chief toolkit.

Includes an authenticated dashboard, HTTPS chat/mail embeds, file-plugin adapter,
child theme, embedded Help/About, deployment templates and documentation.
Existing livewire_* shortcodes and URL configuration remain compatible.

## Install and releases

Use the Rocky 9/10 integration RPM or matching offline archive from
[GitHub Releases](https://github.com/mike0615/commshack/releases). Run
`sudo bash install.sh` after extracting and verifying the bundle. This stages
files under /usr/share/commshack and installs the commshack command.
Then read [installation](docs/INSTALL.md) before using
`sudo commshack --wordpress-root /absolute/wordpress/path`.

Plugin and child-theme ZIPs can instead be installed through WordPress.
WordPress 6.4+, PHP 8.0+, a database and the Twenty Twenty-Four parent theme are
separately supplied prerequisites. Chat, mail, file storage, SSO and HA services
are also separate deployments. These integration packages do not install a
complete WordPress/mail/XMPP/HA stack. No network config, database, account,
activation setting or service is changed by RPM installation.

## Documentation

- [Help](HELP.md) — also Tools > CommShack Help & About in WordPress
- [Installation and migration](docs/INSTALL.md)
- [Operations](docs/OPERATIONS.md)
- [ICD](docs/ICD.md), [specification](docs/SPEC_SHEET.md)
- [Code review and validation](docs/CODE_REVIEW.md)
- [Release notes](RELEASE_NOTES.md)

Historical architecture/site checklists are planning references, not a verified
HA deployment. Read the current install/review documents first. Configure endpoints
for your site and retain backend authorization; WordPress login is not SSO setup.

## Build

`bash build.sh 9 10` uses Podman; set CONTAINER_ENGINE=docker if needed. A connected
builder installs its build dependencies from Rocky repositories. Integration
installation on standard Rocky 9/10 requires no network downloads. Run
`bash packaging/test-offline.sh 9` and `10` for network-disabled RPM checks.
Plugin harness tests exercise WordPress API boundaries; they do not establish
production SSO, mail/chat, third-party plugin behavior or cross-site failover.
Application RPMs are unsigned. Authenticate release media and checksum manifests.
