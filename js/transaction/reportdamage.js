$(document).ready(function()
{
    // Initialize the jQuery File Upload widget:
    $('#fileupload').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: '/picture/upload/' + $('#transaction_id').val() + '/2',
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
        $('#num-upload-pictures').text($("#upload-damage").find('tr.template-download').children('td').children('p.name').children('a').length);
    });
    
    $("form#reportdamage").data('validate_options',
    {
        ignore: "",
        errorElement: "p",
        errorClass: "text-error",
        rules:
        {
        },
        errorPlacement: function(error, element) 
        {
            if (element.attr("name") == "damage-option")
            {
                error.insertAfter("#rate-damage");
            }
            
            else
            {
                error.insertAfter(element);
            }
        }        
    });
    
});

function customResponseHandler(responseText)  
{ 
     window.location = responseText.URL;
} 
    