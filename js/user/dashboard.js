/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */




$(document).ready(function()
{
//$('.show-hide-history').on('click', function(e) {
//    e.preventDefault();
//    var $this = $(this);
//    var $collapse = $this.closest('.collapse-group').find('.collapse');
//    $collapse.collapse('toggle');
//});


    $('.collapse').on('hide', function () 
    {
        var $transaction_id = $(this).attr('tid');
        
        $('#collapse-link-' + $transaction_id).html('[+] Show History');
    });
    
    $('.collapse').on('show', function () 
    {
        var $transaction_id = $(this).attr('tid');
        
        $('#collapse-link-' + $transaction_id).html('[-] Hide History');
    })
    


});