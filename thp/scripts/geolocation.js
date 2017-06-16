/* Web Systems and Web Computing Project: Part 3
   Filename: geolocation.js
   Description: script for loading geolocation

   For course: CAS 6WW3
   By: Omer Waseem (graduate student)

   References:

   1) Geolocation code adopted from w3schools.com
   URL: http://www.w3schools.com/html/html5_geolocation.asp
*/

function getLocation() {
   if (navigator.geolocation) {
      // If geolocation object is found, get locations using call back functions
      navigator.geolocation.getCurrentPosition(showPosition, showError);
   } else {
      // Display msg if geolocation object is not found
      $("#geoerror").html("Geolocation is not supported in this browser.");
   }
}

function showPosition(position) {
   $("#latitude").val(position.coords.latitude.toString().substring(0,10));
   $("#longitude").val(position.coords.longitude.toString().substring(0,10));
}

// Geolocation error handling
function showError(error) {
   switch(error.code) {
      case error.PERMISSION_DENIED:
         $("#geoerror").html("Search by range unavailable: geolocation denied by user.");
      break;
      case error.POSITION_UNAVAILABLE:
         $("#geoerror").html("Search by range unavailable.");
      break;
      case error.TIMEOUT:
         $("#geoerror").html("Search by range unavailable: request timed out.");
      break;
      case error.UNKNOWN_ERROR:
         $("#geoerror").html("Search by range unavailable.");
      break;
   }
}