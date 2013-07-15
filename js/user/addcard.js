    $(document).ready(function()
{

    
    $('#creditForm')
        .ajaxForm({
            beforeSubmit: function (formData, jqForm, options) 
            {
                $('#secondloader').show();
                $('#creditsubmitbutton').attr('disabled',true);
            },
            
            success : function (response) 
            {
                if (response < 0)
                {
                    //alert ("Error: " + response);
                    alert("Something was wrong with your Paypal information. Try again.")
                    $('#creditsubmitbutton').attr('disabled',false);
                    $('#secondloader').hide();
                }
                
                else
                    window.location = response;
            }
        });        
        
        if (document.domain == "localhost" || document.domain == "staging.qhojo.com")
            balanced.init('/v1/marketplaces/TEST-MP1UEXukTLr6ID7auHkkCHd6');
        else if (document.domain == "qhojo.com")
            balanced.init('/v1/marketplaces/MPX4F8Wx05ETX8BWY4aC00U');

        $('#credit-card-form').submit(function()
        {   
            var $form = $('#credit-card-form');

            // collect the data from the form.
            var creditCardData = 
            {
                card_number: $form.find('.cc-number').val(), //'4111 1111 1111 1111',
                expiration_month: $form.find('.cc-em').val(), //'4', 
                expiration_year: $form.find('.cc-ey').val(), // '2014', ,
                security_code: $form.find('cc-csc').val()
            };               
         
            if (!jQuery.isEmptyObject(balanced.card.validate(creditCardData)))
            {
                alert("Card didn't validate, make sure all details were entered in correctly.");
                return false;
            }
            
            $('#secondloader').show();
            $('#debitsubmitbutton').attr('disabled',true);     
         
            balanced.card.create(creditCardData, responseCallbackHandler);
            
            return false;
        }); 

    function responseCallbackHandler(response) 
    {
       switch (response.status) 
       {
         case 400:
             // missing or invalid field - check response.error for details
             alert(response.error);
            $('#secondloader').hide();
            $('#debitsubmitbutton').attr('disabled',false);             
             break;
         case 404:
             // your marketplace URI is incorrect
             alert(response.error);
            $('#secondloader').hide();
            $('#debitsubmitbutton').attr('disabled',false);             
             break;
         case 402:
            // card declined
            alert("Your card is not valid. Try again.");
            $('#secondloader').hide();
            $('#debitsubmitbutton').attr('disabled',false);             
             break;            
         case 201:
             // WOO HOO! MONEY!
             // response.data.uri == URI of the card resource
             // you should store this returned card URI to later charge it
             //console.log(response.data);

            $.post('/user/addcard/' + $('#userid').val() + '/1', response.data, function(response) 
            {
                if (response < 0)
                {
                    alert ("Error: " + response);
                    $('#debitsubmitbutton').attr('disabled',false);
                    $('#secondloader').hide();
                }
                
                else
                    window.location = '/user/edit/' + $('#userid').val() + '/0';
            });
         }
    }
    
});