   $(document).ready(function()
{
    
   
   $('.borough').hover(function () 
    {
        //alert($(this).attr('boroughid'));
        $('.neighborhoods').hide();
        //$("#boroughid" + $(this).attr('boroughid') + ".neighborhood").show();
        //alert('.neighborhood[boroughid="' + $(this).attr('boroughid') + '"]');
        $('.neighborhoods[boroughid="' + $(this).attr('boroughid') + '"]').show();
        
       //$('.neighborhood[boroughid="0"]');
    });
    
 //alert(window.location.pathname);
var splittedURL = window.location.pathname.split(/\/+/g);

var state = splittedURL[4];

        
if (state == 1)
    {
        var neighborhoodID = splittedURL[3];
        var neighborhood = $('.neighborhood[neighborhoodid="' + neighborhoodID + '"]');
        var boroughID = neighborhood.parent().attr('boroughid');

        $('.neighborhoods[boroughid="' + boroughID + '"]').show();      
        neighborhood.css("font-weight", "bold");
    }
    
    else if (state == 2)
        {
            var boroughID = splittedURL[3];
            $('.neighborhoods[boroughid="' + $(this).attr('boroughid') + '"]').show();
        }
    
    



    
    
});