$(document).ready(function(){

              $("#signup").validate({
                      rules:{
                              fname:"required",
                              lname:"required",
                              zipcode: 
                                {
                                              required:true
                                },                                  
                              email:{
                                              required:true,
                                              email: true
                                      },
                              passwd:{
                                      required:true,
                                      minlength: 8
                              }
                      },

                      errorClass: "help-inline"

              });
      });