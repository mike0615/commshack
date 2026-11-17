# CommShack operations

Configure and back up WordPress, its database, endpoint settings and tools.json.
CommShack stores no user/service passwords and creates no database tables.
Keep service credentials in the owning service's protected configuration/vault.

If a card is absent, validate tools.json: array of up to 100 objects containing
string icon, title, desc and HTTPS url without embedded credentials. Invalid
entries are omitted. If an embed is blank, check destination authentication,
TLS trust, frame-ancestors/X-Frame-Options and cookie policy. Do not disable those
controls globally to force an embed; use a separate destination link if needed.
Missing Files means the separate shared_files provider has not been installed.

Login enforcement uses WordPress is_user_logged_in; configure SSO and role mapping
separately. Verify that full-page caches/CDNs never serve private shortcode output
to logged-out users. Embedded services must enforce their own authorization.
Use Tools > CommShack Help & About for local help/version information.

The installer never restarts Apache, Prosody, HAProxy or Keepalived. Review and
activate site configurations with their owning service tools. HA/DR claims require
actual failover tests, database consistency and routing qualification. VRRP unicast
does not provide arbitrary cross-subnet VIP routing; PASS is limited and is not
encryption. Use site-appropriate network controls and failover design.
