<!DOCTYPE html>

<!-- Web Systems and Web Computing Project Part 3
Filename: submission.php
Description: Submission page for users to enter new trails with client and server side validation

For course: CAS 6WW3
By: Omer Waseem (graduate student) 

Reference: file upload based on CAS 6WW3 workshop -->

<html>

   <head>
      <title>The Hidden Path</title> <!-- Webpage title -->

      <!-- Addon 1: generic meta tags, homescreen tags and twitter cards -->
      <?php include 'meta.inc' ?>

      <!-- Addon 1: OpenGraph meta-data -->
      <meta property="og:title" content="Trail Submission" />
      <meta property="og:type" content="website" />
      <meta property="og:url" content="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" />
      <meta property="og:description" content="Submit a trail for others to see." />
      <meta property="og:site_name" content="The Hidden Path" />

      <!-- CSS stylesheet link -->
      <link rel="stylesheet" href="css/style_main.css" type="text/css"> 

      <!-- Linked script files -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
      <script type="text/javascript" src="scripts/geolocation.js"></script>
      <script type="text/javascript" src="scripts/fileupload.js"></script>
   </head>

   <body>
      <nav>
         <!-- navication menu stored in a seperate file for re-use -->
         <?php include 'nav.inc' ?>
      </nav>

      <header><h1>The Hidden Path</h1></header>

      <main>
         <h3>Submit a trail for others to see</h3>

         <?php // server side validation with form posting to self

            require ('conn.php'); // file containing DB connection info and s3 bucket info

            // validation for name: should only contains letters and spaces
            function validName($name) {
               return preg_match('/^[A-z0-9 ]+$/', $name); 
            }

            // validation for name: should only contains letters and spaces
            function validCoordinate($cord) {
               return preg_match('/^-?\d*\.?\d*$/', $cord); 
            }

            // validPost() checks if post parameters are set and calls the respective validate functions
            // on each parameter to check for errors. 
            function validatePost(&$errors) {
               
               $flag = true; // boolean variable where false indicates errors

               $errors .= "Errors in submission:";

               // the following if statements check each post input and populate the error variable

               // trail name is required and should only contain letters and spaces
               if ($_POST['trailname'] == "") {
                  $errors .= "<br>Missing trail name";
                  $flag = false;
               } else {
                  if (!validName($_POST['trailname'])) {
                     $errors .= "<br>Trail name should only contain letters and spaces";
                     $flag = false;
                  }
               }

               // latitude is required and must match int or float formatting
               if ($_POST['latitude'] == "") {
                  $errors .= "<br>Missing latitude";
                  $flag = false;
               } else {
                  if (!validCoordinate($_POST['latitude'])) {
                     $errors .= "<br>Incorrect latitude format";
                     $flag = false;
                  } else { // check if latitude is within range
                     if ($_POST['latitude'] < "-90" or $_POST['latitude'] > "90") {
                        $errors .= "<br>Latitude out of range [-90 to 90]";
                        $flag = false;
                     }
                  }
               }

               // longitude is required and must match int or float formatting
               if ($_POST['longitude'] == "") {
                  $errors .= "<br>Missing longitude";
                  $flag = false;
               } else {
                  if (!validCoordinate($_POST['longitude'])) {
                     $errors .= "<br>Incorrect longitude format";
                     $flag = false;
                  } else { // check if longitude is within range
                     if ($_POST['longitude'] < "-180" or $_POST['longitude'] > "180") {
                        $errors .= "<br>Longitude out of range [-180 to 180]";
                        $flag = false;
                     }
                  }
               }

               // trail description is required
               if ($_POST['traildesc'] == "") {
                  $errors .= "<br>Missing trail description";
                  $flag = false;
               }
               
               return $flag;
            }

            // validFilesUpload checks submitted files and uploads if valid
            function validFilesUpload(&$errors, &$imageurl, &$videourl) {
               $flag = true;
               
               // check for image upload
               if ($_FILES['trailimage']['name'] != "") { // image file is available

                  // check for image upload errors
                  if ($_FILES['trailimage']['error'] == UPLOAD_ERR_INI_SIZE) { // file is too big (over 2MB)
                     $errors .= "<br>Image file exceeds 2MB limit";
                     $flag = false;
                  } elseif (!isset($_FILES['trailimage']['error']) || ($_FILES['trailimage']['error'] != UPLOAD_ERR_OK)) {
                     $errors .= "<br>Error uploading image";
                     $flag = false;
                  } else {
                     $imageinfo = new finfo(FILEINFO_MIME_TYPE);
                     if ($imageinfo->file($_FILES['trailimage']['tmp_name']) === "image/jpeg") { // check if image is jpg
                        $imageext = "jpg";
                     } elseif ($imageinfo->file($_FILES['trailimage']['tmp_name']) === "image/png") { // check if image is png
                        $imageext = "png";
                     } else {
                        $errors .= '<br>Invalid image format (must be jpg or png)';
                        $flag = false;
                     }
                  }
               }

               // check for video upload
               if ($_FILES['trailvideo']['name'] != "") { // video file is available
               
                  // check for video upload errors
                  if ($_FILES['trailvideo']['error'] == UPLOAD_ERR_INI_SIZE) { // file is too big (over 2MB)
                     $errors .= "<br>Video file exceeds 2MB limit";
                     $flag = false;
                  } elseif (!isset($_FILES['trailvideo']['error']) || ($_FILES['trailvideo']['error'] != UPLOAD_ERR_OK)) {
                     $errors .= "<br>Error uploading video";
                     $flag = false;
                  } else {
                     $videoinfo = new finfo(FILEINFO_MIME_TYPE);
                     if ($videoinfo->file($_FILES['trailvideo']['tmp_name']) === "video/mp4") { // check if video is mp4
                        $videoext = "mp4";
                     } else {
                        $errors .= '<br>Invalid video format (must be mp4)';
                        $flag = false;
                     }
                  }
               }

               // upload files once they pass validation
               if ($flag and ($_FILES['trailimage']['name'] != "" or $_FILES['trailvideo']['name'] != "")) {

                  require('S3.php'); // S3 library for uploading to AWS S3 bucket
                  $s3 = new S3(AWS_ACCESS_KEY, AWS_SECRET_KEY);

                  // valid image file is available
                  if ($_FILES['trailimage']['name'] != "") {

                     // create unique file name for image
                     $imagehash = sha1_file($_FILES['trailimage']['tmp_name']);
                     $imagename = $imagehash . "." . $imageext;

                     // upload image file into S3 bucket
                     $ok = $s3->putObjectFile($_FILES['trailimage']['tmp_name'], BUCKET_NAME, $imagename, S3::ACL_PUBLIC_READ);
                     if ($ok) {
                        $imageurl = 'https://s3.amazonaws.com/' . BUCKET_NAME . '/' . $imagename;
                        echo '<p>&nbsp;&nbsp;&nbsp;&nbsp;Image upload successful</p>';
                     } else {
                        $errors .= "<br>Error uploading image";
                        $flag = false;
                     }
                  }

                  // valid video file is available and image upload did not fail previously
                  if ($flag and $_FILES['trailvideo']['name'] != "") { 

                     // create unique file name for video
                     $videohash = sha1_file($_FILES['trailvideo']['tmp_name']);
                     $videoname = $videohash . "." . $videoext;

                     // upload video file into S3 bucket
                     $ok = $s3->putObjectFile($_FILES['trailvideo']['tmp_name'], BUCKET_NAME, $videoname, S3::ACL_PUBLIC_READ);
                     if ($ok) {
                        $videourl = 'https://s3.amazonaws.com/' . BUCKET_NAME . '/' . $videoname;
                        echo '<p>&nbsp;&nbsp;&nbsp;&nbsp;Video upload successful</p>';
                     } else {
                        $errors .= "<br>Error uploading video";
                        $flag = false;
                     }
                  }
               }

               return $flag;
            }

            $errors = ""; // string variable to store error information for user
            $imageurl = "";
            $videourl = "";

            if(!isset($_SESSION['loggedin']) or !$_SESSION['loggedin']) {
               echo '<h4><u><a href="login.php">You must be logged in to submit a trail</a></u></h4>';
            } else { // user must be logged in to submit a trail

               if (isset($_POST['trailname']) and isset($_POST['latitude']) and isset($_POST['longitude']) and isset($_POST['traildesc'])) {

                  // post and files validation passes
                  if (validatePost($errors) and validFilesUpload($errors, $imageurl, $videourl)) {

                     try {
                        // connect to DB
                        $conn = new PDO("mysql:host=".SERVER_NAME.";dbname=".DB_NAME."", DB_USER, DB_PASSWORD);

                        // set the PDO error mode to exception
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // create trail insert statement
                        $stmt = $conn->prepare('INSERT INTO `trails` (`name`, `description`, `latitude`, `longitude`, `image_url`, `video_url`) 
                                                VALUES (:name, :description, :latitude, :longitude, :image_url, :video_url)'); 

                        // bind insert values for trail
                        $salt = uniqid(mt_rand(), true);
                        $stmt->bindValue(':name', $_POST['trailname']); 
                        $stmt->bindValue(':description', $_POST['traildesc']); 
                        $stmt->bindValue(':latitude', $_POST['latitude']); 
                        $stmt->bindValue(':longitude', $_POST['longitude']); 
                        $stmt->bindValue(':image_url', $imageurl);
                        $stmt->bindValue(':video_url', $videourl);
                        $stmt->execute();

                     } catch(PDOException $e) { // catch db connection errors
                        echo '<br><b>An error occured in the database, please try again</b><br>';
                        echo 'Connection failed: ' . $e->getMessage();
                     }

                     echo '<h4>&nbsp;&nbsp;&nbsp;&nbsp;Submission complete!</h4>';

                  } else {
                     echo '<span class="errMsg">'.$errors.'</span>';
                     // trail submission form in a seperate file for re-use
                     require 'submissionform.inc';

                     // pre-populate previous entries
                     echo "<script>
                              $('#trail-name').val('".$_POST['trailname']."');
                              $('#latitude').val('".$_POST['latitude']."');
                              $('#longitude').val('".$_POST['longitude']."');
                              $('#trail-desc').val('".$_POST['traildesc']."');
                           </script>";
                  }

               } else {
                  require 'submissionform.inc';
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