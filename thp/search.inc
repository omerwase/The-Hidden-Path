<!-- Web Systems and Web Computing Project Part 3
Filename: search.inc
Description: search form for index.php and results.php

For course: CAS 6WW3
By: Omer Waseem (graduate student) -->

<div id="searchbox">
   <h3>Browse through our collection of trails for your hiking adventures!</h3>
   <form action="results.php" method="get">
      <label for="search"><b>Name:</b>
         <input id="search" name="search" type="text" placeholder="(optional)">
      </label>
      <br><br>
      <!-- range fields are used to determine if user want to search within current location -->
      <span id="rangefields">
         <input type="hidden" name="rangecheck" value="0" />
         <input name="rangecheck" id="rangecheck" type="checkbox" value="1">
         <input type="hidden" name="latitude" id="latitude" value="" />
         <input type="hidden" name="longitude" id="longitude" value="" />
         <b>Within</b>
         <label for="range">
            <select id="range" name="range">
               <option value = "5">5 km</option>
               <option value = "10">10 km</option>
               <option value = "15">15 km</option>
               <option value = "25">25 km</option>
               <option value = "50">50 km</option>
               <option value = "100">100 km</option>
            </select>
         </label>
      </span>
      <br>
      <b>Minimum Rating:</b>
      <label for="rating">
         <select id="rating" name="rating">
            <option value = "1">1</option>
            <option value = "2">2</option>
            <option value = "3">3</option>
            <option value = "4">4</option>
            <option value = "5">5</option>
         </select>
      </label>
      <br><br>
      <label for="object-search" class="search-label">Search</label>
      <input id="object-search" type="submit">
      <br><br><span id="geoerror" class="errMsg"></span><br><br><br>
   </form>
</div><br><br><br><br><br>