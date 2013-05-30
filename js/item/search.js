   $(document).ready(function()
{
    
   $('.borough').hover(function () 
    {
        $('.neighborhoods').hide();
        $('.neighborhoods[boroughid="' + $(this).attr('boroughid') + '"]').show();
    });

function getURLParameter(name) {
    return decodeURI(
        (RegExp(name + '=' + '(.+?)(&|$)').exec(location.search)||[,null])[1]
    );
}

//alert(getURLParameter('borough'));

if (getURLParameter('borough') != 'null')
{
    $('.borough[boroughid="' + getURLParameter('borough') + '"]').css("font-weight", "bold");
    $('.neighborhoods[boroughid="' + getURLParameter('borough') + '"]').show();
}

else if (getURLParameter('neighborhood') != 'null')
{
    var neighborhood = $('.neighborhood[neighborhoodid="' + getURLParameter('neighborhood') + '"]');
    var boroughID = neighborhood.parent().attr('boroughid');
    $('.borough[boroughid="' + boroughID + '"]').css("font-weight", "bold");
    $('.neighborhoods[boroughid="' + boroughID + '"]').show();      
    neighborhood.css("font-weight", "bold");    
}

else
{
    $('#all').css("font-weight", "bold");   
}

if (getURLParameter('tag') != 'null')
{
    $('.tag[tagid="' + getURLParameter('tag') + '"]').css("font-weight", "bold");
}

if (getURLParameter('query') != 'null')
{
    $('#search').val(getURLParameter('query'));
}

 //alert(window.location.pathname);
//var splittedURL = window.location.pathname.split(/\/+/g);
//
//var state = splittedURL[4];
//
//if (state == 1)
//    {
//        var neighborhoodID = splittedURL[3];
//        var neighborhood = $('.neighborhood[neighborhoodid="' + neighborhoodID + '"]');
//        var boroughID = neighborhood.parent().attr('boroughid');
//
//        $('.neighborhoods[boroughid="' + boroughID + '"]').show();      
//        neighborhood.css("font-weight", "bold");
//    }
//    
//    else if (state == 2)
//        {
//            var boroughID = splittedURL[3];
//            $('.neighborhoods[boroughid="' + boroughID + '"]').show();
//        }
       
});