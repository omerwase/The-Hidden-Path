<!DOCTYPE html>

<!-- Web Systems and Web Computing Project Part 3
Filename: index.php
Description: main page for website, provides search features and navigation

For course: CAS 6WW3
By: Omer Waseem (graduate student) 

Addon 2: the logo that is part of the nav menu changes
based on screen size using <picture> and <source> -->

<html>

   <head>
      <title>The Hidden Path</title> <!-- Webpage title -->

      <!-- Addon 1: generic meta tags, homescreen tags and twitter cards -->
      <?php include 'meta.inc' ?>

      <!-- Addon 1: OpenGraph meta-data -->
      <meta property="og:title" content="Trails Search" />
      <meta property="og:type" content="website" />
      <meta property="og:url" content="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>" />
      <meta property="og:description" content="Search for a trail" />
      <meta property="og:site_name" content="The Hidden Path" />

      <!-- CSS stylesheet link -->
      <link rel="stylesheet" href="css/style_main.css" type="text/css"> 

      <!-- Linked script files -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
      <script type="text/javascript" src="scripts/geolocation.js"></script>
      <script type="text/javascript" src="scripts/getloc.js"></script>
      
   </head>

   <body>
      <nav>
         <!-- navication menu stored in a seperate file for re-use -->
         <?php include 'nav.inc' ?> 
      </nav>

      <header><h1>The Hidden Path</h1></header>

      <main>
         <!-- search form stored in a seperate file for re-use with results page -->
         <?php include 'search.inc' ?>
     </main>
      <br>
      <footer>
         <a href="#">About</a> | <a href="#">Contact</a>
      </footer>
   </body>
</html>