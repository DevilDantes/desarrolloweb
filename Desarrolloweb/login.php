<?php

require_once("conexion.php");

$email = $_POST['correo'];

$contrasena = $_POST['contrasena'];

// Preparar la consulta SQL 

$sql = "SELECT * FROM usuarios WHERE correo = ? AND contrasena = ?"; 

 // Preparar la sentencia 

$stmt = $conn->prepare($sql); 

 // Verificar si la preparación fue exitosa 

if ($stmt === false) { die("Error en la preparación de la consulta: " . $conn->error); } 

 // Enlazar parámetros 

$stmt->bind_param("ss", $email, $contrasena); 

 // Ejecutar la consulta 

$stmt->execute(); 

 // Obtener el resultado 

$result = $stmt->get_result(); 

 // Verificar si se encontraron resultados 

if ($result->num_rows > 0) { 

// Mostrar los resultados 

 while ($row = $result->fetch_assoc()) { 

 echo "ID: " . $row["id"] . "\n" . " - Nombres: " . $row["nombres"] ."\n". " - Correo: " . $row["correo"] . "<br>";

 }

 } else { 

 echo "No se encontraron usuarios con esas credenciales."; 

}

?>