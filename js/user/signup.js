$(document).ready(function()
{

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
                    window.location = "/item/reserve/" + response + "/0";
            }
        });
        
    
   $("#phonenumber").mask("(999) 999-9999",{placeholder:"_"});

//function IsEmail(email) {
//  var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
//  return regex.test(email);
//}

});

