$(document).ready(function () {

    $('#update-modal form').on('submit', function (event) {
        event.preventDefault();
        let data = new FormData($(this)[0]);
        let buttonSubmit = $(this).find('button:submit');
        buttonSubmit.addClass('running');
        buttonSubmit.attr('disabled', 'disabled');
        let topContentId = $('input[name="topic_content_id"]').val();
        //var editor_data = CKEDITOR.instances.editor1_update.getData();
        var editor_data = tinyMCE.activeEditor.getContent();
        console.log(data);
        data.set('content',editor_data);
        $.ajax({
            url: $(this).attr('action') + "/" + topContentId,
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            method: 'POST',
        }).done(function (response) {
            $('#update-modal').modal('toggle');
            location.reload();
        }).fail(function (xhr) {
            console.log(xhr);
            let $alert = $('.alert-danger');
            $alert.empty();
            $.each(xhr.responseJSON.errors, function (key, value) {
                $alert.show();
                $alert.append('<p>' + value + '</p>');
            });
            $("#result").html('');
        }).always(function () {
            buttonSubmit.removeClass('running');
            buttonSubmit.removeAttr('disabled', 'disabled');
        });
        




    });

    let updateModal = $('#update-modal');
    
    updateModal.on('show.bs.modal', function (e) {
    let update = $(e.relatedTarget);
    let topContentId = update.data('id');
    let updateModalForm = $('#update-modal form');
    $.ajax({
        url: updateModalForm.attr('action') + "/" + topContentId,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'GET',
        dataType: "json",
    }).done(function (xhr) {
        $('#topic_content_id').val(xhr.data.id);
        //CKEDITOR.instances.editor1_update.setData(xhr.data.content); 
        tinyMCE.activeEditor.setContent(xhr.data.content);
        jQuery('#heading').val(xhr.data.heading);
    });
});

$(document).on('click', '.remove-topic-content', function (event) {
    const deleted = $(this);
    event.preventDefault();
    swal({
        title: 'Are you sure you want to delete this?',
        text: "This action can't be undone",
        buttons: true,
        
    }).then((willDelete) =>{
        if(willDelete){
            let topicId = deleted.attr('data-id');
            $.ajax({
                url: $('#formTopicContent').attr('action'),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'DELETE',
                data: {
                    topic_content_id: topicId,
                },
            }).done(function () {
                swal({
                    title: "Successfully deleted",
                    
                }).then(() => {
                    location.reload();
                });
            }).fail(function (xhr) {
                
                swal('Oops...', "On error ocurred and it couldn't be deleted", 'error');
            });
        }else{
            location.reload();
        }
    })
});




})