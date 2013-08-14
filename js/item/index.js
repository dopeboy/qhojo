$(document).ready(function() 
{ 
    $( document ).tooltip();
     
    var mapOptions = 
    {
        zoom: 11,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        streetViewControl: false,
        disableDefaultUI: true
     };

    var map = new google.maps.Map(document.getElementById("map_canvas"),mapOptions);
    var address = $('#location').val();

    var geocoder = new google.maps.Geocoder();

    geocoder.geocode( { 'address': address}, function(results, status) 
    {
        if (status == google.maps.GeocoderStatus.OK) 
        {
            map.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
                map: map,
                position: results[0].geometry.location
            });
        } 

        else 
        {
            alert("Geocode was not successful for the following reason: " + status);
        }
    });
      

     $('.thumbnail').hover(function()
     {
        $('#largeimage').attr('src',$(this).attr('src'));
     });

    $( "#contact-btn" ).click(function() 
    {
        $('#contact-modal').modal('show');
    });
 	


}); 
         