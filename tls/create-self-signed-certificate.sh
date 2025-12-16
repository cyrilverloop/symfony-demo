#!/bin/sh

SCRIPT_PATH=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd );
cd $SCRIPT_PATH;

docker compose run --rm tls genrsa -aes256 -passout pass:gsahdg -out server.pass.key 4096
docker compose run --rm tls rsa -passin pass:gsahdg -in server.pass.key -out server.key
rm server.pass.key
docker compose run --rm tls req -new -key server.key -out server.csr
docker compose run --rm tls x509 -req -sha256 -days 365 -in server.csr -signkey server.key -out server.crt
rm server.csr
