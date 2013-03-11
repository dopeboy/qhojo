$(document).ready(function() 
{ 
	// bind 'myForm' and provide a simple callback function 


    $('#myForm').ajaxForm(function(output) 
    {
        if (output == 0)
            window.location=2;
        
        else
            alert ("Error submitting feedback");

    });
        
   $('.star').rating(
    {
        required: 'hide',
        callback: function(value)
        {
            $('#zerostub').remove();
        }        
    });        
}); 
                    
