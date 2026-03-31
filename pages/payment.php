<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12 text-center text-md-start">
            <h3 class="fw-bold text-body mb-1">Student Payment</h3>
            <p class="text-secondary small">Please fill in the payment details based on the student receipt.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4 p-md-5">
            <form action="?page=payment/save" method="POST" enctype="multipart/form-data">
                
                <div class="text-center mb-5">
                    <div class="position-relative d-inline-block">
                        <img src="./assets/images/emptyuser.png" id="payment_preview" 
                             class="rounded-circle border border-3 border-primary-subtle shadow-sm" 
                             style="width: 120px; height: 120px; object-fit: cover;">
                        <label for="payment_photo" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2 shadow" style="cursor: pointer;">
                            <i class="bi bi-camera-fill"></i>
                        </label>
                        <input type="file" name="receipt_pic" id="payment_photo" hidden onchange="previewPaymentImage(this)">
                    </div>
                    <p class="small text-muted mt-2">Upload Receipt Photo (Optional)</p>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="small fw-bold mb-1">Student Name (KH/EN)</label>
                        <input type="text" name="student_name" class="form-control rounded-pill px-3" placeholder="Enter student name" required>
                    </div>
                    <div class="col-md-6">
                        <label class="small fw-bold mb-1">Student ID (អត្តលេខ)</label>
                        <input type="text" name="student_id" class="form-control rounded-pill px-3" placeholder="e.g. NPIC21190" required>
                    </div>

                    <div class="col-md-4">
                        <label class="small fw-bold mb-1">Academic Year</label>
                        <select name="academic_year" class="form-select rounded-pill px-3">
                            <option value="2024-2025">2024-2025</option>
                            <option value="2025-2026">2025-2026</option>
                            <option value="2025-2026">2026-2027</option>
                            <option value="2025-2026">2027-2028</option>
                            <option value="2025-2026">2028-2029</option>
                            <option value="2025-2026">2029-2030</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="small fw-bold mb-1">Group (ក្រុម)</label>
                        <input type="text" name="student_group" class="form-control rounded-pill px-3" placeholder="e.g. F" required>
                    </div>
                    <div class="col-md-4">
                        <label class="small fw-bold mb-1">Generation (ជំនាន់)</label>
                        <input type="number" name="generation" class="form-control rounded-pill px-3" placeholder="e.g. 19" required>
                    </div>

                    <div class="col-md-6">
                        <label class="small fw-bold mb-1 text-success">Total Paid ($)</label>
                        <input type="number" name="amount_paid" class="form-control rounded-pill px-3 fw-bold border-success text-success" placeholder="700.00" step="0.01" required>
                    </div>
                    <div class="col-md-6">
                        <label class="small fw-bold mb-1">Payment For</label>
                        <select name="payment_type" class="form-select rounded-pill px-3">
                            <option value="Full Year">Full Year (បង់ពេញមួយឆ្នាំ)</option>
                            <option value="Term">Term (បង់តាមឆមាស)</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="small fw-bold mb-1">Remarks / Note</label>
                        <textarea name="remarks" class="form-control rounded-4 px-3" rows="2" placeholder="Any additional payment info..."></textarea>
                    </div>
                </div>

                <hr class="my-5 opacity-25">

                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light rounded-pill px-4" onclick="history.back()">Cancel</button>
                    <button type="submit" name="btnSavePayment" class="btn btn-primary rounded-pill px-5 shadow-sm">
                        Confirm Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewPaymentImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('payment_preview').src = e.target.result;
        reader.readAsDataURL(input.files[0]);
    }
}
</script>