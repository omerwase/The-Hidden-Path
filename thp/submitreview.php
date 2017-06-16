<?php
   /* Web Systems and Web Computing Project Part 3
      Filename: submitreview.php
      Description: php file for handling review AJAX request

      For course: CAS 6WW3
      By: Omer Waseem (graduate student) 

      Reference: AJAX implementation based on CAS 6WW3 AJAX tutorial */

   // server-side validation for review submission
   // ensure user rating is set and available
   if (!isset($_POST["userrating"]) || ($_POST["userrating"] === "")) {
      echo json_encode(array("status" => false, "message" => " No rating provided<br>"));
      return;
   }

   // ensure user comment is set and available
   if (!isset($_POST["comment"]) || ($_POST["comment"] === "")) {
      echo json_encode(array("status" => false, "message" => " Comment is empty<br>"));
      return;
   }

   // ensure trailid is set and available
   if (!isset($_POST["trailid"]) || ($_POST["trailid"] === "")) {
      echo json_encode(array("status" => false, "message" => " Trail ID not found<br>"));
      return;
   }

   // ensure user id is set and available
   if (!isset($_POST["userid"]) || ($_POST["userid"] === "")) {
      echo json_encode(array("status" => false, "message" => " User ID not found<br>"));
      return;
   }

   // ensure user rating is numeric
   if (!is_numeric($_POST["userrating"])) {
      echo json_encode(array("status" => false, "message" => " Rating is not a number<br>"));
      return;
   }

   // ensure trailid is numeric
   if (!is_numeric($_POST["trailid"])) {
      echo json_encode(array("status" => false, "message" => " Trail ID is not a number<br>"));
      return;
   }

   // ensure userid is numeric
   if (!is_numeric($_POST["userid"])) {
      echo json_encode(array("status" => false, "message" => " User ID is not a number<br>"));
      return;
   }

   require ('conn.php'); // conn.php contains constants for db connection string

   // if no validation errors found then proceed with review submission to database
   try {
      // connect to db
      $conn = new PDO("mysql:host=".SERVER_NAME.";dbname=".DB_NAME."", DB_USER, DB_PASSWORD);

      // set the PDO error mode to exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // insert review into database
      $stmt = $conn->prepare('INSERT INTO `reviews` (`trailid`, `userid`, `rating`, `comment`, `date`) 
                              VALUES (:trailid, :userid, :rating, :comment, CURDATE())'); 

      $salt = uniqid(mt_rand(), true);
      $stmt->bindValue(':trailid', $_POST['trailid']); 
      $stmt->bindValue(':userid', $_POST['userid']); 
      $stmt->bindValue(':rating', $_POST['userrating']); 
      $stmt->bindValue(':comment', $_POST['comment']); 
      $stmt->execute();

      // retrive last insert id of review to retrive date
      $lastid = $conn->lastInsertId();
      global $reviewdate;

      // query for date of submitted review
      $stmt = $conn->prepare('SELECT `date` FROM `reviews` WHERE `id` = :lastid'); 
      $stmt->bindValue(':lastid', $lastid); 
      $stmt->execute();
      $date = $stmt->fetchAll();
      $reviewdate = $date[0]['date'];

      // query for new trail rating based on AJAX submitted review
      $stmt = $conn->prepare('SELECT AVG(`rating`) as trating FROM `reviews`
                              GROUP BY `trailid` 
                              HAVING `trailid` = :trailid'); 
      $stmt->bindValue(':trailid', $_POST['trailid']); 
      $stmt->execute();
      $trating = $stmt->fetchAll();
      $newrating = $trating[0]['trating'];

   } catch(PDOException $e) { // catch db connection errors
      echo json_encode(array("status" => false, "message" => ' An error occured in the database, please try again<br>'.$e->getMessage().'<br>'));
      return;
   }

   // if no errors encounters, returns json object containing submitted review for injection into DOM
   echo json_encode(array("status" => true, "userrating" => htmlspecialchars($_POST["userrating"]), "comment" => htmlspecialchars($_POST["comment"]),
                        "trailid" => htmlspecialchars($_POST["trailid"]), "userid" => htmlspecialchars($_POST["userid"]), 
                        "username" => htmlspecialchars($_POST["username"]), "reviewdate" => htmlspecialchars($reviewdate),
                        "newrating" => htmlspecialchars($newrating)));
?>