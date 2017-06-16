<!DOCTYPE html>

<!-- Web Systems and Web Computing Project Part 3
Filename: registration.php
Description: Registration page for new users with client and server side validation

For course: CAS 6WW3
By: Omer Waseem (graduate student) -->

<html>

   <head>
      <title>The Hidden Path</title> <!-- Webpage title -->

      <!-- Addon 1: generic meta tags, homescreen tags and twitter cards -->
      <?php include 'meta.inc' ?>

      <!-- Addon 1: OpenGraph meta-data -->
      <meta property="og:title" content="User Registration" />
      <meta property="og:type" content="website" />
      <meta property="og:url" content="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" />
      <meta property="og:description" content="Signup for additional functionality" />
      <meta property="og:site_name" content="The Hidden Path" />

      <!-- CSS stylesheet link -->
      <link rel="stylesheet" href="css/style_main.css" type="text/css"> 

      <!-- Linked script files -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
      <script type="text/javascript" src="scripts/validation.js"></script>
   </head>

   <body>
      <nav>
         <!-- navication menu stored in a seperate file for re-use -->
         <?php include 'nav.inc' ?>
      </nav>

      <header><h1>The Hidden Path</h1></header>

      <main>
         <h3>User Registration</h3>
         <?php // server side validation with form posting to self

            // validation for name: should only contains letters or numbers
            function validName($name) {
               return preg_match('/^[A-z]+$/', $name); 
            }

            // validation for login: should only contains letters or numbers
            function validLogin($login) {
               return preg_match('/^[A-z0-9]+$/', $login); 
            }

            // validation for password: should contains at least 8 characters
            function validPass ($pass) {
               return preg_match('/^.{8,}$/', $pass); 
            }

            // validation for proper email format
            function validEmail ($email) {
               return preg_match('/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,})+$/', $email); 
            }

            // validation for proper birth date
            function validBDate ($bdate) {
               return preg_match('/^(18|19|20)\d\d[-\/](0[1-9]|1[012])[-\/](0[1-9]|[12][0-9]|3[01])$/', $bdate); 
            }

            // validPost() checks if post parameters are set and calls the respective validate functions
            // on each parameter to check for errors. 
            function validatePost(&$errors) {
               
               $flag = true; // boolean variable where false indicates errors

               $errors .= "Please correct the following errors:";

               // the following if statements check each post input and populate the error variable
               // first name is required and should only contain letters
               if ($_POST['firstname'] == "") {
                  $errors .= "<br>Missing first name";
                  $flag = false;
               } else {
                  if (!validName($_POST['firstname'])) {
                     $errors .= "<br>First name should only contain letters";
                     $flag = false;
                  }
               }

               // last name is required and should only contain letters
               if ($_POST['lastname'] == "") {
                  $errors .= "<br>Missing last name";
                  $flag = false;
               } else {
                  if (!validName($_POST['lastname'])) {
                     $errors .= "<br>Last name should only contain letters";
                     $flag = false;
                  }
               }

               // user login is required and should only contain letters or numbers
               // user login must go through additional validation to ensure that
               // the login doesn't already exists in database
               if ($_POST['userlogin'] == "") {
                  $errors .= "<br>Missing user login";
                  $flag = false;
               } else {
                  if (!validLogin($_POST['userlogin'])) {
                     $errors .= "<br>Login should only contain letters or numbers";
                     $flag = false;
                  } else { // user name validates, check if it already exists in DB

                     require ('conn.php'); // conn.php contains constants for db connection string

                     try {
                        // connect to db
                        $conn = new PDO("mysql:host=".SERVER_NAME.";dbname=".DB_NAME."", DB_USER, DB_PASSWORD);

                        // set the PDO error mode to exception
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // check if user login exists
                        $stmt = $conn->prepare('SELECT `id` FROM `users` WHERE `login` = :login'); 
                        $stmt->bindValue(':login', $_POST['userlogin']); 
                        $stmt->execute();

                        if($stmt->rowCount() !== 0) { // if no results found
                           $errors .= "<br>Login already exists, select a different login";
                           $flag = false;
                        } 
                     } catch(PDOException $e) { // catch db connection errors
                        echo '<br><b>An error occured in retrieving results, please try again</b><br>';
                        echo 'Connection failed: ' . $e->getMessage();
                     }
                  }
               }

               // password is required and should contain at least 8 characters
               if ($_POST['password'] == "") {
                  $errors .= "<br>Missing password";
                  $flag = false;
               } else {
                  if (!validPass($_POST['password'])) {
                     $errors .= "<br>Password should have at least 8 characters";
                     $flag = false;
                  }
               }

               // email is required and should be in correct formatting
               if ($_POST['useremail'] == "") {
                  $errors .= "<br>Missing user email";
                  $flag = false;
               } else {
                  if (!validEmail($_POST['useremail'])) {
                     $errors .= "<br>Incorrect email format";
                     $flag = false;
                  }
               }

               // user birth date is required and should be in date format
               // checks for a reasonable date in the past
               if ($_POST['userbdate'] == "") {
                  $errors .= "<br>Missing birth date";
                  $flag = false;
               } else {
                  if (!validBDate($_POST['userbdate'])) {
                     $errors .= "<br>Incorrect birth date format";
                     $flag = false;
                  } elseif ($_POST['userbdate'] >= date("Y-m-d")) {
                     $errors .= "<br>Birth date must be in the past";
                     $flag = false;
                  }
               }
               
               return $flag;
            }
            
            // check if user is already logged in
            // user must be logged out to register
            if(isset($_SESSION['loggedin']) and $_SESSION['loggedin']) {
               echo '<h4>You are already logged in</h4>';
            } else {
               $errors = ""; // string variable to store error information for user

               if (isset($_POST['firstname']) and isset($_POST['lastname']) and isset($_POST['userlogin'])
               and isset($_POST['password']) and isset($_POST['useremail']) and isset($_POST['userbdate'])) {

                  if (validatePost($errors)) { // validation passes

                     try {
                        // connect to db
                        $conn = new PDO("mysql:host=".SERVER_NAME.";dbname=".DB_NAME."", DB_USER, DB_PASSWORD);

                        // set the PDO error mode to exception
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // create user account with random salt
                        $stmt = $conn->prepare('INSERT INTO `users` (`fname`, `lname`, `login`, `email`, `contact`, `bdate`, `phash`, `salt`) 
                                                VALUES (:fname, :lname, :login, :email, :contact, :bdate, SHA2(CONCAT(:pass, :salt),0), :salt)'); 

                        $salt = uniqid(mt_rand(), true);
                        $stmt->bindValue(':fname', $_POST['firstname']); 
                        $stmt->bindValue(':lname', $_POST['lastname']); 
                        $stmt->bindValue(':login', $_POST['userlogin']); 
                        $stmt->bindValue(':email', $_POST['useremail']); 
                        $stmt->bindValue(':contact', $_POST['usercontact']);
                        $stmt->bindValue(':bdate', $_POST['userbdate']);
                        $stmt->bindValue(':pass', $_POST['password']); 
                        $stmt->bindValue(':salt', $salt); 
                        $stmt->execute();

                     } catch(PDOException $e) { // catch db connection errors
                        echo '<br><b>An error occured in the database, please try again</b><br>';
                        echo 'Connection failed: ' . $e->getMessage();
                     }

                     echo '<h4>&nbsp;&nbsp;&nbsp;&nbsp;Registration complete!</h4>';

                  } else {
                     echo '<span class="errMsg">'.$errors.'</span>';
                     // user registration form in a seperate file for re-use
                     require 'registerform.inc';

                     // pre-populate previous entries
                     echo "<script>
                              $('#first-name').val('".$_POST['firstname']."');
                              $('#last-name').val('".$_POST['lastname']."');
                              $('#user-login').val('".$_POST['userlogin']."');
                              $('#password').val('".$_POST['password']."');
                              $('#user-email').val('".$_POST['useremail']."');
                              $('#user-bdate').val('".$_POST['userbdate']."');
                           </script>";
                     
                     if ($_POST['usercontact'] == "0"){
                        echo "<script>$('#user-contact').prop('checked', false);</script>";
                     }
                  }

               } else {
                  require 'registerform.inc';
               }
            }
         ?>
         <br>
     </main>

      <footer>
         <a href="#">About</a> | <a href="#">Contact</a>
      </footer>
   </body>
</html>