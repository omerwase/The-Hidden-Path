# The-Hidden-Path
Website built using PHP with MySQL as part of my M.Eng course: Web Systems and Web Computing. Users can search for hiking trails based on geolocation. Logged in users can submit hiking trails and reviews.

This website is intended to be a collection of hiking trails that users can submit and search for.
All materials (images, icons, video) used in this project are free for use.
jQuery, OpenStreetMap and LeafletJS libraries are linked through CDN. All other files are included, including datamodel.sql.

Note: the search page (index.php) has a checkbox followed by 'Within' and a drop down list with distances. Checking this box will use the user's current location to show trails within the specified distance. If user geolocation is not available, an error message is displayed and distance is not used to filter on trails.

Submission Highlights:
1) Assessment criteria stated that user registration should contain a numeric element. I did not feel that a numeric element fit common user registration criteria, so instead I have included a checkbox element, asking the user if they wish to be contacted through provided email. 
2) The website is hosted on AWS EC2 and uses AWS S3 bucket for storage of user submitted files.
3) All website components are served over HTTPS (SSL/TLS). Any HTTP request is redirected by the apache server to HTTPS.
4) User passwords are salted and hashed in the database.
5) Database is always queried through prepared statements.
6) All user inputs submitted to the server are validated on the server-side, as well as client-side.
7) All Addon tasks are completed:
	-	Metadata and microdata (dynamically generated for certian pages ie trails.php)
	-	User video submission to S3 bucket (submission.php)
	-	User reviews are submitted through AJAX (trail.php and review.js). The trail page is updated through AJAX to reflect the new review and user rating. The overall trail rating is also re-calcuated and updated through AJAX.
8) conn.php contains DB connection information and AWS S3 bucket keys, that have been removed in this submission.
