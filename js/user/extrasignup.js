$(document).ready(function() 
{ 
    var $state = window.location.href.split('/')[6];
    
    if ($state == 100)
    {
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
            $('#uploaded-profile-picture').find('*').not('#add-pictures').not('#upload-picture-btn').remove();

            $("#upload-profile-picture").find('tr.template-download').children('td').children('p.name').children('a').each(function(i) 
            { 
                $('#uploaded-profile-picture').append('<img id=\'largeimage\' src=\'' + $(this).attr('href') + '\'>');
                $('#uploaded-profile-picture').append('<input type="hidden" name=\'profile-picture' + '\' value=\'' + $(this).attr('download') + '\'>');

            });

            if ($("#upload-profile-picture").find('tr.template-download').children('td').children('p.name').children('a').length == 1)
            {
                $("#profile-picture-submit").removeClass('disabled');
            }

            else
            {
                $("#profile-picture-submit").addClass('disabled');
            }
        });
    }
    
    else if ($state == 500)
    {
        $("form#blurb").data('validate_options',
        {
            errorElement: "p",
            errorClass: "text-error",
            rules:
            {
                blurb:"required"
            }
        });          
    }
    
    else if ($state == 200)
    {
        $("#phonenumber").mask("(999) 999-9999",{placeholder:"_"});
        
        $("form#phone-verify").data('validate_options',
        {
            errorElement: "span",
            errorClass: "text-error phone",
            rules:
            {
                phonenumber:"required"
            }
        });    
        
        $("form#phone-verification-code").data('validate_options',
        {
            errorElement: "span",
            errorClass: "text-error phone",
            rules:
            {
                verificationcode:"required"
            }
        });         
    }
    
    else if ($state == 300)
    {
        $("form#paypal").data('validate_options',
        {
            errorElement: "span",
            errorClass: "text-error",
            rules:
            {
                firstname:"required",
                lastname:"required",                                  
                email:
                {
                    required:true,
                    email: true
                }
            }
        });          
    }
    
    else if ($state == 400)
    {
        $("#expiration-date").mask("99/9999",{placeholder:"_"});
        
        $("form#credit-card").data('validate_options',
        {
            errorElement: "p",
            errorClass: "text-error",
            rules:
            {
                "cc-number":"required",
                "expiration-date":"required",
                "csc":"required"
            }
        });   

        // bind to the form's submit event 
        $('form#credit-card').submit(function() 
        { 
            balanced.init($('#bp-mp-uri').val());
            var $form = $('form#credit-card');
            $('#error-banner').hide();
            var $pass = true;
     
            $form.validate($form.data('validate_options'));
            $pass = $form.valid();

            if ($pass)
            {
                // collect the data from the form.
                var creditCardData = 
                {
                    card_number: $form.find('#cc-number').val(), //'4111111111111111',
                    expiration_month: $form.find('#expiration-date').val().split('/')[0], //'04', 
                    expiration_year: $form.find('#expiration-date').val().split('/')[1], // '2014', ,
                    security_code: $form.find('#csc').val()
                };               

                if (!jQuery.isEmptyObject(balanced.card.validate(creditCardData)))
                {
                    $('#error-banner').fadeIn("slow");
                    $('#error-message').text("Invalid credit card information. Please try again.");                        
                    $pass = false;
                }

                if ($pass)
                {
                    balanced.card.create(creditCardData, 

                    function(response)
                    {
                        switch (response.status) 
                        {
                            case 400:
                                // missing or invalid field - check response.error for details
                                $('#error-banner').fadeIn("slow");
                                $('#error-message').text("Missing or invalid field." + response.error);            
                                $pass = false;
                                break;
                            case 404:
                                // your marketplace URI is incorrect
                                $('#error-banner').fadeIn("slow");
                                $('#error-message').text("Marketplace URI is bad.");     
                                $pass = false;
                                break;
                            case 402:
                                // card declined
                                $('#error-banner').fadeIn("slow");
                                $('#error-message').text("Your card was declined."); 
                                $pass = false;
                                break;         
                            case 201:
                                $.ajax(
                                {
                                    type: "POST",
                                    url: '/user/extrasignup/null/401',
                                    data: {"card-uri" : response.data.uri},
                                    success: function(responseText) 
                                    {
                                        if (responseText.Error != null)
                                        {
                                            $('#error-banner').fadeIn("slow");
                                            $('#error-message').text(responseText.Error.Message);
                                            $form.find('button[type=submit]').removeAttr('disabled');
                                        }

                                        else
                                            window.location = responseText.URL;
                                    },
                                    dataType: 'json'
                                });
                                break;
                          }
                    });
                }
            }
                  
            if ($pass)
                $form.find('button[type=submit]').attr('disabled','disabled');
            
            return false; 
        }); 

        function processSubmit($form, options)
        {

        }
        
        function showCCRequest(formData, jqForm, options) 
        { 
            
        } 

        // post-submit callback 
        function showCCResponse(responseText, statusText, xhr, $form)  
        { 
            // Houston we got a problem
            if (responseText.Error != null)
            {
                $('#error-banner').fadeIn("slow");
                $('#error-message').text(responseText.Error.Message);
                $form.find('button[type=submit]').removeAttr('disabled');
            }

            else
                customResponseHandler(responseText);
        }

    }
    

}); 

function customResponseHandler(responseText)  
{ 
    // Show the verification code box, show the tooltip
    if (responseText.Action == "VERIFY-PHONE" && responseText.Status == "OK")
    {
        $('#phone-verification-code').show();
        $('#verificationcode').tooltip('show');
        
    }

    else
        window.location = responseText.URL;
} 