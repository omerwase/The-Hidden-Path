<!DOCTYPE html>

<!-- Web Systems and Web Computing Project Part 3
Filename: login.php
Description: Login page submits to itself to check user credentials

For course: CAS 6WW3
By: Omer Waseem (graduate student) -->

<html>

   <head>
      <title>The Hidden Path</title> <!-- Webpage title -->

      <!-- Addon 1: generic meta tags, homescreen tags and twitter cards -->
      <?php include 'meta.inc' ?>

      <!-- Addon 1: OpenGraph meta-data -->
      <meta property="og:title" content="User Login" />
      <meta property="og:type" content="website" />
      <meta property="og:url" content="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" />
      <meta property="og:description" content="User login." />
      <meta property="og:site_name" content="The Hidden Path" />

      <!-- CSS stylesheet link -->
      <link rel="stylesheet" href="css/style_main.css" type="text/css"> 

      <!-- Linked script files -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
   </head>

   <body>
      <nav>
         <!-- navication menu stored in a seperate file for re-use -->
         <?php include 'nav.inc' ?>
      </nav>

      <header><h1>The Hidden Path</h1></header>

      <main>
         <h3>&nbsp;&nbsp;User Login</h3>

         <?php

            // function queries the database with login credentials to find a match
            function checkAuth($login, $pass) { 
               require ('conn.php'); // conn.php contains constants for db connection string

               try {
                  // connect to db
                  $conn = new PDO("mysql:host=".SERVER_NAME.";dbname=".DB_NAME."", DB_USER, DB_PASSWORD);

                  // set the PDO error mode to exception
                  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                  // select trails based on given criteria (name and rating)
                  // password stored using salt and SHA2 hashing
                  $stmt = $conn->prepare('SELECT `id`, `fname`, `lname` FROM `users` WHERE `login` = :login AND `phash` = SHA2(CONCAT(:pass,`salt`),0)'); 
                  $stmt->bindValue(':login', $login); 
                  $stmt->bindValue(':pass', $pass); 
                  $stmt->execute();

                  if($stmt->rowCount() == 0) { // return false if user is not found or credentials don't match'
                     return false;
                  } else { // credentials match, populate user object and return true
                     session_regenerate_id(); // regenerate session id to avoid session fixation attack
                     $user = $stmt->fetchAll();
                     $_SESSION['user'] = $user[0];
                     return true;
                  }
               }
               catch(PDOException $e) // catch db connection errors
               {
                  echo '<br><b>A connection error occured, please try again</b><br>';
                  echo 'Connection failed: ' . $e->getMessage();
               }
            }

            // start_session() is called in nav.inc
            if (!isset($_SESSION['loggedin'])) { // if user is not logged in show login form
               if (isset($_POST['loginid']) and isset($_POST['password'])) { // check if login.php resubmitted with post values set
                  if (checkAuth($_POST['loginid'], $_POST['password'])) { // check if user credentials match configure login
                     $_SESSION['loggedin'] = true;
                     echo '<script>
                              $("#msgLogin").html("Logout");
                           </script>
                           <h4>&nbsp;&nbsp;&nbsp;&nbsp;Welcome '.$_SESSION['user']['fname'].' '.$_SESSION['user']['lname'].'!</h4>
                           <h4>&nbsp;&nbsp;&nbsp;&nbsp;You are logged in</h4>';
                  } else { // user credentials don't match
                     echo '<h4 class="errMsg">&nbsp;&nbsp;&nbsp;&nbsp;Invalid credentials</h4>';
                     require('loginform.inc');
                  }
               } else { // user has not logged in yet
                  require('loginform.inc');
               }
            } else { // user is currently logged in and clicked logout, perform logout operations
               unset($_SESSION['loggedin']);
               unset($_SESSION['user']);
               echo '<script>
                        $("#msgLogin").html("Login");
                     </script>
                     <h4>&nbsp;&nbsp;&nbsp;&nbsp;You are now logged out</h4>';
               require('loginform.inc');
            }
         ?>
      </main>

      <br>
      <footer>
         <a href="#">About</a> | <a href="#">Contact</a>
      </footer>
   </body>
</html>