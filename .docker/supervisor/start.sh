#!/bin/bash

set -e

FILE="/var/www/symfony/vendor/autoload.php"

cmd="$@"

>&2 echo "!!!!!!!! Check autoload.php for available !!!!!!!!"

until [[ -f "$FILE" ]]; do
  >&2 echo "Autoload.php is unavailable - sleeping"
  sleep 1
done

>&2 echo "Autoload.php is up - executing command"

exec $cmd

