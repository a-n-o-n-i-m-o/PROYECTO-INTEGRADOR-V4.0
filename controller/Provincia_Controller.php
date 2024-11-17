<?php

require_once "../config/config.php";

$accion = $_POST['accion'] ?? $_GET['accion'];

/* $tabla  = "empleados";
$tabla2 = "sesiones";
$tabla3 = "roles"; */

$tabla = "provincias";

$datos = array(
    "provincia_id"            => $_POST['provincia_id'] ?? null,
    "nombre"         => $_POST['nombre'] ?? null,
    "departamento_id"               => $_POST['departamento_id'] ?? null,
);

//verificando a cual accion tomar
switch ($accion) {

    case "buscar_departamento":
        buscar_departamento($pdo, $tabla, $datos);
        break;

}

function buscar_departamento ($pdo, $tabla, $datos){
    
    $sql = $pdo->prepare("SELECT * FROM provincias where departamento_id = :departamento_id");

    $sql->bindParam(':departamento_id', $datos['departamento_id']);
    $sql->execute();
    $provincias = $sql->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($provincias);
    return;
}


?>