jQuery(document).ready(function () {


    jQuery('#update-modal form').on('submit', function (event) {
        event.preventDefault();
        let data = new FormData(jQuery(this)[0]);
        let buttonSubmit = jQuery(this).find('button:submit');
        buttonSubmit.addClass('running');
        buttonSubmit.attr('disabled', 'disabled');
        let gameId = jQuery('input[name="livecase_id"]').val();
        
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
    let livecaseId = update.data('id');
    let updateModalForm = jQuery('#update-modal form');
    jQuery.ajax({
        url: updateModalForm.attr('action') + "/" + livecaseId,
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        },
        type: 'GET',
        dataType: "json",
    }).done(function (xhr) {
        jQuery('#livecase_id').val(xhr.data.id);
        jQuery('#points_update').val(xhr.data.points);
       
    });
});

jQuery(document).on('click', '.remove-livecase', function (event) {
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
                url: $('#formLiveCase').attr('action'),
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











})