$(document).ready(function()
{    
    $('[rel=tooltip]').tooltip();
    $('input#query').popover('show');
    
    
    if (getURLParameter('query') != 'null')
    {
        $('#query').val(getURLParameter('query'));
    }
    
    if (getURLParameter('location') != 'null')
    {
        $('#location').val(getURLParameter('location'));
    }

});
