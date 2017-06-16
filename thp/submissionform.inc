<!-- Web Systems and Web Computing Project Part 3
Filename: submissionform.inc
Description: trail submission form for use in submission.php

For course: CAS 6WW3
By: Omer Waseem (graduate student) -->

<form action="submission.php" method="post" enctype="multipart/form-data">
   <h4>Enter trail details below:</h4>

   <!-- input groups below use HTML5 validation. Name, location and description are required fields -->
   <!-- input group below have formatting applied so their labels will stack on smaller screens -->
   <div class="input-group">
      <label for="trail-name" class="input-label">
         <b>Name: </b>
      </label>
      <input id="trail-name" name="trailname" class="input-field" type="text" required>
   </div>

   <div class="input-group">
      <label for="latitude" class="input-label">
         <b>Latitude: </b>
      </label>
      <!-- ensures latitude and longtitude input is two decimal numberes seperated by a comma -->
      <input id="latitude" name="latitude" class="input-field" type="text" pattern="^-?\d*\.?\d*$" required>
   </div>

   <div class="input-group">
      <label for="longitude" class="input-label">
         <b>Longitude: </b>
      </label>
      <!-- ensures latitude and longtitude input is two decimal numberes seperated by a comma -->
      <input id="longitude" name="longitude" class="input-field" type="text" pattern="^-?\d*\.?\d*$" required>
   </div>

   <!-- button for geolocation, calls getLocation() -->
   <div class="input-group">
      <label class="input-label">
      </label>
      <label id="geo-label" for="geolocation" class="button-label">Use Current Location</label>
      <input id="geolocation" type="button" onclick="getLocation()">
   </div>

   <div class="input-group">
      <label for="trail-desc" class="input-label">
         <b>Description: </b>
      </label>
      <textarea id="trail-desc" name="traildesc" class="input-field" rows="5" required></textarea>
   </div>

   <!-- the two inputs below (of type 'file' and 'submit') have custom formatting to chance
   their appearance. The labels directly attached to them function as the buttons themselves
   and the inputs are hidden -->
   <div class="input-group">
      <label class="input-label">
         <b>Image: </b>
      </label>
      <label id="trail_img" for="image-upload" class="button-label">Upload Image</label>
      <input id="image-upload" name="trailimage" type="file"><span id="imagelbl"></span>
   </div>

   <div class="input-group">
      <label class="input-label">
         <b>Video: </b>
      </label>
      <label id="trail_vid" for="video-upload" class="button-label">Upload Video</label>
      <input id="video-upload" name="trailvideo" type="file"><span id="videolbl"></span>
   </div>

   &nbsp;&nbsp;&nbsp;&nbsp;Image/video are optional (max 2MB)<br><br>

   <div class="input-group">
      <label for="submit-trail" class="submit-label">Submit</label>
      <input id="submit-trail" type="submit">
   </div>

</form>