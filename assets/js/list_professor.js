// ==========================
// OPEN EDIT MODAL (GLOBAL)
// ==========================
window.openProfessorEditModal = function (btn) {

    document.getElementById('edit_id').value = btn.getAttribute('data-id');
    document.getElementById('edit_name').value = btn.getAttribute('data-name');
    document.getElementById('edit_designation').value = btn.getAttribute('data-designation');
    document.getElementById('edit_description').value = btn.getAttribute('data-description');
    document.getElementById('edit_dept').value = btn.getAttribute('data-dept');

    const photo = btn.getAttribute('data-photo');
    document.getElementById('edit_preview').src =
        photo ? photo : './assets/professor_images/emptyuser.png';

    const modalElement = document.getElementById('editProfessorModal');
    let myModal = bootstrap.Modal.getInstance(modalElement);

    if (!myModal) {
        myModal = new bootstrap.Modal(modalElement);
    }

    myModal.show();
};


// ==========================
// IMAGE PREVIEW
// ==========================
window.previewProfessorImage = function (input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function (e) {
            document.getElementById('edit_preview').src = e.target.result;
        };

        reader.readAsDataURL(input.files[0]);
    }
};


// ==========================
// DELETE CONFIRM (GLOBAL)
// ==========================
window.confirmProfessorDelete = function (id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This professor will be permanently removed!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "?page=professor/list&action=delete&id=" + id;
        }
    });
};