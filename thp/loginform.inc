<!-- Web Systems and Web Computing Project Part 3
Filename: loginform.inc
Description: seperate file containing login form

For course: CAS 6WW3
By: Omer Waseem (graduate student) -->

<form action="login.php" id="loginform" name="loginform" method="post">

   <!-- input groups below use HTML5 validation. Login ID and password are required fields -->
   <!-- input group below have formatting applied so their labels will stack on smaller screens -->
   <div class="input-group">
      <label for="loginid" class="input-label">
         <b>Login ID: </b>
      </label>
      <input id="loginid" name="loginid" class="input-field" type="text" patter="^[A-z0-9]+$" required>
   </div>

   <div class="input-group">
      <label for="password" class="input-label">
         <b>Password: </b>
      </label>
      <!-- password must contain at least 8 characters with 1 alphabet and 1 number -->
      <input id="password" name="password" class="input-field" type="password" pattern="^.{8,}$" required>
   </div>

   <div class="input-group">
      <label for="submit-login" class="submit-label">Login</label>
      <input id="submit-login" type="submit">
   </div>
   
</form>