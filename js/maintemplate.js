$(document).ready(function()
{
    
	$('#searchform').submit(function() 
	{
            window.location.href =  '/item/search/' + $('#search').val() + "/0";
            return false;
        });

        
        
        
			
});
                    
