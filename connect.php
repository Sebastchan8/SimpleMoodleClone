<?php
    function connect(){
        $servidor="localhost";
        $usuario="root";
        $clave="";
        $baseDatos="utatest";
        return new PDO('mysql:host='.$servidor.
                        ';dbname='.$baseDatos,
                        $usuario,
                        $clave);
    }
?>