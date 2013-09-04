$(document).ready(function()
{
    $('.collapse').on('hide', function () 
    {
        var $transaction_id = $(this).attr('tid');
        
        $('#collapse-link-' + $transaction_id).html('[+] Show History');
    });
    
    $('.collapse').on('show', function () 
    {
        var $transaction_id = $(this).attr('tid');
        
        $('#collapse-link-' + $transaction_id).html('[-] Hide History');
    });
    
    $("form#reject").data('validate_options',
    {
        errorElement: "span",
        errorClass: "text-error reject-error",
        rules:
        {
        },
        errorPlacement: function(error, element) 
        {
            element.parent().append(error);
        }        
    });
    
    $("form#cancel-lender").data('validate_options',
    {
        errorElement: "span",
        errorClass: "text-error cancel-error",
        rules:
        {
        },
        errorPlacement: function(error, element) 
        {
            element.parent().append(error);
        }        
    });    
    
    $("form#cancel-borrower").data('validate_options',
    {
        errorElement: "span",
        errorClass: "text-error cancel-error",
        rules:
        {
        },
        errorPlacement: function(error, element) 
        {
            element.parent().append(error);
        }        
    });        
});

function customResponseHandler(responseText)  
{ 
    // A lender is rejecting a request he/she received
    if (responseText.Action == 'REJECT')
    {
        $('#' + responseText.Source).find('.requests-table tr#' + responseText.TransactionID).fadeOut("slow");
        $('#reject-' + responseText.TransactionID).modal('hide');
        $('#' + responseText.Source).find('#requests-count').text($('#' + responseText.Source).find('#requests-count').text()-1);
    }
    
    else if (responseText.Action == 'CANCEL')
    {   
        $('#' + responseText.Source).find('.current-transactions-table tr#' + responseText.TransactionID).fadeOut("slow");
        $('#cancel-' + responseText.TransactionID).modal('hide');
        $('#' + responseText.Source).find('#current-count').text($('#'  + responseText.Source).find('#current-count').text()-1);
    }
} 