$(document).ready(function() 
{
    // Initialize the jQuery File Upload widget:
    $('#fileupload').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: '/picture/upload/' + $('#product_id').val() +  '/3',
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
        $('#done').click();
    });   

    $('#done').click(function()
    {
        $('#item-thumbs').empty();

        $("#upload-product-pictures").find('tr.template-download').children('td').children('p.name').children('a').each(function(i) 
        { 
            $('#item-thumbs').append('<img class=\'thumbnail\' full=\'' +  $(this).attr('href') + '\' src=\'' + $(this).attr('tn') + '\'>');
            $('#item-thumbs').append('<input type="hidden" name=\'file' + '[]' + '\' value=\'' + $(this).attr('download') + '\'>');

        });
    }); 

  
}); 

function customResponseHandler(responseText)  
{ 
     window.location = responseText.URL;
} 
  
         