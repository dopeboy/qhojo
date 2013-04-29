$(document).ready(function()
{
    
    $( document ).tooltip();
 
 $('#networklist').change(function()
    {
        if ($('#networklist option:selected').attr('email') == '')
        {
            $('#networkemail').removeClass('required');
            $('#networkdynamic').hide();
            $('#submit').prop("disabled", true);
        }
           
       else
       {
           $('#networkemail').addClass('required');
           $('#networkdynamic').show();
           $('#submit').prop("disabled", false);
       }
       
        $('#networkemailextension').text($('#networklist option:selected').attr('email'));
    });
    
    
    $('#myForm').validate( {
   
        errorPlacement: function(error, element) 
        {
          
        }
    });
    
    
    $('#myForm')
        .ajaxForm({
            success : function (response) 
            {   
                if (response >= 0)
                    window.location = "/user/edit/" + response + "/2";
                else
                    alert ("Error");
            }
        });    
    
});