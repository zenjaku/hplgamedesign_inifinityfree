
function validateZip(input) {
    if (input.value.length > 5) {
        input.value = input.value.slice(0, 5);
    }
}
function validateContact(input) {
    if (input.value.length > 11) {
        input.value = input.value.slice(0, 11);
    }
}

function validateAge(input) {
    if (input.value < 0) {
        input.value = '';
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
    var isEmployeeID = false; // Flag to track if the Employee ID is valid
    const submitBtn = document.getElementById('registerBtn'); // Ensure your submit button has this ID

    // Disable the submit button initially
    submitBtn.disabled = true;

    // Function to check if the Employee ID exists
    function checkID(employee_id) {
        $.ajax({
            method: 'POST',
            url: 'server/jquery/check_id.php', // Ensure this endpoint handles the check correctly
            data: { employee_id: employee_id },
            success: function (response) {
                // Parse the JSON response from the server
                var data = JSON.parse(response);
                if (data.exists) {
                    showToast('Employee ID already exists. Please choose a different ID.', 'error');
                    isEmployeeID = false;
                } else {
                    isEmployeeID = true;
                }
                // Enable or disable the button based on the result
                submitBtn.disabled = !isEmployeeID;
            },
            error: function () {
                showToast('Error checking Employee ID. Please try again.', 'error');
                isEmployeeID = false;
                submitBtn.disabled = true;
            }
        });
    }

    // Listen for input changes on the Employee ID field
    $('#employee_id').on('input', function () {
        var employee_id = $(this).val().trim();
        if (employee_id.length > 0) {
            // Pass the correct variable to checkID
            checkID(employee_id);
        } else {
            isEmployeeID = false;
            submitBtn.disabled = true; // Ensure the button remains disabled if the input is empty
        }
    });
});
