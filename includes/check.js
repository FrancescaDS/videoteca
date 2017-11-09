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
            },
            //email : {
              //  required : true,
                //email : true
            //},
            
        },
        messages: {
            title: {
                required: "Insert a valid title",
                minlength: "Title must be min 2 chars",
                maxlength: "Title must be max 64 chars"
            },
            year: "Insert a valid year between 1900 and 2017"
        },
        // Settiamo il submit handler per la form
        submitHandler: function(form) {
            form.submit();
        }
    });
});