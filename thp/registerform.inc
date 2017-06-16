<!-- Web Systems and Web Computing Project Part 3
Filename: registerform.inc
Description: registration form for registeration.php

For course: CAS 6WW3
By: Omer Waseem (graduate student) -->

<form action="registration.php" method="post" onsubmit="return validate(this);">   
   <h4>Enter account details below:</h4>

   <!-- input group below have formatting applied so their labels will stack on smaller screens -->
   <!-- 5 different input types are used below for registration: text, email, checkbox, date and file -->
   <div class="input-group">
      <label for="first-name" class="input-label">
         <b>First name: </b>
      </label>
      <input name="firstname" id="first-name" class="input-field" type="text">
      <!-- span is used to display error message -->
      <span id="msgFirstName" class="errMsg"></span>
   </div>

   <div class="input-group">
      <label for="last-name" class="input-label">
         <b>Last name: </b>
      </label>
      <input name="lastname" id="last-name" class="input-field" type="text">
      <!-- span is used to display error message -->
      <span id="msgLastName" class="errMsg"></span>
   </div>

   <div class="input-group">
      <label for="user-login" class="input-label">
         <b>Login ID: </b>
      </label>
      <input name="userlogin" id="user-login" class="input-field" type="text">
      <!-- span is used to display error message -->
      <span id="msgLoginID" class="errMsg"></span>
   </div>

   <div class="input-group">
      <label for="password" class="input-label">
         <b>Password: </b>
      </label>
      <input name="password" id="password" class="input-field" type="password">
      <!-- span is used to display error message -->
      <span id="msgPass" class="errMsg"></span>
   </div>

   <div class="input-group">
      <label for="user-email" class="input-label">
         <b>Email: </b>
      </label>
      <input name="useremail" id="user-email" class="input-field" type="email">
      <!-- span is used to display error message -->
      <span id="msgEmail" class="errMsg"></span>
   </div>

   <div class="input-group">
      <input type="hidden" name="usercontact" value="0" />
      <input name="usercontact" id="user-contact" class="input-field" type="checkbox" value="1" checked> Contact me
   </div>

   <div class="input-group">
      <label for="user-bdate" class="input-label">
         <b>Birth date: </b>
      </label>
      <input name="userbdate" id="user-bdate" class="input-field" type="date" placeholder="yyyy-mm-dd">
      <!-- span is used to display error message -->
      <span id="msgBdate" class="errMsg"></span>
   </div>

   <!-- the submit input has custom formatting to chance their default appearance. -->
   <div class="input-group">
      <label for="submit-registration" class="submit-label">Register</label>
      <input id="submit-registration" type="submit">
   </div>
</form>