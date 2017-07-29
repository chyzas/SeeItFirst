$(function() {
    $("form[name='first_query']").validate({
        rules: {
            'first_query[url]': "required",
            'first_query[name]': "required",
            'first_query[email]': {
                required: true,
                email: true
            }
        },
        messages: {
            'first_query[url]': "Įveskite užklausos nuorodą",
            'first_query[name]': "Įveskite pavadinimą",
            'first_query[email]': {
                required: 'Įveskite E-mail',
                email: "Blogas elektroninio pašto formatas"
            }
        }
    });
});
