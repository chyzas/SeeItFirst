$('document').ready(function(){
    $('#new-filter').click(function () {
        $('#new-filter-modal .modal-body .alert').remove()
    });

    var newFilterForm = $('#create-new-filter');
    newFilterForm.submit(function (e) {
        $.ajax({
            type: newFilterForm.attr('method'),
            url: newFilterForm.attr('action'),
            data: newFilterForm.serialize(),
            success: function (data) {
                console.log(data)
                $('#new-filter-modal').modal('hide');

                    var name = data.name;
                    var url = data.url;
                    var id = data.id;
                var markup = "<tr><th>"+name+"</th><th><a href='"+url+"'target='_blank'>"+url+"</a></th><th><a href='/user/filter/'"+id+"'/delete'><span class='glyphicon glyphicon-remove'></span></a></th><th><a href='/user/filter/'"+id+"'/results'><span class='glyphicon glyphicon-list'></span></a></th></tr>";

                    // $("table tbody").append(markup);

                $('#user-filter-table tr:last').after(markup)
            },
            error: function(data) {
                $('#new-filter-modal .modal-body').prepend("<div class='alert alert-danger'>"+data.responseJSON.message+"</div>");
            }
        });
        e.preventDefault();
    })
});