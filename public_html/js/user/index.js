$(document).ready(function()
{
    $("form#blurb-1").data('validate_options',
    {
        errorElement: "span",
        errorClass: "text-error reject-error",
        rules:
        {
        }    
    });
    
    // Initialize the jQuery File Upload widget:
    $('#fileupload').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: '/picture/upload/null/1',
        maxNumberOfFiles: 1,
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
        // Clear the user's picture
        if ($("#upload-profile-picture").find('tr.template-download').children('td').children('p.name').children('a').length == 0)
        {
            $('#user-picture').attr('src', '/img/stock_big.png');
            
            $.ajax(
            {
                type: "POST",
                url: '/user/edit/null/250',
                data: null
            });            
        }
        
        else if ($("#upload-profile-picture").find('tr.template-download').children('td').children('p.name').children('a').length == 1)
        {
            var $path = null;
            
            $("#upload-profile-picture").find('tr.template-download').children('td').children('p.name').children('a').each(function(i) 
            { 
                $('#user-picture').attr('src', $(this).attr('href'));
                $path = $(this).attr('download');
            });
            
            $.ajax(
            {
                type: "POST",
                url: '/user/edit/null/200',
                data: {profilepicture: $path}
            });              
        }
            
    });    
   	    
});

function customResponseHandler(responseText)  
{
    // A lender is rejecting a request he/she received
    if (responseText.Action == 'SUBMIT-BLURB')
    {
        $('#blurb-content').text($('textarea#edit-blurb').val());        
        $('#edit-blurb-1').modal('hide');
    }        
}  