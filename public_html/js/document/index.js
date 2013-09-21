$(document).ready(function()
{
    $('a.hiwlink').click(function(e)
    {
        e.preventDefault();
        
        // Are we coming from a different peage?
        $(document.body).animate(
        {
            'scrollTop':   $($(this).attr('href').substring(1)).offset().top - $('.navbar-fixed-top').height() - 10
        }, 
        'slow');
    
    });
    
    $('[rel=tooltip]').tooltip();

});
