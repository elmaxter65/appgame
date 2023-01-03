jQuery(document).ready(function () {

    jQuery('#update-modal form').on('submit', function (event) {
        event.preventDefault();
        let data = new FormData(jQuery(this)[0]);
        let buttonSubmit = jQuery(this).find('button:submit');
        buttonSubmit.addClass('running');
        buttonSubmit.attr('disabled', 'disabled');
        let gameId = jQuery('input[name="game_id"]').val();
        
        jQuery.ajax({
            url: jQuery(this).attr('action') + "/" + gameId,
            data: data,
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            },
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            method: 'POST',
        }).done(function (response) {
            jQuery('#update-modal').modal('toggle');
            location.reload();
        }).fail(function (xhr) {
            console.log(xhr);
            let $alert = jQuery('.alert-danger');
            $alert.empty();
            jQuery.each(xhr.responseJSON.errors, function (key, value) {
                $alert.show();
                $alert.append('<p>' + value + '</p>');
            });
            jQuery("#result").html('');
        }).always(function () {
            buttonSubmit.removeClass('running');
            buttonSubmit.removeAttr('disabled', 'disabled');
        });
        


    });

    let updateModal = jQuery('#update-modal');
    updateModal.on('show.bs.modal', function (e) {
    let update = jQuery(e.relatedTarget);
    let gameId = update.data('id');
    let updateModalForm = jQuery('#update-modal form');
    jQuery.ajax({
        url: updateModalForm.attr('action') + "/" + gameId,
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        },
        type: 'GET',
        dataType: "json",
    }).done(function (xhr) {
        jQuery('#game_id').val(xhr.data.id);
        jQuery('#points_update').val(xhr.data.points);
       
    });
});

jQuery(document).on('click', '.remove-game', function (event) {
    const deleted = jQuery(this);
    event.preventDefault();
    swal({
        title: 'Are you sure you want to delete this?',
        text: "This action can't be undone",
        buttons: true,
        
    }).then((willDelete) =>{
        if(willDelete){
            let gameId = deleted.attr('data-id');
            
            jQuery.ajax({
                url: $('#formGame').attr('action'),
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                type: 'DELETE',
                data: {
                    game_id: gameId,
                },
            }).done(function () {
                swal({
                    title: "Successfully deleted",
                    
                }).then(() => {
                    location.reload();
                });
            }).fail(function (xhr) {
                console.log(xhr);
                swal('Oops...', "An error ocurred and it couldn't be deleted", 'error');
            });
        }else{
            location.reload();
        }
    })
});

function getLessonperTopic(){
    var topic_id = $('#selectTopic').val();
    var url = $('#urlTopic').val();
    console.log(topic_id);
    jQuery.ajax({
        url: url,
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        },
        type: 'GET',
        data: {
            topic_id: topic_id,
        },
    }).done(function (xhr) {
        var lessons = xhr.data;
        
        $('#selectLesson option').map(function() {
            var lesson_id = $(this).val();
            if(lessons.length == 0){
                $('#selectLesson'+lesson_id).prop('disabled', true);
            }else{
            for(var lesson of lessons){
                if (lesson_id == lesson.id){
                    console.log('si');
                    $('#selectLesson'+lesson_id).prop('disabled', false);
                    break;
                }else{
                    console.log('no');
                    $('#selectLesson'+lesson_id).prop('disabled', true);
                }
            }
        }

        }).get();
    }).fail(function (xhr) {
        console.log(xhr);
        
    });

}

getLessonperTopic();


$('body').on('change', '#selectTopic', function(){
    getLessonperTopic();
   
});





})