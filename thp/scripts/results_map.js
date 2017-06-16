/* Web Systems and Web Computing Project: Part 3
   Filename: result_map.js
   Description: script for loading maps using OpenStreetMap and LeafletJS

   For course: CAS 6WW3
   By: Omer Waseem (graduate student)

   References:
   1) Mapping example adopted from
   URL: https://asmaloney.com/2014/01/code/creating-an-interactive-map-with-leaflet-and-openstreetmap/
*/

function drawMap(markers, map_object, latitude, longitude, z = 9)
{
   // Create map object centered and zoomed to show all results
   var map = L.map(map_object, {
      center: [latitude, longitude],
      zoom: z
   });

   // Map tile from OpenStreemMap using subdomains
   L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
      subdomains: ['a','b','c']
   }).addTo( map );

   // Markers added with popups and links to individual_sample page
   for (var i = 0; i < markers.length; i++) {
      L.marker([markers[i].latitude, markers[i].longitude])
         .bindPopup('<div class="map-marker"><a href="trail.php?ID=' + markers[i].id + '" target="_blank"><img class="image-size" src="' + markers[i].image_url + '" alt="' + markers[i].name + '"><br>' + markers[i].name + '</a></div>')
         .addTo(map);
   }
}

