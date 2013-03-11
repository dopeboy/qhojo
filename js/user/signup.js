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
});