// --- 1. PREVIEW LOGIC ---
// This updates the image on screen as soon as the user selects a file
document.getElementById("profileUpload").addEventListener("change", function (e) {
    const file = e.target.files[0];
    const preview = document.getElementById("previewImage");
    
    if (!file) return;

    // Validation: File Type
    const validTypes = ["image/jpeg", "image/jpg", "image/png", "image/webp"];
    if (!validTypes.includes(file.type)) {
        Swal.fire('Invalid Type', 'Only JPG, JPEG, PNG, and WEBP allowed!', 'error');
        this.value = ""; 
        return;
    }

    // Validation: File Size (2MB)
    if (file.size > 2 * 1024 * 1024) {
        Swal.fire('File Too Large', 'Maximum size allowed is 2MB.', 'error');
        this.value = "";
        return;
    }

    // Show the preview
    const reader = new FileReader();
    reader.onload = function (event) {
        preview.src = event.target.result;
    };
    reader.readAsDataURL(file);
});

// --- 2. UPDATE CONFIRMATION (SweetAlert2) ---
function validateUpload(event) {
    event.preventDefault(); // Stop the form from auto-submitting
    
    const fileInput = document.getElementById("profileUpload");

    // Check if a file is actually picked
    if (!fileInput.files || fileInput.files.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'No file selected',
            text: 'Please select a picture file first!',
            confirmButtonColor: '#3085d6'
        });
        return false;
    }

    // Show the "Are you sure?" popup
    Swal.fire({
        title: 'Are you sure?',
        text: "You are about to upload a new profile picture.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6', // Blue
        cancelButtonColor: '#d33',    // Red
        confirmButtonText: 'Yes, proceed!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // We manually add the button name so PHP can see it
            const form = event.target.closest('form');
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'uploadpicture'; // Matches your PHP isset
            hiddenInput.value = '1';
            form.appendChild(hiddenInput);
            form.submit();
        }
    });
}

// --- 3. DELETE CONFIRMATION (SweetAlert2) ---
function confirmDelete(event) {
    event.preventDefault();

    Swal.fire({
        title: 'Delete Picture?',
        text: "Are you sure you want to remove your profile photo?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33', // Danger Red
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // We manually add the button name so PHP can see it
            const form = event.target.closest('form');
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'deletepicture'; // Matches your PHP isset
            hiddenInput.value = '1';
            form.appendChild(hiddenInput);
            form.submit();
        }
    });
}