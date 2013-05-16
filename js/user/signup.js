$(document).ready(function()
{
    
    $('#networklist').change(function()
    {
        if ($('#networklist option:selected').attr('email') == '')
        {
            $('#networkemail').removeClass('required');
            $('#networkemail').hide();
            $('#atsymbol').hide();
            $('#networkemailextension').hide();
        }
           
       else
       {
           $('#networkemail').addClass('required');
           $('#networkemail').show();
           $('#atsymbol').show();
           $('#networkemailextension').show();
       }
       
        $('#networkemailextension').text($('#networklist option:selected').attr('email'));
    });
    
    $('#myForm').validate({
    
    rules: {
        "terms": {
            required: true,
            minlength: 1
        },
  
        errorPlacement: function ($error, $element) {
    }        
  }
    });
    
        $('#additionalform').validate( {
    rules: {
        "terms": {
            required: true,
            minlength: 1
        },
  errorPlacement: function ($error, $element) {
    }        
  }
    });
    
        $('#creditForm').validate( {
    rules: {
        "terms": {
            required: true,
            minlength: 1
        },
  errorPlacement: function ($error, $element) {
    }        
  }
    });    
    
 
    $('#uploaderframe').attr('src', '/picture/upload/null/1'); 

    $('#myForm')
        .ajaxForm({
            success : function (response) 
            {   
                if (response != -1)
                    window.location = "/item/main";
                else
                    alert ("Error");
            }
        });

    $('#extraFormNonBill')
        .ajaxForm({
            beforeSubmit: function (formData, jqForm, options) 
            {
                if ($('#picture').children().length != 2)
                {
                    alert ("Missing fields.");
                    return false;
                }
                
                else
                {
                    $('#secondloader').show();
                    $('#extrasubmitbutton').attr('disabled','true');
                    return true;
                }
            },
            
            success : function (response) 
            {   
                if (response < 0)
                    alert ("Error: " + response);
                else
                    window.location = response;
            }
        });
        
    $('#creditForm')
        .ajaxForm({
            beforeSubmit: function (formData, jqForm, options) 
            {
                $('#secondloader').show();
                $('#creditsubmitbutton').attr('disabled',true);
            },
            
            success : function (response) 
            {
                if (response < 0)
                {
                    alert ("Error: " + response);
                    $('#creditsubmitbutton').attr('disabled',false);
                    $('#secondloader').hide();
                }
                
                else
                    window.location = response;
            }
        });        
        
        balanced.init('/v1/marketplaces/TEST-MP1UEXukTLr6ID7auHkkCHd6');

        $('#credit-card-form').submit(function()
        {   
            var $form = $('#credit-card-form');

            // collect the data from the form.
            var creditCardData = 
            {
                card_number: $form.find('.cc-number').val(), //'4111 1111 1111 1111',
                expiration_month: $form.find('.cc-em').val(), //'4', 
                expiration_year: $form.find('.cc-ey').val(), // '2014', ,
                security_code: $form.find('cc-csc').val()
            };               
         
            if (!jQuery.isEmptyObject(balanced.card.validate(creditCardData)))
            {
                alert("Card didn't validate, make sure all details were entered in correctly.");
                return false;
            }
            
            $('#secondloader').show();
            $('#debitsubmitbutton').attr('disabled',true);     
         
            balanced.card.create(creditCardData, responseCallbackHandler);
            
            return false;
        }); 

    function responseCallbackHandler(response) 
    {
       switch (response.status) {
         case 400:
             // missing or invalid field - check response.error for details
             alert(response.error);
            $('#secondloader').hide();
            $('#debitsubmitbutton').attr('disabled',false);             
             break;
         case 404:
             // your marketplace URI is incorrect
             alert(response.error);
            $('#secondloader').hide();
            $('#debitsubmitbutton').attr('disabled',false);             
             break;
         case 201:
             // WOO HOO! MONEY!
             // response.data.uri == URI of the card resource
             // you should store this returned card URI to later charge it
             //console.log(response.data);

            $.post('/user/signup/null/5', response.data, function(response) 
            {
                if (response < 0)
                {
                    alert ("Error: " + response);
                    $('#debitsubmitbutton').attr('disabled',false);
                    $('#secondloader').hide();
                }
                
                else
                    window.location = response;
            });
         }
    }
        
    $("#add-pictures").click(function() 
      {
        $( "#dialog-form" ).dialog( "open" );
        return false;
      });
      
    
   $("#phonenumber").mask("(999) 999-9999",{placeholder:"_"});

    $( "#dialog-form" ).dialog({
      autoOpen: false,
      height: 600,
      width: 984,
      modal: true,
       open: function(event, ui) { $(".ui-dialog-titlebar-close", $(this).parent()).hide(); },      
      buttons: {
        "Done": function() {

       $('#picture').empty();
  
   $("#uploaderframe").contents().find('.files').children('tr.template-download').children('td.name').children('a').each(function(i) { 
    
                $('#picture').append('<img id=\'largeimage\' src=\'' + $(this).attr('href') + '\' style=\'max-height: 100px; max-width: 124px\'>');
                

            $('#picture').append('<input type="hidden" name=\'file' + '[]' + '\' value=\'' + $(this).attr('href') + '\' style=\'display:none\'>');
    
 });
        
            $( this ).dialog( "close" );
         
        }
      }
    });

});

