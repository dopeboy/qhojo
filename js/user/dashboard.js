$(document).ready(function()
{	    
    $("#loanscurrentlink").click(function()
    {
        $("#pastloans").hide();
         $("#loanspastlink").css("font-weight",'normal');
        
        $("#currentloans").show();
        $("#loanscurrentlink").css("font-weight","bold");
    });
    
    $("#loanspastlink").click(function()
    {
        $("#currentloans").hide();
        $("#loanscurrentlink").css("font-weight",'normal')
        
        $("#pastloans").show();
        $("#loanspastlink").css("font-weight","bold");
    });    
    
    $("#borrowscurrentlink").click(function()
    {
        $("#pastborrows").hide();
         $("#borrowspastlink").css("font-weight",'normal');
        
        $("#currentborrows").show();
        $("#borrowscurrentlink").css("font-weight","bold");
    });
    
    $("#borrowspastlink").click(function()
    {
        $("#currentborrows").hide();
        $("#borrowscurrentlink").css("font-weight",'normal')
        
        $("#pastborrows").show();
        $("#borrowspastlink").css("font-weight","bold");
    });  
    
     $(function() {
    $( ".menu" ).menu();
  });
  
  $('.menubutton').click(function()
  {
     $(this).siblings("ul").show().focus();
     
  });
  
    $('.menu').blur(function()
  {
     $(this).hide();
  });
});
