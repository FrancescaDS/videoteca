$().ready(function() {
    function injectTrim(handler) {
        return function (element, event) {
          if (element.tagName === "TEXTAREA" || (element.tagName === "INPUT" 
                                             && element.type !== "password")) {
            element.value = $.trim(element.value);
          }
          return handler.call(this, element, event);
        };
    }
 
    $("#form_register").validate({
        onfocusout: injectTrim($.validator.defaults.onfocusout),
        rules : {
            name : {
              required : true,
              maxlength : 30
            },
            surname : {
              required : true,
              maxlength : 30
            },
            email : {
              required : true,
              email : true
            },
            password : {
              required : true,
              minlength : 8,
              maxlength : 20
            },
            password2 : {
              equalTo : "#password"
            }
        },
        messages: {
            password: "Insert a valid password, between 8 and 20 chars",
            password2: "Password does not match confirm password",
            email: "Insert a valid email address",
            name: "Insert a valid name, max lenght 30chars",
            surname: "Insert a valid surname, max lenght 30chars"
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
    
    
    function isPasswordPresent() {
        return $('#password').val().length > 0;
    }
    
    $("#form_update").validate({
        onfocusout: injectTrim($.validator.defaults.onfocusout),
        rules : {
            name : {
              required : true,
              maxlength : 30
            },
            surname : {
              required : true,
              maxlength : 30
            },
            email : {
              required : true,
              email : true
            },
            password : {
              minlength : 8,
              maxlength : 20
            },
            password2 : {
              equalTo: {
                    depends: isPasswordPresent,
                    param: "#password"
                }
            }
        },
        messages: {
            password: "Insert a valid password, between 8 and 20 chars",
            password2: "Password does not match confirm password",
            email: "Insert a valid email address",
            name: "Insert a valid name, max lenght 30chars",
            surname: "Insert a valid surname, max lenght 30chars"
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
    
    $("#form_login").validate({
        onfocusout: injectTrim($.validator.defaults.onfocusout),
        rules : {
            email : {
              required : true,
              email : true
            },
            password : "required"
        },
        messages: {
            password: "Insert a valid password",
            email: "Insert a valid email address"
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
    
    $("#form_movie").validate({
        onfocusout: injectTrim($.validator.defaults.onfocusout),
        rules : {
            title : {
              required : true,
              minlength : 2,
              maxlength : 64
            },
            year : {
                required : true,
                digits : true,
                min : 1900,
                max : 2017
            }
        },
        messages: {
            title: "Insert a valid title (2-64 chars)",
            year: "Insert a valid year between 1900 and 2017"
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
    
    $("#form_image").validate({
        rules : {
            image : "required"
        },
        messages: {
            image: "Insert a valid image file .jpg"
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
    
    $("#form_newdirector").validate({
        rules : {
            newdirector : {
              min : 1
            }
        },
        messages: {
            newdirector: "Select the new director from the list"
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
    
    $("#form_newactor").validate({
        rules : {
            newactor : {
              min : 1
            },
            character_name: "required"
        },
        messages: {
            newactor : "Select the new star from the list",
            character_name: "Write the name of the character"
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
    
    $("#form_person").validate({
        onfocusout: injectTrim($.validator.defaults.onfocusout),
        rules : {
            name : {
              required : true,
              minlength : 2,
              maxlength : 64
            },
            surname : {
              required : true,
              minlength : 2,
              maxlength : 64
            },
            dob : {
              required : true,
              date : true
            },
            place : {
              required : true,
              minlength : 2,
              maxlength : 64
            },
            dod : {
                date : true
            }
        },
        messages: {
            name: "Insert a valid name (2-64 chars)",
            surname: "Insert a valid surname (2-64 chars)",
            dob: "Insert a valid date of birth",
            place: "Insert a valid place of birth (2-64 chars)"
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
    
});