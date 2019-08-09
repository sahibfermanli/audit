function del(url, id) {
    swal({
        title: 'Do you approve the deletion?',
        type: 'warning',
        showCancelButton: true,
        cancelButtonText: 'No',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes!'
    }).then(function (result) {
        if (result.value) {
            swal({
                title: '<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><span class="sr-only">Please wait...</span>',
                text: 'Loading, please wait...',
                showConfirmButton: false
            });
            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: "delete",
                url: url,
                data: {
                    'id': id,
                    '_token': CSRF_TOKEN,
                },
                success: function (response) {
                    swal.close();
                    if (response.case === 'success') {
                        $('#row_'+response.id).remove();
                    }
                    else {
                        swal(
                            response.title,
                            response.content,
                            response.case
                        );
                    }
                }
            });
        } else {
            return false;
        }
    });
}