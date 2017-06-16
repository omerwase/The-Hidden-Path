# The-Hidden-Path
Website developed using PHP with MySQL as part of my M.Eng course: Web Systems and Web Computing. Users can search for hiking trails based on geolocation. Logged in users can submit hiking trails and reviews.

Highlights:
1) The website was hosted on AWS EC2 and used AWS S3 bucket for storage of user submitted files.
2) All website components were served over HTTPS (SSL/TLS). Any HTTP request is redirected by the apache server to HTTPS.
3) User passwords are salted and hashed in the database.
4) Database is always queried through prepared statements.
5) All user inputs submitted to the server are validated on the server-side, as well as client-side.
6) Following addons are implemenated:
	-	Metadata and microdata (dynamically generated for certian pages ie trails.php)
	-	User video submission to S3 bucket (submission.php)
	-	User reviews are submitted through AJAX (trail.php and review.js). The trail page is updated through AJAX to reflect the new review and user rating. The overall trail rating is also re-calcuated and updated through AJAX.
7) conn.php contains DB connection information and AWS S3 bucket keys, that have been removed in this submission.

All materials (images, icons, video) used in this project are free for use.
jQuery, OpenStreetMap and LeafletJS libraries are linked through CDN. All other files are included, including datamodel.sql.
