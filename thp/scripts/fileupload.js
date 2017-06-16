/* Web Systems and Web Computing Project: Part 3
   Filename: fileupload.js
   Description: functions to handle file uploads for submission.php

   For course: CAS 6WW3
   By: Omer Waseem (graduate student)
*/

$(document).ready(function() {
   // display image submitted message and hide upload button
   $("#image-upload").change(function(){
            $("#imagelbl").html("Image submitted");
            $("#trail_img").hide();
   });

   // display video submitted message and hide upload button
   $("#video-upload").change(function(){
            $("#videolbl").html("Video submitted");
            $("#trail_vid").hide();
   });
});
