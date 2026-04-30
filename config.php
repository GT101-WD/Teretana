<?php 

    session_start();

// Povezivanje sa bazom

    $servername = "localhost";
    $db_username = "root";
    $db_password = "";
    $database_name = "teretana";

    $conn = mysqli_connect($servername, $db_username, $db_password, $database_name);

// Provera da li je konekcija uspešna

    if(!$conn) {
        die("Konekcija Ne uspešna!");
    }
