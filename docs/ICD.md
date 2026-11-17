# CommShack 1.2.0 interface control document

| Interface | Contract |
|---|---|
| commshack_dashboard | Logged-in tool cards from local tools.json |
| commshack_chat / commshack_mail | Logged-in HTTPS iframe |
| commshack_files | Logged-in adapter to separately supplied shared_files shortcode |
| commshack_help / commshack_about | Logged-in embedded help/version |
| livewire_dashboard/chat/mail/files | Compatibility aliases to the same callbacks |
| Tools > CommShack Help & About | WordPress manage_options capability required |
| COMMSHACK_XMPP_URL / COMMSHACK_MAIL_URL | Protected site constants for endpoints |
| commshack_xmpp_url / commshack_mail_url | WordPress endpoint filters |
| LIVEWIRE_* constants / livewire_* filters | Legacy fallbacks retained |

New constants take priority over legacy constants; legacy filters run before new
filters. URLs require HTTPS and a host without URL credentials. Card tool schema:
JSON array (first 100 entries) of objects with string icon, title, desc, url.
Malformed entries are skipped; invalid JSON returns no cards. Text/attributes are
escaped through WordPress APIs. CommShack creates no REST API, session protocol,
password store, database schema or server-side HTTP proxy. Browsers connect to
embedded services directly; each service retains authentication and frame policy.

Host installer: commshack --wordpress-root ABSOLUTE_PATH copies staged plugin/theme
files into an existing wp-content tree after checking wp-load.php, directory
layout, symlinks and destination absence. Requires root. No activation, service
restart or /etc configuration change occurs. RPM source: /usr/share/commshack;
launcher: /usr/bin/commshack. Installed WordPress copies survive RPM removal.
This integration contract assumes separately deployed WordPress/PHP/database and
parent theme. Apache, HAProxy, Keepalived and Prosody files are inactive templates.
