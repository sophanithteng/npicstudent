function openEditModal(user) {
    // Fill the hidden input and visible fields
    document.getElementById('edit_id').value = user.id;
    document.getElementById('edit_name').value = user.name;
    document.getElementById('edit_email').value = user.email || (user.name + "@gmail.com");
    document.getElementById('edit_level').value = user.level;

    // Set the image preview
    const photo = user.profile_pic ? user.profile_pic : './assets/images/emptyuser.png';
    document.getElementById('edit_preview').src = photo;

    // Show the modal
    var myModal = new bootstrap.Modal(document.getElementById('editUserModal'));
    myModal.show();
}

function confirmDelete(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        rounded: '20px' // Matches your pill-style UI
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirect if user clicks "Yes"
            window.location.href = "?page=user/list&action=delete&id=" + id;
        }
    })
}

// Preview image before uploading
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('edit_preview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Handle Form Submission with AJAX
document.getElementById('editUserForm').onsubmit = function (e) {
    e.preventDefault();
    let formData = new FormData(this);
    formData.append('action', 'edit_user');

    fetch('api/user_actions.php', {
        method: 'POST',
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('User Updated!');
                location.reload();
            }
        });
};
