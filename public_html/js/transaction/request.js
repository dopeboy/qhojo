$(document).ready(function()
{

    function updateTotal()
    {
        var millisecondsInADay = 24*60*60*1000;
        
        var d1 = new XDate($('#date-start').val());
        var d2 = new XDate($('#date-end').val());
        var numDays = d1.diffDays(d2);
 
        $('#rentalDuration').text(numDays);
        $('#total').text(numDays * $('#rental-rate').text());
        
        $('#date').val(numDays);
    }
    
    var nowTemp = new Date();
    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

    var checkin = $('#date-start').datepicker({
      onRender: function(date) {
        return date.valueOf() < now.valueOf() ? 'disabled' : '';
      }
    }).on('changeDate', function(ev) 
    {
        
      if (ev.date.valueOf() >= checkout.date.valueOf()) {
        var newDate = new Date(ev.date)
        newDate.setDate(newDate.getDate() + 1);
        checkout.setValue(newDate);
      }
       updateTotal();
      checkin.hide();
      $('#date-end')[0].focus();
    }).data('datepicker');
    
    var checkout = $('#date-end').datepicker({
      onRender: function(date) {
        return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
      }
    }).on('changeDate', function(ev) {
      checkout.hide();
      updateTotal();
    }).data('datepicker');
    
    jQuery.validator.addMethod("nonNull", function(value, element) 
    {
        return value != '';
    }, 
    "Please specify a date");

    $("form#request").data('validate_options',
    {
        ignore: "",
        errorElement: "span",
        errorClass: "text-error",
        rules:
        {
            date:
            {
                number:true,
                required:true
            }
        }
    });


});

function customResponseHandler(responseText)  
{ 
     window.location = responseText.URL;
} 
         