Name: commshack
Version: 1.2.0
Release: 1%{?dist}
Summary: CommShack WordPress portal integration
License: MIT
URL: https://github.com/mike0615/commshack
Source0: commshack-1.2.0.tar.gz
BuildArch: noarch
Requires: bash
Requires: coreutils
%description
CommShack plugin, child theme, embedded help/about and deployment documentation.
Requires an existing, separately managed WordPress/PHP/database installation.
No network services or live WordPress settings are activated by this package.
%prep
%setup -q
%build
%install
install -d %{buildroot}/usr/share/commshack
cp -a plugins themes configs scripts docs README.md HELP.md RELEASE_NOTES.md LICENSE %{buildroot}/usr/share/commshack/
find %{buildroot}/usr/share/commshack -type d -exec chmod 0755 {} +
find %{buildroot}/usr/share/commshack -type f -exec chmod 0644 {} +
install -D -m 0755 packaging/commshack %{buildroot}/usr/bin/commshack
%files
/usr/share/commshack/
/usr/bin/commshack
%changelog
* Tue Nov 17 2026 Mike Anderson <mike@andersoncomputer.us> - 1.2.0-1
- Rebrand portal and gate content on WordPress login; safe explicit integration install
- Embedded Help/About, compatibility aliases, native offline integration RPMs
