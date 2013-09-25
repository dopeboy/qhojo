$(document).ready(function() 
{      
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
            console.log("Geocode was not successful for the following reason: " + status);
        }
    });
      

     $('.thumbnail').hover(function()
     {
        $('#largeimage').attr('src',$(this).attr('full'));
     });

    $( "#contact-btn-not-signedin" ).click(function() 
    {
        window.location = '/user/signin/null/100?return=' + window.location.pathname;
    });
    
    
    $("form#contact").data('validate_options',
    {
        errorElement: "span",
        errorClass: "text-error cancel-error",
        rules:
        {
        },
        errorPlacement: function(error, element) 
        {
            element.parent().append(error);
        }        
    });  
 	
});

function customResponseHandler(responseText)  
{ 
    if (responseText.Action == 'CONTACT' && responseText.Status == 'SUCCESS')
    {
        // Fade out existing
        $( "#mh-" + responseText.EntityID + ' h3').text('Message Sent');
        $( "#mb-" + responseText.EntityID ).html('<p>Your message was sent successfuly.</p>');
        $( "#mf-" + responseText.EntityID + ' .btn-primary').remove();
    }

} 
         