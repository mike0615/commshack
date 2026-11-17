# CommShack help

Activate CommShack Shortcodes in an existing WordPress site after disabling the
old LiveWire plugin. Create pages containing [commshack_dashboard],
[commshack_chat], [commshack_mail] and [commshack_files] as needed. All require a
WordPress login. [commshack_help] and [commshack_about] provide local user help;
administrators can use Tools > CommShack Help & About.

Edit the plugin tools.json to supply HTTPS tool destinations. Configure
COMMSHACK_XMPP_URL / COMMSHACK_MAIL_URL in protected WordPress configuration,
or use commshack_xmpp_url / commshack_mail_url filters. Existing LIVEWIRE_*
constants and livewire_* filters/shortcodes remain compatible. New filters apply
after legacy filters. The file adapter requires a separately installed plugin
providing shared_files; its own authorization controls remain essential.

Embeds require HTTPS URLs without user/password fields. The destination must
allow framing and authenticate its own users. CommShack saves no service passwords.
Missing files integration shows an explanation. Empty/malformed tool definitions
fail closed. The release includes no CDN/font download requirement of its own.
External WordPress themes/plugins and embedded services need separate offline review.
