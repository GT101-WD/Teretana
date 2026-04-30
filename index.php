<?php 

require_once 'config.php';

// Provera da li su podaci poslati 
    if($_SERVER['REQUEST_METHOD'] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT admin_id, password FROM admins WHERE username = ?";
        
        $run = $conn->prepare($sql);
        $run->bind_param("s", $username);
        $run->execute();

        $results = $run->get_result();

        if($results->num_rows == 1) {
            $admin = $results->fetch_assoc();

            if(password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['admin_id'];

                $conn->close();
                header('location: admin_dashboard.php'); // Ako je password tačan, vodi me 
                                                        // na stranicu admin_dashboard.php
            } else {
                $_SESSION['error'] = "Netačan password!";
                
                $conn->close();
                header('location: index.php'); // Ako password nije tačan, vrati me 
                exit();                               // na početnu stranicu. 
            }

        } else {
            $_SESSION['error'] = "Netačan username!";
            
            $conn->close(); 
            header('location: index.php'); // Ako username nije tačan, vrati me 
            exit();                               // na početnu stranicu.
        }

  } 



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
</head>
<body>

    <?php
        if(isset($_SESSION['error'])) {
            echo $_SESSION['error'] . "<br>";
            unset($_SESSION['error']);
        }
    
    
    ?>



    <form action="" method="POST"> 
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="login">
    </form>


</body>
</html>