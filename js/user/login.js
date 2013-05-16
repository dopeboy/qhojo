$(document).ready(function()
{
    $('#myForm')
        .ajaxForm({
            beforeSubmit: function (formData, jqForm, options) 
            {
                $('#secondloader').show();
                $('#submitbutton').attr('disabled',true);
                return true; 
            },

            success : function (response) 
            {   
                if (response < 0)
                {
                    $('#secondloader').hide();
                    $('#submitbutton').attr('disabled',false);
                    alert ("Invalid login. Error code: " + response);
                }
                    
                else
                    window.location = response;
            }
        });
});
