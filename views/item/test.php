<!--<script type="text/javascript" src="https://js.balancedpayments.com/v1/balanced.js"></script>-->

<script type="text/javascript" src="/js/jquery.jeditable.mini.js"></script>

<script type="text/javascript">
   
   $(document).ready(function()
{
    
//   balanced.init('/v1/marketplaces/TEST-MP1UEXukTLr6ID7auHkkCHd6');
//
//
////    var cardData = {
////  "name": "Bernhard Riemann",                 // Optional
////  "card_number": "4111 1111 1111 1111",
////  "expiration_month": 4,
////  "expiration_year": 2014,
////};
//
//    $('#credit-card-form').submit(function(){
//
//        var $form = $('#credit-card-form');
//
//        // collect the data from the form.
//        var creditCardData = {
//            card_number: '4111 1111 1111 1111', //$form.find('.cc-number').val(),
//            expiration_month: '4', //$form.find('.cc-em').val(),
//            expiration_year: '2014', //$form.find('.cc-ey').val(),
//            security_code: $form.find('cc-csc').val()
//        };               
//        alert("sdfsdf");
//          balanced.card.create(creditCardData, responseCallbackHandler);
//        return false;
//        
//                      
//                         
//        }); 
//        
//function responseCallbackHandler(response) {
//   switch (response.status) {
//     case 400:
//         // missing or invalid field - check response.error for details
//         console.log(response.error);
//         break;
//     case 404:
//         // your marketplace URI is incorrect
//         console.log(response.error);
//         break;
//     case 201:
//         // WOO HOO! MONEY!
//         // response.data.uri == URI of the card resource
//         // you should store this returned card URI to later charge it
//         //console.log(response.data);
//         
//        $.post('/item/testest', response.data, function(response) {
//          console.log(response);
//        });
//         //$.post('your-marketplace.tld/cards', response.data);
//     }

$(".edit").editable(function(value, settings) 
{
    selectedId = $(this).attr("id");
    $.ajax(
    {
        url:'/user/edit/111/' + selectedId,
        type:'post',
        data: {data:  value},
        success: function(data) 
        {
            if (data.substring(0, 5) === "Error")
            {
                alert(data);
            }
        },
        error: function(req) 
        {
            alert("Error in request. Please try again later.");
        }
    });
    
    return value; //need the return
},
{ 
    indicator : "Saving...",
    tooltip   : "Click to edit..."
});
//function submitEdit(value, settings)
//{ 
//   var edits = new Object();
//   var origvalue = this.revert;
//   var textbox = this;
//   var result = value;
//   edits[settings.name] = [value];
//   
//   var request = $.ajax({
//        url: "/item/testest",
//        type: "post",
//        data: edits
//    });
//
//    // callback handler that will be called on success
//    request.done(function (response, textStatus, jqXHR){
//        // log a message to the console
//        console.log("Hooray, it worked!" + response);
//    });
//
//    // callback handler that will be called on failure
//    request.fail(function (jqXHR, textStatus, errorThrown){
//        // log the error to the console
//        console.error(
//            "The following error occured: "+
//            textStatus, errorThrown
//        );
//    });
//   return('result');
// }
 

});
//function callbackHandler(response) {
//   switch (response.status) {
//       case 201:
//           // WOO HOO!
//           // response.data.uri == uri of the card or bank account resource
//           alert(response.data.uri);
//           break;
//       case 400:
//           // missing field - check response.error for details
//           break;
//       case 402:
//           // we couldn't authorize the buyer's credit card
//           // check response.error for details
//           break
//       case 404:
//           // your marketplace URI is incorrect
//           break;
//       case 500:
//           // Balanced did something bad, please retry the request
//           break;
//   }
//}

//balanced.card.create(cardData, callbackHandler);
</script>



<!--<form action="#" method="POST" id="credit-card-form">
    <fieldset>
        <legend>Credit Card Details</legend>
        <label>Card Number
            <input type="text"
                   autocomplete="off"
                   placeholder="Card Number"
                   class="cc-number">
        </label>
        <label>Expiration
            <input type="text"
                   autocomplete="off"
                   placeholder="Expiration Month"
                   class="cc-em">
            <span>/</span>
            <input type="text"
                   autocomplete="off"
                   placeholder="Expiration Year"
                   class="cc-ey">
        </label>
        <label>Security Code (CSC)
            <input type="text"
                   autocomplete="off"
                   placeholder="CSC"
                   class="cc-csc">
        </label>
        <button type="submit" class="btn">
            Tokenize
        </button>
    </fieldset>
</form>-->


<div class="edit" id="1">Dolor</div>
<div class="edit_area" id="div_2">Lorem ipsum dolor sit amet, consectetuer 
adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore 
magna aliquam erat volutpat.</div>