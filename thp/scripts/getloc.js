/* Web Systems and Web Computing Project: Part 3
   Filename: geolocation.js
   Description: get location on document load to avoid callback delay on search submission

   For course: CAS 6WW3
   By: Omer Waseem (graduate student)*/

$(document).ready(function() {
   getLocation(); // get user location for searching by range

   // only show geo error message if rangecheck is not checked
   if ($("#rangecheck").is(":checked")){
      $("#geoerror").show();
   } else {
      $("#geoerror").hide();
   }
   $("#rangecheck").change(function() {
         $("#geoerror").toggle();
   });
});