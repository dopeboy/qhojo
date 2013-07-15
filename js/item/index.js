$(document).ready(function() 
{ 
     $( document ).tooltip();
     
       var mapOptions = 
           {
          zoom: 13,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
	
        var map = new google.maps.Map(document.getElementById("map_canvas"),mapOptions);
        var address = $('#address').text();

    var geocoder = new google.maps.Geocoder();
    
    geocoder.geocode( { 'address': address}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
       //  alert (results[0].geometry.location);
        map.setCenter(results[0].geometry.location);
        var marker = new google.maps.Marker({
            map: map,
            position: results[0].geometry.location
        });
      } else {
        alert("Geocode was not successful for the following reason: " + status);
      }});
      
     $('#thumbs img').hover(function()
     {
        $('#largeimage').attr('src',$(this).attr('src'));
     });
     
        $('.star').rating(
    {
   
        required: 'hide',
        readOnly: true
        
    });        
 	
}); 
         
