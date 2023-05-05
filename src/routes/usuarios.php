<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/api/usuarios', function(Request $request, Response $response) {

    $listUsuarios = [];
    $sql = "SELECT * FROM usuario";
    try {
        include("../src/entities/Usuario.php");
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $object = new Usuario();
                $object->id = (int)$row['id'];
                $object->nombre = $row['nombre'];
                $object->email = $row['email'];
                $listUsuarios[] = $object;
            }
        }

        $db = null;
        return $response->withStatus(200)
        ->withHeader('Content-Type', 'application/json')
        ->write(json_encode($listUsuarios), JSON_UNESCAPED_UNICODE);
    } catch(Exception $ex){
        echo '{"error": {"text": '.$ex->getMessage().'}';
    }
});

?>