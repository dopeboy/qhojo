$(document).ready(function() 
{ 
    $(".positive-integer").numeric({ decimal: false, negative: false }, function() { alert("Positive integers only"); this.value = ""; this.focus(); });
    
    $( document ).tooltip();
     
    var mapOptions = 
    {
        zoom: 11,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        streetViewControl: false,
        disableDefaultUI: true
     };

     function map()
     {
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
     }
     
     map();
     
    // lose focus
    $('.editable#title').blur(function() 
    {
        var non_editable = $(this).siblings('.non-editable');
        var editable = $(this);
        
        if ($(this).val().length > 0)
        {          
            non_editable.find('.value').text(editable.val());
            editable.hide();
            non_editable.show();
        }
       
    });	

    $(".icon-pencil#title").click(function() 
    {
        var non_editable = $(this).closest('.non-editable');
        var editable = non_editable.siblings('.editable');
        
        editable.show();
        non_editable.hide();
        editable.focus();
    });
    
    /* ----------------------------------------------------------------- */ 
    
    // lose focus
    $('.editable#rate').blur(function() 
    {
        var non_editable = $(this).siblings('.non-editable');
        var editable = $(this);
        
        if ($(this).val().length > 0)
        {          
            non_editable.find('.value').text(editable.val());
            editable.hide();
            editable.siblings('h2').hide();
            non_editable.show();
        }
       
    });	

    $(".icon-pencil#rate").click(function() 
    {
        var non_editable = $(this).closest('.non-editable');
        var editable = non_editable.siblings('.editable');
        
        editable.show();
        editable.siblings('h2').show();
        non_editable.hide();
        editable.focus();
    });    
    
    /* ----------------------------------------------------------------- */ 
    
    // lose focus
    $('.editable#description').blur(function() 
    {
        var non_editable = $(this).siblings('.non-editable');
        var editable = $(this);
        
        if ($(this).val().length > 0)
        {          
            non_editable.find('.value').text(editable.val());
            editable.hide();
            non_editable.show();
        }
       
    });	

    $(".icon-pencil#description").click(function() 
    {
        var non_editable = $(this).closest('.non-editable');
        var editable = non_editable.siblings('.editable');
        
        editable.show();
        non_editable.hide();
        editable.focus();
    });      
    
    /* ----------------------------------------------------------------- */ 
    
    // lose focus
    $('.editable#hold').blur(function() 
    {
        var non_editable = $(this).siblings('.non-editable');
        var editable = $(this);
        
        if ($(this).val().length > 0)
        {          
            non_editable.text(editable.val());
            editable.hide();
            non_editable.show();
            $(".icon-pencil#hold").show();
        }
       
    });	

    $(".icon-pencil#hold").click(function() 
    {
        var non_editable = $(this).parent().siblings('.non-editable');
        var editable = non_editable.siblings('.editable');
        
        editable.show();
        non_editable.hide();
        editable.focus();
        $(this).hide();
    });       
    
    /* ----------------------------------------------------------------- */ 
    
    $(".icon-pencil#zipcode").click(function() 
    {
        var non_editable = $(this).closest('.non-editable');
        var editable = non_editable.siblings('.editable');
        
        editable.show();
        non_editable.hide();
        editable.focus();
    });   
    
    // lose focus
    $('.editable#zipcode').blur(function() 
    {
        var non_editable = $(this).siblings('.non-editable');
        var editable = $(this);
        
        if ($(this).val().length > 0)
        {          
            $("#location").val(editable.val());
            editable.hide();
            non_editable.show();   
            
            map();
        }
    });	
}); 
         