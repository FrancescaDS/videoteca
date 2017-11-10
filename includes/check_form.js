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
                number : true,
                min : 1900,
                max : 2017
            }
        },
        messages: {
            title: {
                required: "Insert a valid title (2-64 chars)",
                minlength: "Title must be min 2 chars",
                maxlength: "Title must be max 64 chars"
            },
            year: "Insert a valid year between 1900 and 2017"
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
    
    
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
            }
        },
        messages: {
            password: "Insert a valid password, between 8 and 20 chars",
            email: "Insert a valid email address",
            name: "Insert a valid name, max lenght 30chars",
            surname: "Insert a valid surname, max lenght 30chars"
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
    
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
              //required : true,
              minlength : 8,
              maxlength : 20
            }
        },
        messages: {
            password: "Insert a valid password, between 8 and 20 chars",
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
            password : {
              required : true
            }
        },
        messages: {
            password: "Insert a valid password",
            email: "Insert a valid email address"
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
    
    
});