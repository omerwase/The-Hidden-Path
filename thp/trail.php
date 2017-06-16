<!DOCTYPE html> <!-- Document type declaration for HTML5 -->

<!-- Web Systems and Web Computing Project Part 3
Filename: trail.php
Description: Details about an individual trail. Logged in users can submitted reviews 

For course: CAS 6WW3
By: Omer Waseem (graduate student) 

Addon 2: the trail map, image and video change size based on screen size -->

<html prefix="og: http://ogp.me/ns#">

   <head>
      <title>The Hidden Path</title> <!-- Webpage title -->

      <!-- Addon 1: generic meta tags, homescreen tags and twitter cards -->
      <?php include 'meta.inc'; ?>

      <?php
         require ('conn.php'); // conn.php contains constants for db connection string

         try {
            // connect to db
            $conn = new PDO("mysql:host=".SERVER_NAME.";dbname=".DB_NAME."", DB_USER, DB_PASSWORD);

            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // select trail based on ID parameter
            $stmt = $conn->prepare('SELECT `trails`.`id`, `name`, `description`, `latitude`, `longitude`, `image_url`, `video_url`, AVG(`reviews`.`rating`) AS `trating` FROM `trails`
                                                   LEFT JOIN `reviews` ON `trails`.`id` = `reviews`.`trailid`
                                                   WHERE `trails`.`id` = :id
                                                   GROUP BY `trails`.`id`');
            $stmt->bindValue(':id', $_GET['ID']); 
            $stmt->execute();
            
            global $result;
            $result = $stmt->fetchAll(); // store result from query

            if(count($result) != 0) {  // only set meta tags if trail is found
      ?> 
               <!-- Addon 1: OpenGraph meta-data -->
               <meta property="og:title" content="<?php echo $result[0]['name'];?>" />
               <meta property="og:type" content="Trail" />
               <meta property="og:url" content="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" />
               <meta property="og:image" content="<?php echo $result[0]['image_url'];?>" />
               <meta property="og:description" content="<?php echo $result[0]['description'];?>" />
               <meta property="og:site_name" content="The Hidden Path" />
      <?php } ?>

      <!-- CSS stylesheet link -->
      <link rel="stylesheet" href="css/style_main.css" type="text/css"> 

      <!-- LeafletJS stylesheet CDN link -->
      <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.1/dist/leaflet.css" />

      <!-- Linked script files -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
      <script src="https://unpkg.com/leaflet@1.0.1/dist/leaflet.js"></script>
      <script type="text/javascript" src="scripts/geolocation.js"></script>
      <script type="text/javascript" src="scripts/results_map.js"></script>
      <script type="text/javascript" src="scripts/review.js"></script>
   </head>

   <body> 
      <nav>
         <!-- navication menu stored in a seperate file for re-use -->
         <?php include 'nav.inc'; ?>
      </nav>

      <header><h1>The Hidden Path</h1></header>

      <?php

            if(count($result) == 0) { // if no results found
               echo '<br><b>Trail not found</b><br><br>';

            } else { // store result from query

               // if trail is found query database for reviews and inner join users table for user info
               $stmt = $conn->prepare('SELECT `fname`, `lname`, `rating`, `comment`, `date` FROM `reviews` 
                                       INNER JOIN `users` ON `users`.`id` = `reviews`.`userid` 
                                       WHERE `trailid` = :id'); 
               $stmt->bindValue(':id', $_GET['ID']); 
               $stmt->execute();
               global $reviews;
               $reviews = $stmt->fetchAll();
      ?>

         <main class="trail-details">
            <!-- Addon 1: Micro-data for place -->
            <div itemscope itemtype="http://schema.org/Place">
               <!-- Trail details pulled from $results[0] which is the first returned result -->
               <h3>Trail Details</h3>
               <h4>Name: <span itemprop="name"><?php echo $result[0]['name'];?></span></h4>
               <!-- the span in rating below is used to update rating based on AJAX review submission-->
               <h4>Rating: <span id="trating"><?php echo $result[0]['trating'] == 0 ? 'NA' : substr($result[0]['trating'],0,3);?></span></h4>
               <div itemprop="geo" itemscope itemtype="http://schema.org/GeoCoordinates">
                  <h4>Latitude: <?php echo $result[0]['latitude'];?></h4>
                  <h4>Longitude: <?php echo $result[0]['longitude'];?></h4>
                  <meta itemprop="latitude" content="<?php echo $result[0]['latitude'];?>" />
                  <meta itemprop="longitude" content="<?php echo $result[0]['longitude'];?>" />
               </div>
               <h4>Description: <?php echo $result[0]['description'];?></h4>
               
               <br>
               <h4>Trail location:</h4>
               <!-- Trail map scales to screen and different resolution images are used based on sreen size -->
               <div id="trail-map">
               </div>
               
               <!-- call drawMap() from results_map.js to draw map markers -->
               <script type="text/javascript">
                  drawMap(<?php echo json_encode($result);?>, "trail-map", <?php echo $result[0]['latitude'];?>, <?php echo $result[0]['longitude'];?>);
               </script>
            </div>
            
            <br>
            <h4>Trail Image:</h4>
            <!-- only displays image if they are available -->
            <?php if ($result[0]['image_url'] != "") { ?>
                  <img class="image-size2" src="<?php echo $result[0]['image_url'];?>" alt="<?php echo $result[0]['name'];?>">
            <?php } else {
                  echo 'Image is not available for this trail'; // no image found in database
               }
            ?>

            <br><br>
            <!-- Addon 2: video -->
            <h4>Trail Video:</h4>
            <!-- only displays video if they are available -->
            <?php if ($result[0]['video_url'] != "") { ?>
               <video class="video-size" controls>
                  <source src="<?php echo $result[0]['video_url'];?>" type="video/mp4">
                  Your browser does not support HTML5 video.
               </video>
            <?php } else {
                  echo 'Video is not available for this trail'; // no video found in database
               }
            ?>
            <br><br><hr>

            <!-- User review submission -->
            <?php
               if(isset($_SESSION['loggedin']) and $_SESSION['loggedin']) {
                  // the hidden fields below contain trail and user information for AJAX review submission
                  echo '<h3>Submit a review below</h3>
                        <input type="hidden" name="trailid" id="trailid" value="'.$_GET['ID'].'" />
                        <input type="hidden" name="userid" id="userid" value="'.$_SESSION['user']['id'].'" />
                        <input type="hidden" name="username" id="username" value="'.$_SESSION['user']['fname'].' '.$_SESSION['user']['lname'].'" />
                        <span id="errorMsg"></span>';
                  include 'userreview.inc';
               } else {
                  echo '<br><h4><u><a href="login.php">Login to submit a review</a></u></h4><br>';
               }
            ?>

            <!-- Addon 1: Micro-data for reviews and ratings is added below -->
            <!-- The user-review class provides text formatting specifically for reviews-->
            <hr><br><h3>User Reviews:</h3>
            <div id="userreviews">
            <?php
               if (count($reviews) === 0) { // if no reviews are available display appropriate message
                  echo '<h4 id="noreviews">This trail has no reviews</h4>';
               } else { // else loop over each review and display info
                  foreach ($reviews as $rev) {
               ?>
                  <div class="user-review">
                     <hr>
                     <div itemprop="review" itemscope itemtype="http://schema.org/Review">
                        <h4>Name: <span itemprop="author"><?php echo $rev['fname'].' '.$rev['lname'];?></span></h4>
                        <div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
                           <meta itemprop="worstRating" content = "1">
                           <meta itemprop="bestRating" content = "5">
                           <h4>Rating: <span itemprop="ratingValue"><?php echo $rev['rating'];?></span></h4>
                           </div>
                        <b>Comment:</b> <span itemprop="description"><?php echo $rev['comment'];?></span>
                        <meta itemprop="datePublished" content="<?php echo $rev['date'];?>"><h5>Date: <?php echo $rev['date'];?></h5>
                        <br>
                     </div>      
                  </div>
               <?php
                  }
               }
            ?>
            </div>

         </main>
      
      <?php
            }
         }
         catch(PDOException $e) // catch db connection errors
         {
            echo '<br><b>An error occured in retrieving results, please try again</b><br>';
            echo 'Connection failed: ' . $e->getMessage();
         }
      ?>

      <footer>
         <a href="#">About</a> | <a href="#">Contact</a>
      </footer>
   </body>
</html>