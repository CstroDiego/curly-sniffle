<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../src/config/DB.php';

$app = new \Slim\App;

require '../src/routes/visitas.php';

try {
    $app->run();
} catch (Throwable $e) {
    echo '{"error": {"text": ' . $e->getMessage() . '}';
}
