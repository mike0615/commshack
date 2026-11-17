#!/bin/bash
set -euo pipefail
cd -- "$(dirname -- "${BASH_SOURCE[0]}")"
engine=${CONTAINER_ENGINE:-podman}
mkdir -p releases/1.2.0
for major in "${@:-9 10}"; do
  for version in $major; do
    [[ "$version" == 9 || "$version" == 10 ]] || exit 2
    "$engine" run --rm --security-opt label=disable -v "$PWD:/src" "docker.io/rockylinux/rockylinux:$version" bash /src/packaging/build-in-container.sh "$version"
  done
done
(cd releases/1.2.0; sha256sum ./*.rpm ./*.tar.gz ./*.zip > SHA256SUMS)
