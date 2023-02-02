<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegistroController {}

    $conexion = mysqli_connect("localhost","root","12345", "prueba" );//correcto
    if (!$conexion){
        echo 'Error al conectar a la base de datos';
    }
    else {
        echo 'Conectado a la base de datos';
    }

    include 'Usuario.php';
    //Recibir los datos y almacenarlos en variables
    $nombre = $_POST["nombre"];
    $apellidos = $_POST["apellidos"];
    $correo = $_POST["correo"];
    $usuario = $_POST["usuario"];
    $contrasenya = $_POST["contraseña"];
    $genero = $_POST["genero"];
    $fechaNacimiento = $_POST["fechaNacimiento"];



    $verificar_usuario = mysqli_query($conexion, "SELECT * FROM usuarios WHERE usuario = '$usuario'");//correcto


    if (mysqli_num_rows($verificar_usuario) > 0){
        echo 'El usuario ya esta registrado';
        exit;
    }else{

        $insertar =mysqli_query($conexion, "INSERT INTO usuarios(nombre, apellidos, correo, usuario, contraseña, genero , fechaNacimiento) VALUES ('$nombre', '$apellidos', '$correo', '$usuario', '$contrasenya', '$genero' ,'$fechaNacimiento' ");


        if (!$insertar){
            echo 'Error al registrarse';

        }else {
            echo'Usuario registrado exitosamente';
        }

    }




//Cerrar conexion
mysqli_close($conexion);