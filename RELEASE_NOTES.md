# CommShack 1.2.0 — November 17, 2026

First release under the CommShack name, formerly LiveWire Portal.

- Renames plugin/theme/repository and retains existing shortcode/endpoint aliases.
- Adds login boundaries, typed tool schema validation, HTTPS-only credential-free
  embeds and escaped rendering; safely explains missing file integration.
- Adds embedded user Help/About and administrator Tools page.
- Replaces automatic live config deployment/restarts with explicit, no-overwrite
  installation into a selected existing WordPress directory.
- Hardens inactive Apache and proxy templates; corrects VRRP operational guidance.
- Ships Rocky 9/10 noarch integration RPMs, offline installers, WordPress ZIPs,
  source archives, checksums and installation/operations/ICD/specification docs.

This is an integration release, not a complete WordPress/mail/chat/HA appliance.
WordPress, PHP, database, parent theme and backend services must already be
provided. Native offline checks and plugin harness tests do not establish real
SSO, service embedding, production WordPress behavior or failover qualification.
RPMs are unsigned. Live WordPress files are activated/upgraded explicitly and
survive removal of the staged integration RPM. Back up before migration.
