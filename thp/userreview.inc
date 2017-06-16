<!-- Web Systems and Web Computing Project Part 3
Filename: userreview.inc
Description: user review form for trail.php

For course: CAS 6WW3
By: Omer Waseem (graduate student) -->

<form>
   <div class="input-group">
      <label for="userrating" class="input-label">
         <b>Rating: </b>
      </label>
      <select id="userrating" name="userrating" class="input-field">
         <option value = "5">5</option>
         <option value = "4">4</option>
         <option value = "3">3</option>
         <option value = "2">2</option>
         <option value = "1">1</option>
      </select>
   </div>
   <div class="input-group">
      <label for="comment" class="input-label">
         <b>Comment: </b>
      </label>
      <textarea id="comment" name="comment" class="input-field" rows="5" required></textarea>
   </div>
   <div class="input-group">
      <button id="reviewbutton" type="button" class="submit-label" onclick="submitReviewForm();">Submit</button>
   </div><br>
</form>