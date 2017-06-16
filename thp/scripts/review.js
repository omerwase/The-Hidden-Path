/* Web Systems and Web Computing Project: Part 3
   Filename: review.js
   Description: functions for handling AJAX submission of user reviews

   For course: CAS 6WW3
   By: Omer Waseem (graduate student)

   Reference: implementation based on AJAX workshop from CAS 6WW3
*/

function insertReviewResponse() {
	if (this.status == 200) {
		response = JSON.parse(this.responseText);
		if (response.status == false) { // error in submitting review
			$("#errorMsg").html("Error:" + response.message + "<br>");
         $("#errorMsg").addClass("errMsg");
		} else { // review submission successful

			// display message and reset input fields
         $("#errorMsg").html("Review submitted!<br><br>");
         $("#errorMsg").removeClass("errMsg");
         $("#userrating").val("5");
         $("#comment").val("");
         $("#noreviews").hide(); // incase this is the first review, hide no reviews message

         // Appends new user review into DOM
         $("#userreviews").append('<div class="user-review">'
                                    +'<hr>'
                                    +'<div itemprop="review" itemscope itemtype="http://schema.org/Review">'
                                       +'<h4>Name: <span itemprop="author">'+response.username+'</span></h4>'
                                       +'<div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">'
                                          +'<meta itemprop="worstRating" content = "1">'
                                          +'<meta itemprop="bestRating" content = "5">'
                                          +'<h4>Rating: <span itemprop="ratingValue">'+response.userrating+'</span></h4>'
                                          +'</div>'
                                       +'<b>Comment:</b> <span itemprop="description">'+response.comment+'</span>'
                                       +'<meta itemprop="datePublished" content="'+response.reviewdate+'"><h5>Date: '+response.reviewdate+'</h5>'
                                       +'<br>'
                                    +'</div>'    
                                 +'</div>');

         // update trail rating with new rating
         $("#trating").html(response.newrating.toString().substring(0,3));
		}
	}
}

function submitReviewForm() {
	request = new XMLHttpRequest(); // create new AJAX request
	request.open("POST", "submitreview.php"); // post review to submitreview.php for response
	request.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	request.onload = insertReviewResponse;

   // post review with trail and user information
	request.send("userrating=" + encodeURIComponent($("#userrating").val()) + "&comment=" + encodeURIComponent($("#comment").val())
               + "&trailid=" + encodeURIComponent($("#trailid").val()) + "&userid=" + encodeURIComponent($("#userid").val())
               + "&username=" + encodeURIComponent($("#username").val()));
}