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

    $check = $conexion->prepare("SELECT id FROM usuarios WHERE nombre=?");
    $check->execute([$datos->nombre]);

    if ($check->rowCount() > 0) {
        echo json_encode(["error" => "Usuario ya existe"]);
        exit;
    }

    $stmt = $conexion->prepare(
        "INSERT INTO usuarios (nombre,apellido,email,telefono,fecha_nacimiento,nacionalidad) VALUES(?,?,?,?,?,?)",
    );
    $stmt->execute([
        $datos->nombre,
        $datos->apellido,
        $datos->email,
        $datos->telefono,
        $datos->fecha_nacimiento,
        $datos->nacionalidad,
    ]);
    echo json_encode(["mensaje" => "Usuario creado"]);

} elseif ($metodo == "PUT") {
    $datos = json_decode(file_get_contents("php://input"));
    $stmt = $conexion->prepare(
        "UPDATE usuarios SET nombre=?, apellido=?, email=?, telefono=?, fecha_nacimiento=?, nacionalidad=? WHERE id=?",
    );
    $stmt->execute([
        $datos->nombre,
        $datos->apellido,
        $datos->email,
        $datos->telefono,
        $datos->fecha_nacimiento,
        $datos->nacionalidad,
    ]);
    echo json_encode(["mensaje" => "Usuario actualizado"]);
} elseif ($metodo == "DELETE") {
    $id = $_GET["id"];
    $stmt = $conexion->prepare("DELETE FROM usuarios WHERE id=?");
    $stmt->execute([$id]);
    echo json_encode(["mensaje" => "Usuario eliminado"]);
}

?>
