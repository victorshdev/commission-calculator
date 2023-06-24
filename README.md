## Preinstall
```
change EXCHANGE_API_ACCESS_TOKEN in .env with your access_token 
```

## Install
```
composer install
```

## Run code

```
php app.php index.txt
```

## Run tests

```
php ./vendor/bin/phpunit --verbose tests
```

Do not hesitate to ry with another ExchangeRate Provider url;)
```
EXCHANGE_PROVIDER_URL="https://openexchangerates.org/api/latest.json?app_id=%YOUR_APP_ID%&show_alternative=true";
```