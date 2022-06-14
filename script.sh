composer install
yarn install
php bin/console d:d:d -f
php bin/console d:d:c
php bin/console d:m:m --no-interaction
php bin/console d:f:l --no-interaction

if  [[ $1 = "--serve" ]]; then
    symfony serve -d
    if  [[ $2 = "--dev" ]]; then
        yarn dev-server
    else
        yarn dev
    fi
fi
