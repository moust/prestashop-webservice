moust/prestashop-webservice
===========================

PHP wrapper for Prestashop web service

## Installation

1. Clone this repository in your new working directory.

2. Download the [`composer.phar`](https://getcomposer.org/composer.phar) executable or use the installer.

    ``` sh
    curl -s https://getcomposer.org/installer | php
    ```

3. Run Composer: `php composer.phar update`

## How to use it

```php
// initialize WebService
$webservice = new Webservice('http://mystore.com/', 'your-prestashop-api-key', false);

// initialize CacheProvider (optional)
$cache = new Doctrine\Common\Cache\PhpFileCache(__DIR__.'/cache');
$webservice->setCacheProvider($cache);
$webservice->setTtl(60);

// initialize Logger with Monolog (optional)
$logger = new Monolog\Logger('prestashop');
$handler = new Monolog\Handler\StreamHandler(__DIR__.'/logs/prestashop.log');
$logger->pushHandler($handler);
$webservice->setLogger($logger);

$products = $webservice->getProducts();

echo '<ul>';
foreach ($products as $product) {
    echo '<li>' . $product->id . ' - ' . $product->name[1] . '</li>';
}
echo '</ul>';
```
