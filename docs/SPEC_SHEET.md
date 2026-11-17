# CommShack 1.2.0 specification

| Item | Specification |
|---|---|
| Product | WordPress communications portal integration; Cyber Chief toolkit |
| Platform | Existing WordPress 6.4+, PHP 8.0+, database and HTTPS |
| Native packaging | Rocky Linux 9/10 noarch integration RPMs and offline installers |
| Other packaging | WordPress plugin ZIP, child-theme ZIP, source archive |
| Theme | CommShack Child, requires separately supplied Twenty Twenty-Four |
| Functions | Authenticated cards, chat/mail embeds, files adapter, Help/About |
| State | Site tools.json/configuration; no new database schema or password storage |
| Dependencies | Existing WordPress services; optional chat/mail/files/SSO deployments |
| Native prerequisites | Standard Rocky bash/coreutils; no network fetch at install |
| Network | Existing WordPress HTTPS and configured service HTTPS endpoints |
| License | MIT, consistent with original project declaration |

No complete WordPress, database, chat, mail, LDAP or HA stack is bundled. No live
service is changed by package installation. Automated checks cover plugin API
boundaries and offline package lifecycle. Full WordPress/browser/SSO, backend
service integration and multi-site failover require site qualification. Unsigned RPMs.
