$(document).ready(function()
{

    $("#add-pictures").click(function() 
      {
        $( "#dialog-form" ).dialog( "open" );
        return false;
      });
      
      $('#uploaderframe').attr('src', '/picture/upload/null/1'); 
      
    $( "#dialog-form" ).dialog({
      autoOpen: false,
      height: 600,
      width: 984,
      modal: true,
       open: function(event, ui) { $(".ui-dialog-titlebar-close", $(this).parent()).hide(); },      
      buttons: {
        "Done": function() {

       //$('#picture').empty();
       $('#picture').find('#profilepicture').remove();
       //$('#picture').find('input').remove();
  
  
  if ($("#uploaderframe").contents().find('.files').children('tr.template-download').children('td.name').children('a').length > 0)
      {
          $('#submitPictureButton').show();
      }
      
      else
          {
          $('#submitPictureButton').hide();
          $('#picture').prepend('<img id="profilepicture" src="/img/stock_user_profile.jpg">');
          }
  
  
   $("#uploaderframe").contents().find('.files').children('tr.template-download').children('td.name').children('a').each(function(i) { 
    
                $('#picture').prepend('<img id=\'profilepicture\' src=\'' + $(this).attr('href') + '\' style=\'\'>');
                

            $('#picture').prepend('<input type="hidden" name=\'file' + '[]' + '\' value=\'' + $(this).attr('href') + '\' style=\'display:none\'>');
    
 });
 
 
        
            $( this ).dialog( "close" );
            $('#submitPictureButton').attr('disabled', false);
         
        }
      }
    });
    
    $('#submitPictureButton').click(function() 
      {
          $('#submitPictureButton').attr('disabled', true);
            $(this).siblings('.loading').show();        
            var thisReference = $(this);
            
        //post
        $.ajax(
        {
            url:'/user/edit/' + $('#userid').val() + "/4",
            type:'post',
            data: {picture: $('#profilepicture').attr('src')},
            success: function(data) 
            {
                if (data.substring(0, 5) === "Error")
                {
                    alert(data);
                    thisReference.siblings('.loading').hide();
                     thisReference.hide();
                                         
                }

                else
                {
                    thisReference.siblings('.loading').hide();
                     thisReference.hide();
              
                     thisReference.siblings('.checkmark').css('opacity',"1.0").fadeTo(1500, 0);
                     
                }

            },
            error: function(req) 
            {
                alert("Error in request. Please try again later.");
                //thisReference.siblings('.editable').val(oldValue);
                //$('.cancelButton').click();                       
            }
        });  
        
        
      });
    
    
    $('.editNetworksButton').click(function() 
    {
        $('.currentNetworks').hide();
        $('.editNetworks').show();
    });
    
    $('.submitButton.network').click(function(e) 
    {
        $(this).attr('disabled', true);
        $(this).siblings('.cancelButton').hide();
        $(this).siblings('.loading').show();
        var thisReference = $(this);            
        
        //post
        $.ajax(
        {
            url:'/user/edit/' + $('#userid').val() + "/3",
            type:'post',
            data: {email:  $('#networkemail').val(), networkid: $('#networklist option:selected').attr('value')},
            success: function(data) 
            {
                if (data.substring(0, 5) === "Error")
                {
                    alert(data);
                }

                else
                {
                    $('#networkRequestSent').show();
                    $('.editNetworks').hide();
                    thisReference.siblings('.loading').hide();
                    thisReference.attr('disabled', false);
                    thisReference.siblings('.cancelButton').show();
                    $('#networkemail').val('');
                }

            },
            error: function(req) 
            {
                alert("Error in request. Please try again later.");
                thisReference.siblings('.editable').val(oldValue);
                $('.cancelButton').click();                       
            }
        });          
        
        e.stopImmediatePropagation();
    });
    
    
    $('.cancelButton.network').click(function() 
    {
        $('.currentNetworks').show();
        $('.editNetworks').hide();
        e.stopImmediatePropagation();
    });
    
    
    
    $('.editButton').click(function() 
    {
        $(this).hide();
        $(this).siblings('.noneditable').hide();
        $(this).siblings('.cancelButton').show();
        $(this).siblings('.editable').show();
        $(this).siblings('.editable')[0].focus();
        $(this).siblings('.submitButton').show();
    });

    $('.submitButton.Location').click(function(e) 
    {
        var newValue =  $('.editable option:selected').attr('value');
        
        $(this).attr('disabled', true);
        $(this).siblings('.cancelButton').hide();
        $(this).siblings('.loading').show();

        var thisReference = $(this);

        //post
        $.ajax(
        {
            url:'/user/edit/' + $('#userid').val() + "/5",
            type:'post',
            data: {location_id:  newValue},
            success: function(data) 
            {
                if (data.substring(0, 5) === "Error")
                {
                    alert(data);

                    $('.cancelButton').click();   
                }

                else
                {
                    thisReference.siblings('.noneditable').text($('.editable option:selected').text());   
                    thisReference.siblings('.checkmark').css('opacity',"1.0").fadeTo(1500, 0);
                    $('.cancelButton').click();
                }

            },
            error: function(req) 
            {
                alert("Error in request. Please try again later.");
                thisReference.siblings('.editable').val(oldValue);
                $('.cancelButton').click();                       
            }
        });
        
        e.stopImmediatePropagation();
    });
    
    $('.submitButton.PP').click(function(e) 
    {
        var oldFirstName = $(this).siblings('.noneditable.ppFirstName').text();
        var oldLastName = $(this).siblings('.noneditable.ppLastName').text();
        var oldEmailAddress = $(this).siblings('.noneditable.ppEmailAddress').text();
        
        var newFirstName = $(this).siblings('.editable.ppFirstName').val().trim();
        var newLastName = $(this).siblings('.editable.ppLastName').val().trim();
        var newEmailAddress = $(this).siblings('.editable.ppEmailAddress').val().trim();
        
        if (!IsEmail(newEmailAddress))
        {
            alert("Invalid email");
        }
            
        else if (oldFirstName.localeCompare(newFirstName) != 0 || oldLastName.localeCompare(newLastName) != 0 || oldEmailAddress.localeCompare(newEmailAddress) != 0)
        {
            $(this).attr('disabled', true);
            $(this).siblings('.cancelButton').hide();
            $(this).siblings('.loading').show();

            var thisReference = $(this);
            
            //post
            $.ajax(
            {
                url:'/user/edit/' + $('#userid').val() + "/" + thisReference.parent().attr("id"),
                type:'post',
                data: {ppFirstName:  newFirstName, ppLastName : newLastName, ppEmailAddress: newEmailAddress},
                success: function(data) 
                {
                    if (data.substring(0, 5) === "Error")
                    {
                        alert(data);
                          
                        thisReference.siblings('.editable.ppFirstName').val(oldFirstName); 
                        thisReference.siblings('.editable.ppLastName').val(oldLastName); 
                        thisReference.siblings('.editable.ppEmailAddress').val(oldEmailAddress);
                        
                        $('.cancelButton').click();   
                    }

                    else
                    {
                        var obj = JSON.parse(data);

                        thisReference.siblings('.noneditable.ppFirstName').text(obj.ppFirstName);    
                        thisReference.siblings('.editable.ppFirstName').val(obj.ppFirstName);
                        thisReference.siblings('.noneditable.ppLastName').text(obj.ppLastName);    
                        thisReference.siblings('.editable.ppLastName').val(obj.ppLastName);
                        thisReference.siblings('.noneditable.ppEmailAddress').text(obj.ppEmailAddress);    
                        thisReference.siblings('.editable.ppEmailAddress').val(obj.ppEmailAddress);
                        
                        thisReference.siblings('.checkmark').css('opacity',"1.0").fadeTo(1500, 0);
                        $('.cancelButton').click();
                    }

                },
                error: function(req) 
                {
                    alert("Error in request. Please try again later.");
                    thisReference.siblings('.editable').val(oldValue);
                    $('.cancelButton').click();                       
                }
            });                 
        }
    
        else
        {
            $('.cancelButton').click();
        }
        
        e.stopImmediatePropagation();
    }); 
    
    $('.submitButton').click(function() 
    {
        var oldValue = $(this).siblings('.noneditable').text().trim();
        var newValue = $(this).siblings('.editable').val().trim();

        if (!IsEmail(newValue))
        {
            alert("Invalid email");
        }
            
        else if (oldValue.localeCompare(newValue) != 0)
        {
            $(this).attr('disabled', true);
            $(this).siblings('.cancelButton').hide();
            $(this).siblings('.loading').show();

            var thisReference = $(this);
            
            //post
            $.ajax(
            {
                url:'/user/edit/' + $('#userid').val() + "/" + thisReference.parent().attr("id"),
                type:'post',
                data: {data:  newValue},
                success: function(data) 
                {
                    if (data.substring(0, 5) === "Error")
                    {
                        alert(data);
                        thisReference.siblings('.editable').val(oldValue);
                        $('.cancelButton').click();   
                    }

                    else
                    {
                        thisReference.siblings('.noneditable').text(data);    
                        thisReference.siblings('.editable').val(data);
                        thisReference.siblings('.checkmark').css('opacity',"1.0").fadeTo(1500, 0);
                        $('.cancelButton').click();
                        
                    }

                },
                error: function(req) 
                {
                    alert("Error in request. Please try again later.");
                    thisReference.siblings('.editable').val(oldValue);
                    $('.cancelButton').click();                       
                }
            });                 
        }
    
        else
        {
            $('.cancelButton').click();
        }
    });
    
     $('.cancelButton').click(function() 
 {
     $(this).hide();
     $(this).siblings('.submitButton').attr('disabled', false);
     $(this).siblings('.submitButton').hide();
          $(this).siblings('.noneditable').show();
          $(this).siblings('.editButton').show();
     $(this).siblings('.editable').hide();
     $(this).siblings('.loading').hide();
  
     
});



    
        

function IsEmail(email) {
  var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}

//$('.editable').blur(function() {
//    $('.cancelButton').click();
//});

//
//    $( document ).tooltip();
 
// $('.jeditable-activate').click(function() 
// {
//    $(this).hide();
//    $(this).prev().click();
//});
//
// $(".edit").editable(function(value, settings) 
//{
//    $(this).next().hide();
//    selectedId = $(this).attr("id");
//    $.ajax(
//    {
//        url:'/user/edit/111/' + selectedId,
//        type:'post',
//        data: {data:  value},
//        success: function(data) 
//        {
//            if (data.substring(0, 5) === "Error")
//            {
//                alert(data);
//            }
//            
//            else
//                {
//                    // return old value
//                }
// 
//        },
//        error: function(req) 
//        {
//            alert("Error in request. Please try again later.");
//        }
//    });
//    
//    return value; //need the return
//},
//{ 
//    indicator : "Saving...",
//    tooltip   : "Click to edit...",
//     width:(0 + 200) + "px", // THIS DOES THE TRICK,
//     style   : 'display: inline',
//              cancel    : 'Cancel',
//         submit    : 'OK',
//          onblur: function(value) {
//            $(this).next().show();
//          }
//});
 
 
 
 $('#networklist').change(function()
    {
        if ($('#networklist option:selected').attr('email') == '')
        {
            $('#networkemail').removeClass('required');
            $('#networkdynamic').hide();
            $('#submit').prop("disabled", true);
        }
           
       else
       {
           $('#networkemail').addClass('required');
           $('#networkdynamic').show();
           $('#submit').prop("disabled", false);
       }
       
        $('#networkemailextension').text($('#networklist option:selected').attr('email'));
    });
//    
//    
//    $('#myForm').validate( {
//   
//        errorPlacement: function(error, element) 
//        {
//          
//        }
//    });
//    
//    
//    $('#myForm')
//        .ajaxForm({
//            success : function (response) 
//            {   
//                if (response >= 0)
//                    window.location = "/user/edit/" + response + "/2";
//                else
//                    alert ("Error");
//            }
//        });    
    
});