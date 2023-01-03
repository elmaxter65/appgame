jQuery(document).ready(function () {



jQuery(document).on('click', '.remove-notification', function (event) {
    const deleted = jQuery(this);
    event.preventDefault();
    swal({
        title: 'Are you sure you want to delete this?',
        text: "This action can't be undone",
        buttons: true,
        
    }).then((willDelete) =>{
        if(willDelete){
            let notificationId = deleted.attr('data-id');
            
            jQuery.ajax({
                url: $('#formNotification').attr('action'),
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                type: 'DELETE',
                data: {
                    notification_id: notificationId,
                },
            }).done(function () {
                swal({
                    title: "Successfully deleted",
                    
                    
                }).then(() => {
                    location.reload();
                });
            }).fail(function (xhr) {
                
                swal('Oops...', "An error ocurred and it couldn't be deleted", 'error');
            });
        }else{
            location.reload();
        }
    })
});

jQuery(document).on('click', '.send-notification', function (event) {
    const deleted = jQuery(this);
    event.preventDefault();
    swal({
        title: 'Are you sure you want to send this notification?',
        text: "This action can't be undone",
        buttons: true,
        
    }).then((willDelete) =>{
        if(willDelete){
            let notificationId = deleted.attr('data-id');
            
            jQuery.ajax({
                url: $('#sendNotification').val(),
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                data: {
                    notification_id: notificationId,
                },
            }).done(function () {
                swal({
                    title: "Successfully sent",
                    
                    
                }).then(() => {
                    location.reload();
                });
            }).fail(function (xhr) {
                console.log(xhr);
                swal('Oops...', "An error ocurred and it couldn't be sent", 'error');
            });
        }else{
            location.reload();
        }
    })
});




})