<?php

require_once("conexion.php");

$nombres = $_POST['nombres'];

$apellidos = $_POST['apellidos'];

$email = $_POST['correo'];

$contrasena = $_POST['contrasena'];

// Preparar la consulta SQL 

$sql = "INSERT into usuarios (nombres, apellidos, correo, contrasena) values (?,?,?,?)"; 

 // Preparar la sentencia 

$stmt = $conn->prepare($sql); 

 // Verificar si la preparación fue exitosa 

if ($stmt === false) { die("Error en la preparación de la consulta: " . $conn->error); } 

 // Enlazar parámetros 

$stmt->bind_param("ssss",$nombres,$apellidos ,$email, $contrasena); 

 // Ejecutar la consulta 

$stmt->execute(); 

 // Obtener el resultado 

$result = $stmt->get_result(); 

 // Verificar si se encontraron resultados 

echo "Usuario registrado"."<br/>","Nombres: ". $nombres , "<br>" ,"Apellidos:" . $apellidos , "<br>" , "Correo: " . $email , "<br>" , "Contraseña: " . $contrasena; 

?>