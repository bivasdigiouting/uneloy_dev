$(function () {   

    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: root+'/get-roles-data',
        columns: [
            {data: 'select_check_box', name: 'select_check_box'},
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'role_slug', name: 'role_slug'},
            {data: 'created_date', name: 'created_date'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},

        ]

    });

});

/**
 * Beautiful confirmation dialog for role deletion
 */
function deleteRoleWithConfirmation(deleteUrl, roleName) {
    Swal.fire({
        title: 'Delete Role?',
        html: `<div class="text-center">
                <i class="ti ti-trash text-danger" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                <p class="mb-2">Are you sure you want to delete the role:</p>
                <strong class="text-primary">${roleName}</strong>
                <p class="text-muted mt-2 small">This action cannot be undone!</p>
               </div>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="ti ti-trash me-1"></i>Yes, Delete It!',
        cancelButtonText: '<i class="ti ti-x me-1"></i>Cancel',
        customClass: {
            popup: 'swal2-popup-custom',
            title: 'swal2-title-custom',
            confirmButton: 'btn btn-danger',
            cancelButton: 'btn btn-secondary'
        },
        buttonsStyling: false,
        reverseButtons: true,
        focusCancel: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
                title: 'Deleting Role...',
                html: '<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x text-primary"></i></div>',
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false
            });
            
            // Perform the deletion
            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire({
                        title: 'Deleted!',
                        text: `Role "${roleName}" has been deleted successfully.`,
                        icon: 'success',
                        confirmButtonColor: '#28a745',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Reload the page or refresh the table
                        location.reload();
                    });
                },
                error: function(xhr, status, error) {
                    let errorMessage = 'An error occurred while deleting the role.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    Swal.fire({
                        title: 'Error!',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonColor: '#dc3545',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });
}