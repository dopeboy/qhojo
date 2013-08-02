$(document).ready(function()
{

    function updateTotal()
    {
        var millisecondsInADay = 24*60*60*1000;
        
        var d1 = new Date($('#dpd1').val());
        var d2 = new Date($('#dpd2').val());
        var numDays = (d2-d1)/millisecondsInADay;
        
        console.log(numDays);
 
        $('#rentalDuration').text(numDays);
        $('#total').text(numDays * $('#rental-rate').text());
    }
    
    var nowTemp = new Date();
    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

    var checkin = $('#dpd1').datepicker({
      onRender: function(date) {
        return date.valueOf() < now.valueOf() ? 'disabled' : '';
      }
    }).on('changeDate', function(ev) 
    {
        
      if (ev.date.valueOf() > checkout.date.valueOf()) {
        var newDate = new Date(ev.date)
        newDate.setDate(newDate.getDate() + 1);
        checkout.setValue(newDate);
        updateTotal();
      }
      checkin.hide();
      $('#dpd2')[0].focus();
    }).data('datepicker');
    var checkout = $('#dpd2').datepicker({
      onRender: function(date) {
        return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
      }
    }).on('changeDate', function(ev) {
      checkout.hide();
      updateTotal();
    }).data('datepicker');

});