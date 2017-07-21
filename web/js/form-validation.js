$(function() {
    $("form[name='first_query']").validate({
        rules: {
            'first_query[url]': "required",
            'first_query[name]': "required",
            'first_query[email]': {
                email: true
            }
        },
        messages: {
            'first_query[url]': "Įveskite užklausos nuorodą",
            'first_query[name]': "Įveskite pavadinimą",
            'first_query[email]': "Blogas elektroninio pašto formatas"
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
});
