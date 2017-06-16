<!DOCTYPE html>

<!-- Web Systems and Web Computing Project Part 3
Filename: results.php
Description: Search results from main search page

For course: CAS 6WW3
By: Omer Waseem (graduate student)

Addon 2: the result map and trail images change size based on screen size -->

<html>

   <head>
      <title>The Hidden Path</title> <!-- Webpage title -->

      <!-- Addon 1: generic meta tags, homescreen tags and twitter cards -->
      <?php include 'meta.inc' ?>

      <!-- Addon 1: OpenGraph meta-data -->
      <meta property="og:title" content="Search Results" />
      <meta property="og:type" content="website" />
      <meta property="og:url" content="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" />
      <meta property="og:description" content="Results from the search" />
      <meta property="og:site_name" content="The Hidden Path" />

      <!-- CSS stylesheet link -->
      <link rel="stylesheet" href="css/style_main.css" type="text/css"> 

      <!-- LeafletJS stylesheet CDN link -->
      <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.1/dist/leaflet.css" />

      <!-- Linked script files -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
      <script src="https://unpkg.com/leaflet@1.0.1/dist/leaflet.js"></script>
      <script type="text/javascript" src="scripts/geolocation.js"></script>
      <script type="text/javascript" src="scripts/results_map.js"></script>
      <script type="text/javascript" src="scripts/getloc.js"></script>

   </head>

   <body>
      <nav>
         <!-- navication menu stored in a seperate file for re-use -->
         <?php include 'nav.inc' ?>
      </nav>

      <header><h1>The Hidden Path</h1></header>

      <main>
         <!-- search form stored in a seperate file for re-use -->
         <?php include 'search.inc' ?>

         <!-- set search and rating values from previous search -->
         <?php 
            if(isset($_GET['search'])) {
               echo '<script>
                        $("#search").val("'.$_GET["search"].'");
                     </script>';
            }

            if(isset($_GET['rating']) and $_GET['rating'] >= 1 and $_GET['rating'] <= 5) {
               echo '<script>
                        $("#rating").val("'.$_GET["rating"].'");
                     </script>';
            }

            if(isset($_GET['range']) and $_GET['range'] >= 5 and $_GET['range'] <= 100) {
               echo '<script>
                        $("#range").val("'.$_GET["range"].'");
                     </script>';
            }

            if(isset($_GET['rangecheck']) and $_GET['rangecheck'] == "1") {
               echo '<script>
                        $("#rangecheck").prop("checked", true);
                     </script>';
            }
         ?>

         <!-- search results are displayed in both map and table.
         For larger screens the map and table results show side-by-side
         For smaller screens the map and table results are stack one of top of the other-->
         <div id="results">

             <!-- Map image scales based on screen size-->
            <div class="floating-result" id="rmap">
               <div id="result-map">
               </div>
            </div>

            <!-- results table shows image of trail along with details.
            Trail images scale based on screen size -->
            <div class="floating-result">
               <table class="result-table" style="width:100%">

                  <?php
                     require ('conn.php'); // conn.php contains constants for db connection string

                     try {
                        // connect to db
                        $conn = new PDO("mysql:host=".SERVER_NAME.";dbname=".DB_NAME."", DB_USER, DB_PASSWORD);

                        // set the PDO error mode to exception
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // select trails based on given criteria (name and rating)
                        // if statement check if rangecheck is selected, if so search include distance from user's location
                        // distance SQL formula referenced from: http://sqlfiddle.com/#!2/abcc8/4
                        $ifrange = ($_GET['rangecheck'] == "1" and $_GET['latitude'] != "" and $_GET['longitude'] != "");
                        if ($ifrange) {
                           // the select statement below calculates average rating based on reviews and distance based on user's coordinates
                           $stmt = $conn->prepare('SELECT `trails`.`id`, `name`, `description`, `latitude`, `longitude`, `image_url`, AVG(`reviews`.`rating`) AS `trating`,
                                                             111.1111   * DEGREES(ACOS(COS(RADIANS(`latitude`))
                                                                        * COS(RADIANS(:latitude))
                                                                        * COS(RADIANS(`longitude`) - RADIANS(:longitude))
                                                                        + SIN(RADIANS(`latitude`))
                                                                        * SIN(RADIANS(:latitude)))) AS dist FROM `trails` 
                                                   LEFT JOIN `reviews` ON `trails`.`id` = `reviews`.`trailid`
                                                   WHERE `name` LIKE :name 
                                                   GROUP BY `trails`.`id`
                                                   HAVING `dist` <= :dist AND (`trating` >= :rating OR `trating` is NULL) 
                                                   ORDER BY `dist`'); 
                           $stmt->bindValue(':latitude', $_GET['latitude']); 
                           $stmt->bindValue(':longitude', $_GET['longitude']); 
                           $stmt->bindValue(':dist', $_GET['range']); 
                        } else {
                           // the select statement below calculates average rating based on reviews
                           $stmt = $conn->prepare('SELECT `trails`.`id`, `name`, `description`, `latitude`, `longitude`, `image_url`, AVG(`reviews`.`rating`) AS `trating` FROM `trails`
                                                   LEFT JOIN `reviews` ON `trails`.`id` = `reviews`.`trailid`
                                                   WHERE `name` LIKE :name
                                                   GROUP BY `trails`.`id` 
                                                   HAVING (`trating` >= :rating OR `trating` is NULL) 
                                                   ORDER BY `trating` desc'); 
                        }
                        $stmt->bindValue(':name', '%'.$_GET['search'].'%'); 
                        $stmt->bindValue(':rating', $_GET['rating']); 
                        $stmt->execute();

                        if($stmt->rowCount() == 0) { // if no results found, display message and hide the map
                           echo '<div>
                                    <b>No trails match the search criteria</b>
                                 </div><br>
                                 <script>
                                    $("#rmap").hide();
                                 </script>';
                        } else { // print results in table
                           echo '<tr>
                                    <th colspan="2">Search Results</th>
                                 </tr>';

                           global $result; 
                           $result = $stmt->fetchAll(); // store all results from query

                           foreach ($result as $row) { // create table entry for each result
                           ?>
                              <tr>
                                 <td class="centered">
                                    <a href="trail.php?ID=<?php echo $row['id'];?>">
                                       <?php if ($row['image_url'] != "") { ?>
                                             <img class="image-size" src="<?php echo $row['image_url'];?>" alt="<?php echo $row['name'];?>">
                                       <?php } else {
                                             echo 'Image not available'; // no image found in database
                                          }
                                       ?>
                                    </a>
                                 </td>
                                 <td>
                                    <a href="trail.php?ID=<?php echo $row['id'];?>"><b><?php echo $row['name'];?></b><br>
                                       <u>Rating:</u> <?php echo $row['trating'] == 0 ? 'NA' : substr($row['trating'],0,3);?><br>
                                       <?php if($ifrange) {
                                          echo '<u>Distance to trail:</u> '.substr($row['dist'],0,5).' km<br>';
                                       }?>
                                       <u>Description:</u> <?php echo $row['description'];?><br>
                                    </a>
                                 </td>
                              </tr>
                           <?php
                           }

                           // call drawMap() from results_map.js to draw map markers
                           if ($ifrange) { // set center and zoom for map based on user location and range
                              $zoom = 10;
                              if ($_GET['range'] <= "5") {
                                 $zoom = 12;
                              } else if ($_GET['range'] <= "10") {
                                 $zoom = 11;
                              } else if ($_GET['range'] <= "15") {
                                 $zoom = 10;
                              } else if ($_GET['range'] <= "25") {
                                 $zoom = 9;
                              } else {
                                 $zoom = 8;
                              }
                              echo '<script type="text/javascript">drawMap('.json_encode($result).',"result-map",'.$_GET['latitude'].','.$_GET['longitude'].','.$zoom.');</script>';
                           } else {
                              echo '<script type="text/javascript">drawMap('.json_encode($result).',"result-map",'.$result[0]['latitude'].','.$result[0]['longitude'].');</script>'; 
                           }
                        }
                     }
                     catch(PDOException $e) // catch db connection errors
                     {
                        echo '<br><b>An error occured in retrieving results, please try again</b><br>';
                        echo 'Connection failed: ' . $e->getMessage();
                     }
                  ?>
               </table>
            </div>
         </div>
     </main>

      <footer>
         <a href="#">About</a> | <a href="#">Contact</a>
      </footer>
   </body>
</html>