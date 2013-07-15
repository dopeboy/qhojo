$(document).ready(function()
{
    
	$('#searchform').submit(function() 
	{
            window.location.href =  '/item/search?query=' + $('#search').val();
            return false;
        });

        
        
        
			
});
                    
