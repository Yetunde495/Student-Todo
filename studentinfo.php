<?php

//including my config file
require_once "includes/config.php";

//defining my variables and initializing an empty value to it

$firstname = $lastname = $email = $phonenumber = $username = $password = $confirm_password = "";

//processing data that would be submitted

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //validate firstname
    if(empty(trim($_POST['firstname']))) {
        $firstname_err = "Please, enter your first name";
    } elseif (!preg_match('/^[a-zA-Z_]+$/', trim($_POST["firstname"]))) {
        //err code...
        $firstname_err = "Your name must contain only letters";
    } else {
        //preparing a select statement
        $sql = "SELECT id FROM students WHERE firstname = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            //binding variable to the prepared statement

            mysqli_stmt_bind_param($stmt, "s", $param_firstname);
            //assign a value to the param firstname
            $param_firstname = trim($_POST["firstname"]);

            //execute the prepared statement

            if (mysqli_stmt_execute($stmt)) {
                //store result
                mysqli_stmt_store_result($stmt);
                $firstname = trim($_POST['firstname']);
            
            } else{echo "something is wrong";}
            
        } else {
            mysqli_stmt_close($stmt);
        }
    }

    //validate last name
    if(empty(trim($_POST['lastname']))) {
        $lastname_err = "Please, enter your last name";
    } elseif (!preg_match('/^[a-zA-Z_]+$/', trim($_POST["lastname"]))) {
        //err code...
        $lastname_err = "Your last name must contain only letters";
    } else {
        //preparing a select statement
        $sql = "SELECT id FROM students WHERE lastname = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            //binding variable to the prepared statement

            mysqli_stmt_bind_param($stmt, "s", $param_lastname);
            //assign a value to the param lastname
            $param_lastname = trim($_POST["lastname"]);

            //execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                //store result 
                mysqli_stmt_store_result($stmt);
                $lastname = trim($_POST['lastname']);
            } else {
                echo "something is wrong";
            }
        } else {
            mysqli_stmt_close($stmt);
        }
    }
    //validate email
$email = mysqli_real_escape_string($conn, $_POST['email']);
    if(empty($email)) {
        $email_err = "Please, enter your email";
     } elseif 
    //(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["email"])) 
    (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        //err code...
        $email_err = "This email address is invalid";
    } else {

        
        //preparing a select statement
        $sql = "SELECT id FROM students WHERE email = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            //binding variable to the prepared statement

            mysqli_stmt_bind_param($stmt, "s", $email);
            //assign a value to the param email
            $param_email = trim($_POST["email"]);

            //execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                //store result 
                mysqli_stmt_store_result($stmt);
                $email = trim($_POST['email']);
            } else {
                echo "something is wrong";
            }
        } else {
            mysqli_stmt_close($stmt);
        }
    }
    //validate phone number 
    $phonenumber = mysqli_real_escape_string($conn, $_POST['phonenumber']);
    if(empty($phonenumber)) {
        $phonenumber_err = "Please, enter your phone number";
    } elseif (!preg_match('/^[0-9_]+$/', $phonenumber)) {
        //err code...
        $phonenumber_err = "Your phone number can only contain numbers";
    } elseif(strlen(trim($_POST["phonenumber"])) < 11) {
        $phonenumber_err = "Your phone number must be 11 characters";
    } else {
        //preparing a select statement
        $sql = "SELECT id FROM students WHERE phonenumber = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            //binding variable to the prepared statement

            mysqli_stmt_bind_param($stmt, "s", $param_phonenumber);
            //assign a value to the param phone number
            $param_phonenumber = trim($_POST["phonenumber"]);

            //execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                //store result 
                mysqli_stmt_store_result($stmt);
                $phonenumber = trim($_POST['phonenumber']);
            } else {
                echo "something is wrong";
            }
        } else {
            mysqli_stmt_close($stmt);
        }
    }
    //validate username
    if(empty(trim($_POST['username']))) {
        $username_err = "Please, enter your preferred username";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))) {
        //err code
        $username_err = "Username can only be letters, numbers and underscores";
    } else {
        //preparing a select statement
        $sql = "SELECT id FROM students WHERE username = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            //binding variable to the prepared statement

            mysqli_stmt_bind_param($stmt, "s", $param_username);
            //assign a value to the param message
            $param_username = trim($_POST["username"]);

            //execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                //store result 
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt)) {
                    $username_err = 'This username has been taken already. Please, enter another username';
                } else {
                    $username = trim($_POST['username']);  
                }
                
            } else {
                echo "something is wrong";
            }
        } else {
            mysqli_stmt_close($stmt);
        }

        //validate password
        if (empty(trim($_POST["password"]))) {
            $password_err = "please enter a password";
        }elseif(strlen(trim($_POST["password"])) < 6) {
            $password_err = "password must be at least 6 characters";
        } else{
            $password = trim($_POST["password"]);
        }
        //validate confirm password
        if (empty(trim($_POST["confirm_password"]))) {
            $confirm_password_err = "Please confirm password";
        }else {
            $confirm_password = trim($_POST["confirm_password"]);
            if (empty($password_err) && ($password != $confirm_password)) {
                $confirm_password_err = "Password did not match";
            }
        }
    }

    //checking other forms of errorrs
    if (empty($firstname_err) && empty($lastname_err) && empty($email_err) && 
    empty($phonenumber_err) && empty($username_err) && empty($password_err)) {
        //prepare an insert statement
        $sql = "INSERT INTO students (firstname, lastname, email, phonenumber, username, password) VALUES (?, ?, ?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssssss", $param_firstname, $param_lastname, $param_email, $param_phonenumber, $param_username, $param_password);

            //set the parameters
            $param_firstname = $firstname;
            $param_lastname = $lastname;
            $param_email = $email;
            $param_phonenumber = $phonenumber;
            $param_username = $username;
            $param_password = $password;

            //execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                //redirecting to the login page 
                header("location: studentinfo.php");

            } else {echo "Something is wrong, please check your login details 
                again";}
            //close statement
            mysqli_stmt_close($stmt);
            

        }
        
    }
}

