/*
 * jQuery File Upload Plugin JS Example 7.0
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

/*jslint nomen: true, unparam: true, regexp: true */
/*global $, window, document */

$(function () {
    'use strict';

        // Initialize the jQuery File Upload widget:
    $('#fileupload').fileupload({
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        maxNumberOfFiles: window.location.pathname.slice(-1) == 0 ? 8 : 1,
        url: '/picture/handler/null/' + window.location.pathname.slice(-1)
    });

//    // Enable iframe cross-domain access via redirect option:
//    $('#fileupload').fileupload(
//        'option',
//        'redirect',
//        window.location.href.replace(
//            /\/[^\/]*$/,
//            '/cors/result.html?%s'
//        )
//    );

//$('#fileupload').bind('fileuploadchange', function (e, data) 
//{
//    alert(data.files.length);
//    alert($(".template-upload").size());
//    if (data.files.length + $(".template-upload").size() > 1) {
//                alert("only allow " + 1 + " file(s) for simultaneous upload");
//                return false;
//            }
//        });
        
        // Load existing files:
        $.ajax({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
            url: $('#fileupload').fileupload('option', 'url'),
            dataType: 'json',
            context: $('#fileupload')[0]
        }).done(function (result) {
            $(this).fileupload('option', 'done')
                .call(this, null, {result: result});
        });
    
    $('#fileupload').bind('fileuploadsubmit', function (e, data) {
    var inputs = data.context.find(':input');
    
    data.formData = inputs.serializeArray();
    });

    $('#fileupload').bind('fileuploaddestroy', function (e, data) {
            
    
//that._adjustMaxNumberOfFiles(1);
    });


});

    function myFunction(file)
    {
//        alert("sdfsdf");
        
 $.ajax({
            url: '/picture/handler/null/' + + window.location.pathname.slice(-1),
            dataType: 'json',
            context: this,
            data: {del: 'yes', file: file},
            type: 'POST'
        });
        
        return false;
    }
    
//$(document).ready(function()
//{	
//    $(window).unload(function() {
//      
//  $('#thumbs', window.opener.document ).empty();
//   $('#picture', window.opener.document ).find('img').remove();
//  
//$('.files').children('tr.template-download').children('td.name').children('a').each(function(i) { 
//    
//        if (i==0)
//            {
//                $('#picture', window.opener.document ).append('<img id=\'largeimage\' src=\'' + $(this).attr('href') + '\' style=\'max-height: 500px; max-width: 640px\'>');
//            }
//    
//            $('#thumbs', window.opener.document ).append('<img src=\'' + $(this).attr('href') + '\' style=\'height: 50px\'>');
//    
//        });
//
//
//  
//  
//    });
//});