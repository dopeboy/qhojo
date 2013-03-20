$(document).ready(function(){

    $('#borrowlink').click(function()
    {
        $('#borrow').show();
        $('#borrowlink').css("font-weight","bold");
        
        $('#loan').hide();
        $('#loanlink').css("font-weight","normal");
    });
    
    $('#loanlink').click(function()
    {
        $('#loan').show();
        $('#loanlink').css("font-weight","bold");
        
        $('#borrow').hide();
        $('#borrowlink').css("font-weight","normal");
    });
    
    $('#borrowlink').click();

});
