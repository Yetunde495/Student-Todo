<?php
// Initializing a session
session_start();

//check if a user is logged in, redirect the user if user is logged in

if (isset($_SESSION["loggedin"]) && $_SESSION["LOGGEDIN"] === true) {

    header("Location: todo.php");
    exit;
}

require_once "includes/config.php"

//define variables and initialize with a value
$username = $password = "";
$username_err = $password_err = $login_err = "";

//process data received from the login form
if ($_SERVER["REQUEST_METHOD"] == POST) {

    //check for empty username 
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please, enter your username";
    } else {
        //if username isn't empty, it receives the value and stores it in the username var
        $username = trim($_POST["username"]);
    }
    //check for empty password
    if (empty(trim($_POST["password"]))) {
        $password_err = "please, enter your password";
    } else {
        //if password isn't empty, it receives the value and stores it in the password var
        $password = trim($_POST["password"]);
    }

    //validate the credentials 

        // check if usrname and password isn't empty,
    if(empty($username_err) && empty($password_err)) {
       //prepare a select statement
       
       $sql = "SELECT id, username, password FROM students WHERE username =?";
       if ($stmt = mysqli_prepare($conn, $sql)) {
           //bind variables to the prepared statement as param 
           mysqli_stmt_bind_param($stmt, "s", $param_username);

           //set the parameter
           $param_username = $username;

           //attempt to execute the prepared statement
           if(mysqli_stmt_execute($stmt)) {
               //store result
               mysqli_stmt_store_result($stmt);

               //check to see if username exists, if yes then we verify password
               if (mysqli_stmt_num_rows($stmt) == 1) {
                   //bind result variable
                   mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password) {
                       if (mysqli_stmt_fetch($stmt)) {
                           //check to see if password is correct
                           if(password_verify($password, $hashed_password)) {
                            /if password is correct, start a session
                            session_start();
 
                            //store
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $SESSION["username"] = $username;
 
                            //redirect the user 
                            header('Location: todo.php');
                           } else {
                               //password is not valid
                               $login_err = "invalid username or password";
                           }
                           
                       }
                   }
               } else {
                   $login_err = "Invalid username or password";
               }
           } else {
               echo "Something is wrong";
           }
       } //close statement
       mysqli_stmt_close($stmt);
    }
} ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <section class="s-pad">
        <div class="container justify-content-center">
            <div class="text-center">
                <h2>Login</h2>
                <p>Lorrem ipsum dolor sit amet</p>
            </div>

            <?php 
            if (empty($login_err)) {
                echo '<div class="alert alert-danger">' .$login_err
            }

        </div>
    </section>
</body>
</html>
