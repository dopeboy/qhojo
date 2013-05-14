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
    
   
        
			
});
                    
