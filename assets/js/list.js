window.openUserEditModal = function(btn) {

    document.getElementById('edit_id').value = btn.getAttribute('data-id');
    document.getElementById('edit_name').value = btn.getAttribute('data-name');
    document.getElementById('edit_username').value = btn.getAttribute('data-username');
    document.getElementById('edit_level').value = btn.getAttribute('data-level');

    const photo = btn.getAttribute('data-photo');
    document.getElementById('edit_preview').src = photo ? photo : './assets/images/emptyuser.png';

    const modalElement = document.getElementById('editUserModal');
    let myModal = bootstrap.Modal.getInstance(modalElement);
    if (!myModal) {
        myModal = new bootstrap.Modal(modalElement);
    }
    myModal.show();
}

window.previewUserImage = function(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('edit_preview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

window.confirmUserDelete = function(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This user will be permanently removed!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "?page=user/list&action=delete&id=" + id;
        }
    })
}
