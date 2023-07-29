# Comandos de criação do projeto
```shell
mkdir src
touch src/index.php

composer require slim/slim
composer require illuminate/database
composer require slim/http
composer require slim/psr7
composer require httpsoft/http-message httpsoft/http-server-request
composer require nyholm/psr7 nyholm/psr7-server
composer require guzzlehttp/psr7
composer require laminas/laminas-diactoros

```
# adicionar script start no composer.json
```composer.json
{
    "scripts": {
        "start": "php -S localhost:8000 -t src"
    },
    "require": {
        ... continuação do arquivo
}
```

# Colocar o código abaixo no src/index.php
```php
<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add routes
$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write('<a href="/hello/world">Try /hello/world</a>');
    return $response;
});

$app->get('/hello/{name}', function (Request $request, Response $response, $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");
    return $response;
});

$app->run();
```

# depois de configurado, rodar o comando
```shell
composer start
```

# Comando de start do projeto já criado
```shell
git clone https://github.com/torneseumprogramador/fluxo-de-caixa-php.git
cd fluxo-de-caixa-php
composer install
composer start
```