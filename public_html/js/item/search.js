$(document).ready(function()
{    
    $('[rel=tooltip]').tooltip();

    $('input#query').popover('toggle');
    $('input#query').focus();    

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
