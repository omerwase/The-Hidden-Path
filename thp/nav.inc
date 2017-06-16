<!-- Web Systems and Web Computing Project Part 3
Filename: nav.inc
Description: top navigation menu

For course: CAS 6WW3
By: Omer Waseem (graduate student) -->

<!-- for larger screens nav menu is display horizontally on the top of the page with fixed position. 
For smaller screens (less than 500px) the menu is stacked vertically with static position -->
<ul class="topnav">

   <!-- lower/higher resolution logo based on screen size -->
   <li><a href="index.php">
      <picture>
         <source srcset="images/logo_lg.png" media="(min-width: 500px)">
         <img src="images/logo_sm.png" alt="Logo">
      </picture>
   </a></li>

   <!-- link with active class indicates current page -->
   <li><a class="links" href="index.php">Search</a></li>
   <li><a class="links" href="submission.php">Submit</a></li>
   <li><a class="links" href="registration.php">Register</a></li>

   <!-- login link is positioned to the right -->
   <li class="login"><a class="links" href="login.php"><span id="msgLogin" name="msgLogin">
      <?php // checks if user is logged in or not and sets message accordingly
         ini_set('session.use_only_cookies', 1); // disable tokens in URLs for security
         session_start();
         if (isset($_SESSION['loggedin']) and $_SESSION['loggedin'] == true) {
            echo 'Logout';
         } else {
            echo 'Login';
         }
      ?>
   </span></a></li> 
</ul>