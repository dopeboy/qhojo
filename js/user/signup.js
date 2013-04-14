$(document).ready(function()
{
    
    $('#myForm').validate( {
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
        
    $('#additionalform')
        .ajaxForm({
            success : function (response) 
            {   
                if (response == -1)
                    alert ("Error");
                else
                    window.location = response;
            }
        });

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

