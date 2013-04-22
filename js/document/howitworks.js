$(document).ready(function(){

    $('#borrowlink').click(function()
    {
        $('#borrow').show();
        $('#borrowlink').css("font-weight","bold");
        $('#borrowlink').css("text-decoration","underline");
        
        $('#loan').hide();
        $('#loanlink').css("font-weight","normal");
        $("#loanlink").css("text-decoration",'none');
    });
    
    $('#loanlink').click(function()
    {
        $('#loan').show();
        $('#loanlink').css("font-weight","bold");
        $("#loanlink").css("text-decoration",'underline');
        
        $('#borrow').hide();
        $('#borrowlink').css("font-weight","normal");
         $("#borrowlink").css("text-decoration",'none');
    });
    
    $('#borrowlink').click();

});
