<?php

global $app;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/api/fechas', function (Request $request, Response $response) {

    $listFechas = [];
    $sql = "SELECT * FROM calendario";
    try {
        include("../src/entities/Fecha.php");
        $db = new DB();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $object = new Fecha();
                $object->id = (int)$row['id'];
                $object->mes = $row['mes'];
                $object->dia = $row['dia'];
                $object->rutas = $row['rutas'];
                $listFechas[] = $object;
            }
        }

        $db = null;
        return $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode($listFechas, JSON_THROW_ON_ERROR), JSON_UNESCAPED_UNICODE);
    } catch (Exception $ex) {
        echo '{"error": {"text": ' . $ex->getMessage() . '}}';
    }
    return $response->withStatus(500)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode($listFechas, JSON_THROW_ON_ERROR), JSON_UNESCAPED_UNICODE);
});

$app->post('/api/fechas', function (Request $request, Response $response) {
    $mes = $request->getParam('mes');
    $dia = $request->getParam('dia');
    $rutas = $request->getParam('rutas');

    $sql = "INSERT INTO calendario (mes, dia, rutas) VALUES (:mes, :dia, :rutas)";
    try {
        include("../src/entities/Fecha.php");
        $db = new DB();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':mes', $mes);
        $stmt->bindParam(':dia', $dia);
        $stmt->bindParam(':rutas', $rutas);
        $stmt->execute();
        $object = new Fecha();
        $object->id = $db->lastInsertId();
        $object->mes = $mes;
        $object->dia = $dia;
        $object->rutas = $rutas;
        $db = null;
        return $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode($object, JSON_THROW_ON_ERROR), JSON_UNESCAPED_UNICODE);
        echo '{"notice": {"text": "Fecha agregada"}';
    } catch (Exception $ex) {
        echo '{"error": {"text": ' . $ex->getMessage() . '}}';
    }
});

$app->get('/api/fechas/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');

    $sql = "SELECT * FROM calendario WHERE id = $id";
    try {
        include("../src/entities/Fecha.php");
        $db = new DB();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $object = new Fecha();
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $object->id = (int)$row['id'];
                $object->mes = $row['mes'];
                $object->dia = $row['dia'];
                $object->rutas = $row['rutas'];
            }
        }
        $db = null;
        return $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode($object, JSON_THROW_ON_ERROR), JSON_UNESCAPED_UNICODE);
    } catch (Exception $ex) {
        echo '{"error": {"text": ' . $ex->getMessage() . '}}';
    }
});

$app->put('/api/fechas/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $mes = $request->getParam('mes');
    $dia = $request->getParam('dia');
    $rutas = $request->getParam('rutas');

    $sql = "UPDATE calendario SET
                mes = :mes,
                dia = :dia,
                rutas = :rutas
            WHERE id = $id";
    try {
        include("../src/entities/Fecha.php");
        $db = new DB();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':mes', $mes);
        $stmt->bindParam(':dia', $dia);
        $stmt->bindParam(':rutas', $rutas);
        $stmt->execute();
        $object = new Fecha();
        $object->id = (int)$id;
        $object->mes = $mes;
        $object->dia = $dia;
        $object->rutas = $rutas;
        $db = null;
        echo '{"notice": {"text": "Fecha actualizada"}';
        return $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode($object, JSON_THROW_ON_ERROR), JSON_UNESCAPED_UNICODE);
    } catch (Exception $ex) {
        echo '{"error": {"text": ' . $ex->getMessage() . '}}';
    }
});
