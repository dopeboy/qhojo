$(document).ready(function() 
{
        var price =  parseFloat($("#price").text());
        var duration = parseFloat($("#duration").val());
        $("#total").text(String(price*duration));
        
    $("#duration").change(function()
    {
        var price =  parseFloat($("#price").text());   
        var duration = parseFloat($("#duration").val());
        $("#total").text(String(price*duration));
    });
    
 	$('#form').ajaxForm(function(output) 
	{
		if (output == 0)
                {
                        alert("Item reserved");
			window.location.href = "/user/dashboard/";
                }
                
		else
			alert("Error");
                    

	});    
});