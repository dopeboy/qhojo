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
    
    $("form#contact").data('validate_options',
    {
        errorElement: "p",
        errorClass: "text-error",
        rules:
        {
        }
         
    });  
    
    function popupwindow(url, title, w, h) 
    {
      var left = (screen.width/2)-(w/2);
      var top = (screen.height/2)-(h/2);
      var aa = window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
      aa.focus();
      return aa;
    } 

    $(".share.twitter").click(function() 
    {
        popupwindow("http://twitter.com/share?url=/&text=" + $(this).attr('text'), 'Share on Twitter', 500, 500);
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
        $('#' + responseText.Source).find('#open-requests-count').text($('#' + responseText.Source).find('#open-requests-count').text()-1);
    }
    
    else if (responseText.Action == 'CANCEL')
    {   
        $('#' + responseText.Source).find('.current-transactions-table tr#' + responseText.TransactionID).fadeOut("slow");
        $('#cancel-' + responseText.TransactionID).modal('hide');
        $('#' + responseText.Source).find('#current-count').text($('#'  + responseText.Source).find('#current-count').text()-1);
    }  
    
    else if (responseText.Action == 'CONTACT' && responseText.Status == 'SUCCESS')
    {
        // Fade out existing
        $( "#mh-" + responseText.EntityID + ' h3').text('Message Sent');
        $( "#mb-" + responseText.EntityID + '.modal-body').html('<p>Your message was sent successfuly.</p>');
        $( "#mf-" + responseText.EntityID + ' .btn-primary').remove();
    }    
    
    else if (responseText.Action == 'DEACTIVATE' && responseText.Status == 'SUCCESS')
    {
        $('#deactivate-' + responseText.ItemID).modal('hide');
        
        $('.dashboard-section#inactive-items').find('tr#no-items').remove();
        $('.dashboard-section#active-items').find('tr#' + responseText.ItemID).appendTo($('.dashboard-section#inactive-items').find('table.inactive-items-table'));
        
        if ($('table.active-items-table tr').length == 1)
            $('table.active-items-table').append('<tr id=\'no-items\'><td colspan="5"><i>No items</i></td></tr>');
        
        $('.dashboard-section#active-items').find('#active-count').text(parseInt($('.dashboard-section#active-items').find('#active-count').text())-1);
        $('.dashboard-section#inactive-items').find('#inactive-count').text(parseInt($('.dashboard-section#inactive-items').find('#inactive-count').text())+1);
    }     
    
    else if (responseText.Action == 'ACTIVATE' && responseText.Status == 'SUCCESS')
    {
        $('#activate-' + responseText.ItemID).modal('hide');
        
        $('.dashboard-section#active-items').find('tr#no-items').remove();
        $('.dashboard-section#inactive-items').find('tr#' + responseText.ItemID).appendTo($('.dashboard-section#active-items').find('table.active-items-table'));
        
        if ($('table.inactive-items-table tr').length == 1)
            $('table.inactive-items-table').append('<tr id=\'no-items\'><td colspan="5"><i>No items</i></td></tr>');
        
        $('.dashboard-section#active-items').find('#active-count').text(parseInt($('.dashboard-section#active-items').find('#active-count').text())+1);
        $('.dashboard-section#inactive-items').find('#inactive-count').text(parseInt($('.dashboard-section#inactive-items').find('#inactive-count').text())-1);
    }          
} 