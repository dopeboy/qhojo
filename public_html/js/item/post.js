$(document).ready(function() 
{
    $(".positive-integer").numeric({ decimal: false, negative: false }, function() { alert("Positive integers only"); this.value = ""; this.focus(); });
    
    var urlWithoutParameters = location.protocol + '//' + location.host + location.pathname;
    var $state = urlWithoutParameters.split('/')[6];
    
    if ($state == 0 || $state == null)
    {
        $('#category').change(function() 
        {
            var my_options = 
            { 
                beforeSubmit:  showRequest,  // pre-submit callback 
                success:       showResponse,  // post-submit callback 
                data: {'category-id' : $(this).val()},
                url:       '/product/getbrandsforcategory/',         // override for form's 'action' attribute 
                type:      'post',        
                dataType:  'json'        // 'xml', 'script', or 'json' (expected server response type) 
            }; 

            $(this).ajaxSubmit(my_options);

            // pre-submit callback 
            function showRequest(formData, jqForm, options) 
            { 
            } 

            // post-submit callback 
            function showResponse(responseText, statusText, xhr, $form)  
            {
                $("#brand option[value!=-1]").each(function() { this.remove(); });
                $('#brand').val(-1);

                for (var key in responseText) 
                {
                    $("<option>").text(responseText[key].NAME).attr("value", responseText[key].BRAND_ID).attr("name","brand-id").appendTo("#brand"); 
                }

                // Clear the product dropdown and kill the valid entries
                $("#product").prop("disabled", true);
                $("#product option[value!=-1]").each(function() { this.remove(); });

                $("#brand").removeAttr("disabled");

                $("#submit-product").addClass("disabled");
            }        
        });

        $('#brand').change(function() 
        {
            var options = 
            { 
                beforeSubmit:  showRequest,  // pre-submit callback 
                success:       showResponse,  // post-submit callback 
                data:      {'category-id' : $('#category').val(), 'brand-id' : $(this).val() },
                url:       '/product/getproductsforcategoryandbrand/',         // override for form's 'action' attribute 
                type:      'post',        
                dataType:  'json'        // 'xml', 'script', or 'json' (expected server response type) 
            }; 

            $(this).ajaxSubmit(options); 

            // pre-submit callback 
            function showRequest(formData, jqForm, options) 
            { 
            } 

            // post-submit callback 
            function showResponse(responseText, statusText, xhr, $form)  
            {
                $("#product option[value!=-1]").each(function() { this.remove(); });
                $('#product').val(-1);

                for (var key in responseText) 
                {
                    $("<option>").text(responseText[key].PRODUCT_NAME).attr("value", responseText[key].PRODUCT_ID).attr("name","product-id").appendTo("#product"); 
                }

                $("#product").removeAttr("disabled");
                $("#submit-product").addClass("disabled");
            }        
        });    

        $('#product').change(function() 
        {
            $("#submit-product").removeClass("disabled");
        }); 
    }
    
    else if ($state == 1)
    {
        // Initialize the jQuery File Upload widget:
        $('#fileupload').fileupload({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
            url: '/picture/upload/null/0',
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
            
            $("#upload-item-pictures").find('tr.template-download').children('td').children('p.name').children('a').each(function(i) 
            { 
                $('#item-thumbs').append('<img class=\'thumbnail\' full=\'' +  $(this).attr('href') + '\' src=\'' + $(this).attr('tn') + '\'>');
                $('#item-thumbs').append('<input type="hidden" name=\'file' + '[]' + '\' value=\'' + $(this).attr('download') + '\'>');
            });
            
            if ($("#upload-item-pictures").find('tr.template-download').children('td').children('p.name').children('a').length === 0)
                $('#item-thumbs').hide();
            else
                $('#item-thumbs').show();
            
        }); 
        
        $('.help').tooltip();
        $('.help').tooltip();
    }
}); 

function customResponseHandler(responseText)  
{ 
     window.location = responseText.URL;
} 
  
         