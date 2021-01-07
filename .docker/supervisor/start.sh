#!/bin/bash

set -e

cmd="$@"

>&2 echo "!!!!!!!! Check autoload.php for available !!!!!!!!"

until [ ! "$(docker ps -a | grep Exited)" ]; do
  >&2 echo "Autoload.php is unavailable - sleeping"
  sleep 1
done

>&2 echo "Autoload.php is up - executing command"

exec $cmd

