$(document).ready(function()
{    
    $('[rel=tooltip]').tooltip();
    
    if (getURLParameter('query') != 'null')
    {
        $('#query').val(getURLParameter('query'));
    }
    
    if (getURLParameter('location') != 'null')
    {
        $('#location').val(getURLParameter('location'));
    }

});
