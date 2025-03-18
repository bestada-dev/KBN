#!/bin/bash

# Run composer install if vendor directory does not exist
if [ ! -d "/var/www/html/vendor" ]; then
  composer install
fi

# Check if APP_ENV is production
if [ "$APP_ENV" == "production" ]; then
  # Check if the local-db-data directory exists
  if [ ! -d "./local-db-data" ]; then
    echo "Directory local-db-data does not exist. Creating it now."
    mkdir -p ./local-db-data
  else
    echo "Directory local-db-data already exists."
  fi
else
  echo "APP_ENV is not set to production. Skipping directory check and creation."
fi

# Continue running the original CMD from the Dockerfile
exec "$@"
