<div class="container" id="checkID">
    <div class="card">
        <div class="card-header">
            <h5>Enter your Employee ID</h5>
        </div>
        <div class="p-5 card-body">
            <div class="input-group">
                <input type="number" name="employee_id" class="form-control" placeholder="Employee ID" id="id">
                <button type="submit" class="btn btn-dark" id="submitBtn" disabled>Submit</button>
            </div>
        </div>
    </div>
</div>

<script>
    function validateContact(input) {
        if (input.value.length > 11) {
            input.value = input.value.slice(0, 11);
        }
    }
    // Function to show toast notifications
    function showToast(message, status) {
        // Define colors based on status
        const bgColor = status === 'success' ? 'warning' : 'danger';
        const textColor = bgColor === 'danger' ? 'text-white' : '';

        // Create the toast element
        const toast = document.createElement('div');
        toast.className = `toast show bg-${bgColor} ${textColor}`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
        <div class="toast-body justify-content-between d-flex">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
    `;

        // Create the toast container if it doesn't exist
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            document.body.appendChild(toastContainer);
        }

        // Append the toast to the container
        toastContainer.appendChild(toast);

        // Automatically remove the toast after 3 seconds
        setTimeout(() => toast.remove(), 3000);
    }
    $(document).ready(function () {
        const submitBtn = document.getElementById('submitBtn');

        submitBtn.disabled = true; // Ensure button starts as disabled

        function checkEmployeeExists(employee_id) {
            $.ajax({
                method: 'POST',
                url: 'server/jquery/check_employee_id.php',
                data: { employee_id: employee_id }, // Ensure key matches PHP script
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data.exists) {
                        showToast('Employee ID is valid.', 'success');
                        submitBtn.disabled = false; // Enable button when valid
                    } else {
                        showToast('Employee ID not found.', 'error');
                        submitBtn.disabled = true;
                    }
                },
                error: function () {
                    showToast('Error checking Employee ID. Please try again.', 'error');
                    submitBtn.disabled = true;
                }
            });
        }

        $('#id').on('input', function () {
            var employee_id = $(this).val().trim();
            if (employee_id.length > 0) {
                checkEmployeeExists(employee_id);
            } else {
                submitBtn.disabled = true;
            }
        });
    });
</script>