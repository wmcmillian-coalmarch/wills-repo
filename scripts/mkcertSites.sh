#!/usr/bin/env bash

trim() {
    local var="$*"
    # remove leading whitespace characters
    var="${var#"${var%%[![:space:]]*}"}"
    # remove trailing whitespace characters
    var="${var%"${var##*[![:space:]]}"}"
    printf '%s' "$var"
}

ARGS='';

TEST='';
NL=$'\n'
for DIR in ~/Sites/*; do
  if [[ -d $DIR ]]; then
    dirname=${DIR##*/};
    pattern="^[A-Za-z0-9_\.-]*$"
    if [[ "$dirname" =~ $pattern ]]; then
      ARGS="${ARGS} ${dirname}.test"
    fi
  fi
done

#echo "mkcert -cert-file ~/Sites/test-cert.pem -key-file ~/Sites/test-cert-key.pem $ARGS"
mkcert -cert-file ~/Sites/test-cert.pem -key-file ~/Sites/test-cert-key.pem $ARGS