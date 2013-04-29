$(document).ready(function()
{ 
    
    
    
    $( document ).tooltip();
 
    
    $('#uploaderframe').attr('src', '/picture/upload/null/0');  
    
            $('.star').rating(
    {
   
        required: 'hide',
        readOnly: true
        
    });      
    
    
    // lose focus
    $('.editable').blur(function() 
    {
        var g = $(this).parent().find('.noneditable');
        var h = $(this).parent().find('.editPencil');
        
        // if there is text
      if ($(this).val().length > 0)
          {
            if ($(this).attr('id') == 'deposit' && $(this).val() > 500)
            {
                alert("As of now, qhojo is not taking items worth more than $500.");
                $(this).val('');
            }
            
            else
            {
              // hide the input box
              $(this).hide();
           
              g.text($(this).val());
              g.show();
              h.show();
            } 
              // unhide the div and put the text in there
          }
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
    
    
    $(document).on(
    {
        mouseenter: function() 
        {
                $('#largeimage').attr('src',$(this).attr('src'));
        }
    }
    , '#thumbs img'); //pass the element as an argument to .on


    $( "#dialog-form" ).dialog({
      autoOpen: false,
      height: 600,
      width: 984,
      modal: true,
       open: function(event, ui) { $(".ui-dialog-titlebar-close", $(this).parent()).hide(); },
      buttons: {
        "Done": function() {

    $('#thumbs').empty();
   $('#picture').find('img').remove();
   
$("#uploaderframe").contents().find('.files').children('tr.template-download').children('td.name').children('a').each(function(i) { 
    
        if (i==0)
            {
                $('#picture').append('<img id=\'largeimage\' src=\'' + $(this).attr('href') + '\' style=\'max-height: 500px; max-width: 640px\'>');
                
            }
    
            $('#thumbs').append('<img src=\'' + $(this).attr('href') + '\' style=\'height: 50px\'>');
            $('#thumbs').append('<input type="hidden" name=\'file' + '[]' + '\' value=\'' + $(this).attr('href') + '\' style=\'display:none\'>');
    
        });
        
            $( this ).dialog( "close" );
         
        }
      }
    });
 

 
    $("#add-pictures").click(function() 
      {
        $( "#dialog-form" ).dialog( "open" );
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
//           function getImages()
// {
//     result = [];
//     $('#thumbs').children('img').each(function(i) { result.push({file:$(this).attr('src')}); });
//     return JSON.stringify(result);
// }

         var queryString = $.param(formData); 
 
    // jqForm is a jQuery object encapsulating the form element.  To access the 
    // DOM element for the form do this: 
    // var formElement = jqForm[0]; 


    
    if ($('#address').val() == '' || $('#thumbs').children().length == 0 || $('#rate').val() == '' || $('#deposit').val() == ''  || $('#desc').text() == '')
    {
        alert("Missing some fields!");
        return false;
    }

    $('#loading').show();
    $('#submit').prop("disabled", true);
     
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

 
    if (responseText != null && responseText != -1)
        window.location = "/item/post/" + responseText + "/2";
    else
        alert("Error making post!");
 }  


 
    
});
                    
