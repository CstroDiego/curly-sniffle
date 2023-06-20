<?php

global $app;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/api/visitas', function (Request $request, Response $response) {

    $listVisitas = [];
    $sql = "SELECT * FROM visita";
    try {
        include("../src/entities/Visita.php");
        $db = new DB();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $object = new Visita();
                $object->id = (int)$row['id'];
                $object->lugar = $row['lugar'];
                $object->motivo = $row['motivo'];
                $object->responsable = $row['responsable'];
                $object->latitud = $row['latitud'];
                $object->longitud = $row['longitud'];
                $listVisitas[] = $object;
            }
        }

        $db = null;
        return $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode($listVisitas, JSON_THROW_ON_ERROR), JSON_UNESCAPED_UNICODE);
    } catch (Exception $ex) {
        echo '{"error": {"text": ' . $ex->getMessage() . '}}';
    }
    return $response->withStatus(500)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode($listVisitas, JSON_THROW_ON_ERROR), JSON_UNESCAPED_UNICODE);
});

$app->post('/api/visitas', function (Request $request, Response $response) {
    $lugar = $request->getParam('lugar');
    $motivo = $request->getParam('motivo');
    $responsable = $request->getParam('responsable');
    $latitud = $request->getParam('latitud');
    $longitud = $request->getParam('longitud');

    $sql = "INSERT INTO visita (lugar, motivo, responsable, latitud, longitud) VALUES (:lugar, :motivo, :responsable, :latitud, :longitud)";
    try {
        include("../src/entities/Visita.php");
        $db = new DB();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':lugar', $lugar);
        $stmt->bindParam(':motivo', $motivo);
        $stmt->bindParam(':responsable', $responsable);
        $stmt->bindParam(':latitud', $latitud);
        $stmt->bindParam(':longitud', $longitud);
        $stmt->execute();
        $object = new Visita();
        $object->id = $db->lastInsertId();
        $object->lugar = $lugar;
        $object->motivo = $motivo;
        $object->responsable = $responsable;
        $object->latitud = $latitud;
        $object->longitud = $longitud;
        $db = null;
        return $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode($object, JSON_THROW_ON_ERROR), JSON_UNESCAPED_UNICODE);
    } catch (Exception $ex) {
        echo '{"error": {"text": ' . $ex->getMessage() . '}}';
    }
    return $response->withStatus(500)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode($object, JSON_THROW_ON_ERROR), JSON_UNESCAPED_UNICODE);
});

$app->get('/api/visitas/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $sql = "SELECT * FROM visita WHERE id = :id";

    try {
        include("../src/entities/Visita.php");
        $db = new DB();
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return $response->withStatus(404)
                ->withHeader('Content-Type', 'application/json')
                ->write(json_encode(['error' => 'Visita no encontrada'], JSON_THROW_ON_ERROR), JSON_UNESCAPED_UNICODE);
        }

        $object = new Visita();
        $object->id = (int)$row['id'];
        $object->lugar = $row['lugar'];
        $object->motivo = $row['motivo'];
        $object->responsable = $row['responsable'];
        $object->latitud = $row['latitud'];
        $object->longitud = $row['longitud'];

        $db = null;

        return $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode($object, JSON_THROW_ON_ERROR), JSON_UNESCAPED_UNICODE);
    } catch (PDOException $ex) {
        return $response->withStatus(500)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode(['error' => $ex->getMessage()], JSON_THROW_ON_ERROR), JSON_UNESCAPED_UNICODE);
    } catch (Exception $ex) {
        return $response->withStatus(500)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode(['error' => 'Error interno del servidor'], JSON_THROW_ON_ERROR), JSON_UNESCAPED_UNICODE);
    }
});


