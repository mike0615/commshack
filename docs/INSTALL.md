# CommShack installation and migration

## Prerequisites
An existing WordPress 6.4+ site with PHP 8.0+, a configured database, HTTPS and
WordPress user authentication. The child theme requires Twenty Twenty-Four.
Provision all prerequisites and third-party services from approved offline media.
Their installers are not in the integration RPM. Back up WordPress files, database,
plugins, theme configuration and endpoint configuration before migrating.

## RPM or ZIP installation
For standard Rocky Linux 9/10, extract the matching offline archive into an empty
directory and verify SHA256SUMS from trusted release media. Run sudo bash install.sh.
The unsigned integration RPM stages files under /usr/share/commshack only.
Run sudo commshack --wordpress-root /absolute/wordpress/path to copy plugin/theme
files to the chosen existing installation. Symlinked content directories and
existing CommShack destinations are refused; use reviewed manual deployment for
those layouts. The command does not fetch Git or overwrite service configuration.

Alternatively install the commshack-shortcodes and commshack-child ZIPs through
WordPress. Deactivate LiveWire Shortcodes before activating CommShack Shortcodes.
Existing livewire_* page shortcodes and LIVEWIRE_* URL constants remain valid.
Activate the child theme only after confirming its parent is installed. Preview
the theme and preserve existing customizations rather than replacing them blindly.
Configure your own HTTPS endpoints and tool list. Legacy acc.local names are
examples; no credentials or accounts are provisioned. WordPress caching must not
cache authenticated shortcode output for anonymous users; test this explicitly.

## Network services and SSO
All configs are examples under /usr/share/commshack/configs. None are installed
under /etc automatically. Adapt document root, hostnames, CA, keytabs and service
settings to your site; validate service syntax before any deliberate activation.
The Apache example requires mod_auth_gssapi and HTTPS; it does not map REMOTE_USER
into a WordPress account by itself. Supply/test a supported SSO bridge and roles.
There is no Basic password fallback. Preserve TLS to HAProxy backends with proper
CA/hostname verification. Provide the isolated health endpoint referenced by the
HAProxy template; never health-check wp-login.php. Backend access and SSO/CSRF
protections remain the responsibility of each service.

## Upgrades and removal
The explicit file copier refuses existing CommShack directories. Back them up and
perform a reviewed WordPress/plugin upgrade, retaining tools.json and configuration.
RPM upgrades update staged source only; they do not change live WordPress files.
RPM removal retains copies already deployed into WordPress. Deactivate and remove
those through your normal WordPress process if desired. Never remove a database,
uploads or site configuration as part of package cleanup. Roll back from backups
if the migration fails; test real login, dashboard, embeds and permissions first.
