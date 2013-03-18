$(document).ready(function()
{
    // lose focus
    $('.editable').blur(function() 
    {
       //alert($(this).parent().find('.noneditable').attr('id'));
        var g = $(this).parent().find('.noneditable');
        var h = $(this).parent().find('.editPencil');
        
        // if there is text
      if ($(this).val().length > 0)
          {
              // hide the input box
              $(this).hide();
           
              g.text($(this).val());
              g.show();
              h.show();
              
              // unhide the div and put the text in there
          }
    });		
    
    $(".editPencil").click(function() 
    {
        var g = $(this).parent().find('.noneditable');
        var h = $(this).parent().find('.editable');
        
        // hide the div
        $(this).hide();
        g.hide();
        
        // unhide the input box
         h.show();
         h.focus();
          
    });
    
    
    
    
    
        $(".editPencildropdown").click(function() 
    {
        var g = $(this).parent().find('.noneditable');
        var h = $(this).parent().find('.editabledropdown');
        
        // hide the div
        $(this).hide();
        g.hide();
        
        // unhide the input box
         h.show();
         h.focus();
          
    });
    
        // lose focus
    $('.editabledropdown').change(function() 
    {
       //alert($(this).parent().find('.noneditable').attr('id'));
        var g = $(this).parent().find('.noneditable');
        var h = $(this).parent().find('.editPencildropdown');
        
        // if there is text
      if ($(this).val().length > 0)
          {
              // hide the input box
              $(this).hide();
           
              g.text($(this).find(":selected").text());
              g.show();
              h.show();
              
              // unhide the div and put the text in there
          }
          
          
          
          $('#map_canvas').show();
          
                var mapOptions = 
                    {
                   zoom: 13,
                   mapTypeId: google.maps.MapTypeId.ROADMAP
                 };

                 var map = new google.maps.Map(document.getElementById("map_canvas"),mapOptions);
                 var address = $(this).find(":selected").text();

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
    });
    
    
    
   
       var options = { 
        success:       showResponse,  // post-submit callback 
        beforeSubmit:  check
    }; 
    // bind form using 'ajaxForm' 
    $('#myForm').ajaxForm(options); 
    
 
 function check(formData, jqForm, options) 
 { 
    if ($('#uploadedfile1').val() == '' || $('#address').val() == '')
    {
        alert("Missing some fields!");
        return false;
    }

     return true;
 }


     // post-submit callback 
function showResponse(responseText, statusText, xhr, $form)  
{ 
    // for normal html responses, the first argument to the success callback 
    // is the XMLHttpRequest object's responseText property 
 
    // if the ajaxForm method was passed an Options Object with the dataType 
    // property set to 'xml' then the first argument to the success callback 
    // is the XMLHttpRequest object's responseXML property 
 
    // if the ajaxForm method was passed an Options Object with the dataType 
    // property set to 'json' then the first argument to the success callback 
    // is the json data object returned by the server 

 
    if (responseText != null)
        window.location = "/item/post/" + responseText + "/2";
    else
        alert("Error making reservation!");
 }  

    
});
                    