?>

<!DOCTYPE html>
<html lang="en" class="index">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="index">

<!-- <section class="navbar-section">
    <nav class="navbar navbar-expand-lg navbar-dark">
     <div class="container-fluid">
    <a class="navbar-brand" href="#">
        <img class="navbar-logo" src="css/images/images-logo-dark.png" alt="">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarSupportedContent">

        <ul class="navbar-nav ms-auto">
            <li class="nav-item"> 
                <a class="nav-link active" href=""><span class="btn-text" style="color: #f5fffa;">Home</span></a> </li>
                <li class="nav-item"> 
                <a class="nav-link active" href=""><span class="btn-text">About</span></a> </li>
        </ul>



      
      
    </div>
    </div>
    </nav>
    </section> -->
  
  





 <section class="form-section s-pad">
    <div class="container form-container">
    <div class="f shadow-lg card">  
           

           <div class="text-center">
            <h1>Sign up</h1>
            <p>Fill in the form to register your details </p>

            </div>

            <div>
                   <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="form-r">
               
           <div class="mb-3">
                <label  class="form-label">First Name</label> 
                <input type="text" class="form-control <?php echo (!empty($firstname_err)) ? 'is-invalid' : ''; ?>"
                value="<?php echo $firstname; ?>" name="firstname" autocomplete="off"> 
                <span class="invalid-feedback"><?php echo $firstname_err; ?></span>
            </div>

            <div class="mb-3">
                <label  class="form-label">Last Name</label> 
                <input type="text" class="form-control <?php echo (!empty($lastname_err)) ? 'is-invalid' : ''; ?>"
                value="<?php echo $lastname; ?>" name="lastname" autocomplete="off"> 
                <span class="invalid-feedback"><?php echo $lastname_err; ?></span>
            </div>

            <div class="mb-3">
                <label  class="form-label">Email</label> 
                <input type="text" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>"
                value="<?php echo $email; ?>" name="email" autocomplete="off"> 
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>

            <div class="mb-3">
                <label  class="form-label">Phone Number</label> 
                <input type="contact" class="form-control <?php echo (!empty($phonenumber_err)) ? 'is-invalid' : ''; ?>"
                value="<?php echo $phonenumber; ?>" name="phonenumber" autocomplete="off"> 
                <span class="invalid-feedback"><?php echo $phonenumber_err; ?></span>
            </div>

            <div class="mb-3">
                <label  class="form-label">Username</label> 
                <input type="text" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>"
                value="<?php echo $username; ?>" name="username" autocomplete="off"> 
                <span class="invalid-feedback"><?php echo $username_err; ?></span>

            </div>

            <div class="mb-3">
                <label  class="form-label">Password</label> 
                <input type="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>"
                value="<?php echo $password; ?>" name="password" autocomplete="off"> 
                <span class="invalid-feedback"><?php echo $password_err; ?></span>

            </div>

            <div class="mb-3">
                <label  class="form-label">Confirm Password</label> 
                <input type="password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>"
                value="<?php echo $confirm_password; ?>" name="confirm_password"> 
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-warning btn-lg" value="submit"> Sign up</button>
               
            </div>
            <p>Have an account already? <a href="signin.php">Sign in </a> </p>
            </form>
        </div>
               
       
    </div>     
</div>
</section>
   
 
   
   </div><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>
</html>