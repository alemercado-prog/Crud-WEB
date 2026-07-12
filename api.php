<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

$conexion = new PDO("mysql:host=localhost;dbname=crud", "root", "");

$metodo = $_SERVER["REQUEST_METHOD"];

if ($metodo == "GET") {
    $stmt = $conexion->query("SELECT * FROM usuarios");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($usuarios);
} elseif ($metodo == "POST") {
    $datos = json_decode(file_get_contents("php://input"));
    $stmt = $conexion->prepare(
        "INSERT INTO usuarios (nombre,apellido,email) VALUES(?,?,?)",
    );
    $stmt->execute([$datos->nombre, $datos->apellido, $datos->email]);
    echo json_encode(["mensaje" => "Usuario creado"]);
} elseif ($metodo == "PUT") {
    $datos = json_decode(file_get_contents("php://input"));
    $stmt = $conexion->prepare(
        "UPDATE usuarios SET nombre=?, apellido=?, email=? WHERE id=?",
    );
    $stmt->execute([$datos->nombre, $datos->apellido, $datos->email]);
    echo json_encode(["mensaje" => "Usuario actualizado"]);
} elseif ($metodo == "DELETE") {
    $id = $_GET["id"];
    $stmt = $conexion->prepare("DELETE FROM usuarios WHERE id=?");
    $stmt->execute([$id]);
    echo json_encode(["mensaje" => "Usuario eliminado"]);
}

?>
