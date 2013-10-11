$(document).ready(function()
{    
    $('[rel=tooltip]').tooltip();
    $('input#query').focus();  
    
    if ($.cookie('seenSearch'))
    {
        $('input#query').popover('hide');
        $.cookie('seenSearch', true);    
    }
    
    else 
    {
        $('input#query').popover('toggle');        
        $.cookie('seenSearch', true);   
    }

    // lose focus
    $('input#query').blur(function() 
    {
        $('input#query').popover('destroy');
    });	
    
    if (getURLParameter('query') != 'null')
    {
        $('#query').val(getURLParameter('query'));
    }
    
    if (getURLParameter('location') != 'null')
    {
        $('#location').val(getURLParameter('location'));
    }

});
