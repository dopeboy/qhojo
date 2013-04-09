$(document).ready(function()
{	    
    $("#loanslink").click(function()
    {
        $("#borrows").hide();
        $("#borrowslink").css("font-weight",'normal');
        $("#borrowslink").css("text-decoration",'none');
        $("#requests").hide();
        $("#requestslink").css("font-weight",'normal');         
        $("#requestslink").css("text-decoration",'none');
        
        $("#loans").show();
        $("#loanslink").css("font-weight","bold");
        $("#loanslink").css("text-decoration","underline");
    });
    
    $("#borrowslink").click(function()
    {
        $("#loans").hide();
        $("#loanslink").css("font-weight",'normal');
        $("#loanslink").css("text-decoration",'none');        
        $("#requests").hide();
        $("#requestslink").css("font-weight",'normal');          
        $("#requestslink").css("text-decoration",'none');        
        
        $("#borrows").show();
        $("#borrowslink").css("font-weight","bold");
        $("#borrowslink").css("text-decoration","underline");
    });    
    
    $("#requestslink").click(function()
    {
        $("#loans").hide();
        $("#loanslink").css("font-weight",'normal');
        $("#loanslink").css("text-decoration",'none');                
        $("#borrows").hide();
        $("#borrowslink").css("font-weight",'normal');
        $("#borrowslink").css("text-decoration",'none');        
        
        $("#requests").show();
        $("#requestslink").css("font-weight","bold");
        $("#requestslink").css("text-decoration","underline");        
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
  
    $(".accept").click(function()
    {
        // Show ajax
        $('.accept').parent().parent().siblings('.loader').show();
        
        // Disable button
        $(this).attr("class","ui-state-disabled");
        
        $.ajax({
          url: $(this).attr('href'),
          context: this,
          success: function(data)
          {
              if (data == 0)
                  window.location = "/item/accept/" + $(this).closest('tr').attr('itemid') + "/1";
              else
                  {
                      alert("Error");                
                      
                  }
          }
        });
        
        return false;
    });    
    
    $(".ignore").click(function()
    {
        $.ajax({
          url: $(this).attr('href'),
          context: this,
          success: function(data)
          {
              if (data != 0)
                  alert("Error");                
              else
                  {
                      $(this).closest('tr').fadeOut('slow', function() {
                            // Animation complete.
                          });
                  }
          }
        });
        
        return false;
    });  
    
});
