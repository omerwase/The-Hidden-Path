/* Web Systems and Web Computing Project: Part 3
   Filename: validation.js
   Description: validation script for registration page

   For course: CAS 6WW3
   By: Omer Waseem (graduate student)
*/

// Validates user input from registration.html
// All error messages are displayed in-form using span element
function validate(form){
   var flag = true;

   // Check if first name is entered and it matches correct formatting
   // Displays error message in span or clears span based on validation
   if (form.firstname.value == "") {
      $("#msgFirstName").html(" &nbsp;missing first name");
      flag = false;
   } else {
      if (!verifyUsername(form.firstname.value)){
         $("#msgFirstName").html(" &nbsp;can only contains letters");
         flag = false;
      } else {
         $("#msgFirstName").html("");
      }
   }

   // Check if last name is entered and it matches correct formatting
   // Displays error message in span or clears span based on validation
   if (form.lastname.value == "") {
      $("#msgLastName").html(" &nbsp;missing last name");
      flag = false;
   } else {
      if (!verifyUsername(form.lastname.value)){
         $("#msgLastName").html(" &nbsp;can only contains letters");
         flag = false;
      } else {
         $("#msgLastName").html("");
      }
   }

   // Check if userlogin is entered and matches correct formatting
   // Displays error message in span or clears span based on validation
   if (form.userlogin.value == "") {
      $("#msgLoginID").html(" &nbsp;missing user login");
      flag = false;
   } else {
      if(!verifyLogin(form.userlogin.value)){
         $("#msgLoginID").html(" &nbsp;only letters or numbers allowed");
         flag = false;
      } else {
         $("#msgLoginID").html("");
      }
   }

   // Check if password is entered and matches correct formatting
   // Displays error message in span or clears span based on validation
   if (form.password.value == "") {
      $("#msgPass").html(" &nbsp;missing password");
      flag = false;
   } else {
      if(!verifyPassword(form.password.value)){
         $("#msgPass").html(" &nbsp;must contains at least 8 characters");
         flag = false;
      } else {
         $("#msgPass").html("");
      }
   }

   // Check if useremail is entered and matches correct formatting
   // Displays error message in span or clears span based on validation
   if (form.useremail.value == "") {
      $("#msgEmail").html(" &nbsp;missing user email");
      flag = false;
   } else {
      if (!verifyEmail(form.useremail.value)) {
         $("#msgEmail").html(" &nbsp;incorrect email format");
         flag = false;
      } else {
         $("#msgEmail").html("");
      }
   }

   // Check if userbday is entered and matches correct formatting.
   // Displays error message in span or clears span based on validation
   if (form.userbdate.value == "") {
      $("#msgBdate").html(" &nbsp;missing user birth date");
      flag = false;
   } else {
      if (!verifyDate(form.userbdate.value)) {
         $("#msgBdate").html(" &nbsp;incorrect birth date");
         flag = false;
      } else {
         $("#msgBdate").html("");
      }
   }

   return flag;
}

// Validates that username only contains letters
function verifyUsername(str) {
   var patt = /^[A-z]+$/;
   return patt.test(str);
}

// Validates userlogin only contains letters and numbers
function verifyLogin(str) {
   var patt = /^[A-z0-9]+$/;
   return patt.test(str);
}

// Validates password must be at least 8 characters
function verifyPassword(str) {
   var patt = /^.{8,}$/;
   return patt.test(str);
}

// Validates correct email formatting
function verifyEmail(str) {
   var patt = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,})+$/;
   return patt.test(str);
}

// Validates correct date formatting in the form of yyyy-mm-dd or yyyy/mm/dd
function verifyDate(str) { 
   var patt = /^(18|19|20)\d\d[-\/](0[1-9]|1[012])[-\/](0[1-9]|[12][0-9]|3[01])$/g;
   return patt.test(str);
}


