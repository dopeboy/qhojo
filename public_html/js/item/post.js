$(document).ready(function() 
{ 
    $(".positive-integer").numeric({ decimal: false, negative: false }, function() { alert("Positive integers only"); this.value = ""; this.focus(); });
     
     $('input#title').popover('show');
     
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
                console.log("Geocode was not successful for the following reason: " + status);
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
    
    
    var options = 
    { 
        beforeSubmit:  showRequest,  // pre-submit callback 
        success:       showResponse,  // post-submit callback 

        // other available options: 
        //url:       url         // override for form's 'action' attribute 
        //type:      type        // 'get' or 'post', override for form's 'method' attribute 
        dataType:  'json'        // 'xml', 'script', or 'json' (expected server response type) 
        //clearForm: true        // clear all form fields after successful submit 
        //resetForm: true        // reset the form after successful submit 

        // $.ajax options can be used here too, for example: 
        //timeout:   3000 
    }; 

    // bind to the form's submit event 
    $('form#post').submit(function() 
    { 
        // inside event callbacks 'this' is the DOM element so we first 
        // wrap it in a jQuery object and then invoke ajaxSubmit 

        $(this).ajaxSubmit(options); 

        // !!! Important !!! 
        // always return false to prevent standard browser submit and page navigation 
        return false; 
    });     
    
    $(document).on(
    {
        mouseenter: function() 
        {
            $('#largeimage').attr('src',$(this).attr('full'));
        }
    }
    , '.thumbnail'); //pass the element as an argument to .on
    
    
    // Initialize the jQuery File Upload widget:
    $('#fileupload').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: '/picture/upload/' + $('#item_id').val() + '/0',
        maxNumberOfFiles: 8,
        autoUpload: true,
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i
    });

    // Enable iframe cross-domain access via redirect option:
    $('#fileupload').fileupload(
        'option',
        'redirect',
        window.location.href.replace(
            /\/[^\/]*$/,
            '/views/picture/cors/result.html?%s'
        )
    );
   
    // Load existing files:
    $('#fileupload').addClass('fileupload-processing');
    $.ajax({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: $('#fileupload').fileupload('option', 'url'),
        dataType: 'json',
        context: $('#fileupload')[0]
    }).always(function () {
        $(this).removeClass('fileupload-processing');
    }).done(function (result) {
        $(this).fileupload('option', 'done')
            .call(this, null, {result: result});
    });   
    
    $('#done').click(function()
    {
        $('#item-thumbs').empty();
        $('#item-picture').find('img').remove();
   
        $("#upload-item-pictures").find('tr.template-download').children('td').children('p.name').children('a').each(function(i) 
        { 
            if (i==0)
                $('#item-picture').append('<img id=\'largeimage\' src=\'' + $(this).attr('href') + '\'>');
            
            $('#item-thumbs').append('<img class=\'thumbnail\' full=\'' +  $(this).attr('href') + '\' src=\'' + $(this).attr('tn') + '\'>');
            $('#item-thumbs').append('<input type="hidden" name=\'file' + '[]' + '\' value=\'' + $(this).attr('download') + '\'>');
    
        });
    });
}); 


function getURLParameter(name) 
{
    name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");  
    var regexS = "[\\?&]"+name+"=([^&#]*)";  
    var regex = new RegExp( regexS );  
    var results = regex.exec( window.location.href ); 
    if( results == null )    return "";  
    else    return results[1];
}

// pre-submit callback 
function showRequest(formData, jqForm, options) 
{ 
    $('#error-banner').hide();
    var $pass = true;

    // jqForm is a jQuery object encapsulating the form element.  To access the 
    // DOM element for the form do this: 
     var formElement = jqForm[0]; 
     
    if ($('.editable:visible:first').length == 1)
    {
        $(document.body).animate(
        {
            'scrollTop':   $('.editable:visible:first').offset().top - $('.navbar-fixed-top').height() - 10
        }, 
        'slow');
        
        $('.editable:visible:first').focus();
        
        $pass = false;
    }
    
    else if ($('#item-thumbs input').length == 0)
    {
        $(document.body).animate(
        {
            'scrollTop':   $('div#add-pictures').offset().top - $('.navbar-fixed-top').height() - 10
        }, 
        'slow');
        
        //$('.editable:visible:first').focus();
        
        $pass = false;        
    }

    if ($pass)
        jqForm.find('button[type=submit]').attr('disabled','disabled');
    
    return $pass; 
} 

// post-submit callback 
function showResponse(responseText, statusText, xhr, $form)  
{ 
    // Houston we got a problem
    if (responseText.Error != null)
    {
        if (responseText.Error.ModalID != null)
        {
            $('#alert-error-modal-' + responseText.Error.ModalID).fadeIn("slow");
            $('#error-message-modal-' + responseText.Error.ModalID).text(responseText.Error.Message);
            $form.find('button[type=submit]').removeAttr('disabled');
        }

        else   
        {
            $('#error-banner').fadeIn("slow");
            $('#error-message').text(responseText.Error.Message);
            $form.find('button[type=submit]').removeAttr('disabled');
            
            $(document.body).animate(
            {
                'scrollTop':   0
            }, 
            'slow');            
        }
    }
    
    else
        window.location = responseText.URL;
}
         