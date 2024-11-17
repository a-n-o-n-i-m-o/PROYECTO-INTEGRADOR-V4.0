<?php

require_once "../config/config.php";

$accion = $_POST['accion'] ?? $_GET['accion'];

/* $tabla  = "empleados";
$tabla2 = "sesiones";
$tabla3 = "roles"; */

$tabla = "distritos";

$datos = array(
    "distrito_id"            => $_POST['distrito_id'] ?? null,
    "nombre"         => $_POST['nombre'] ?? null,
    "provincia_id"               => $_POST['provincia_id'] ?? null,
);

//verificando a cual accion tomar
switch ($accion) {

    case "buscar_provincia":
        buscar_provincia($pdo, $tabla, $datos);
        break;

}

function buscar_provincia ($pdo, $tabla, $datos){
    
    $sql = $pdo->prepare("SELECT * FROM distritos where provincia_id = :provincia_id");

    $sql->bindParam(':provincia_id', $datos['provincia_id']);
    $sql->execute();
    $distritos = $sql->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($distritos);
    return;
}


?>