$(document).ready(function()
{
    
	$('#searchform').submit(function() 
	{
		
			window.location.href =  '/item/search/' + $('#search').val();
                        return false;
        });

        
			
});
                    
