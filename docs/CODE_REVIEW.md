# CommShack review and validation

| Finding | Correction |
|---|---|
| Default deploy overwrote Apache/Prosody and restarted services | Explicit WordPress target; file staging only, no overwrite |
| Git pull failure could be ignored before live apply | Installer has no Git/network dependency |
| Embeds/files/cards lacked WordPress login boundary | All portal shortcodes enforce is_user_logged_in |
| tools.json accepted arbitrary shapes | Bounded object/string/HTTPS validation and escaping |
| Plugin/theme direct access | ABSPATH guards |
| Apache allowed indexes and ambiguous authorization | Indexes disabled, permissive grant removed, explicit auth merging |
| Proxy examples used plaintext backend transport | Verified backend TLS and site CA/hostname configuration |
| VRRP cross-subnet and password guidance overstated | Routing/8-character PASS limits documented |
| No release packaging/help/about | Inactive native packages, ZIPs, embedded help and version, current manuals |

PHP harness tests exercise anonymous denial, aliases, URL handling, malformed JSON,
escaping, missing file provider and help capability checks. Shell tests cover target
validation, spaces, first installation and overwrite refusal. Native Rocky 9/10
builds run these checks and fresh network-disabled RPM install/integration/removal
tests. The PHP harness uses WordPress API stubs; a full WordPress installation,
real SSO, third-party plugins, chat/mail, SELinux policy and HA failover are not
qualified by those checks. Integration version and limitations are explicit.
