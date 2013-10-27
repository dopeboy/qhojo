$(document).ready(function()
{    
    $('[rel=tooltip]').tooltip();  
    
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

});
