
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
    var isUsernameValid = false; // Flag to track if username is valid
    const registerBtn = document.getElementById('registerBtn');

    registerBtn.disabled = true; // Correct way to disable the button initially

    // Function to check if username exists
    function checkUsernameExists(username) {
        $.ajax({
            method: 'POST',
            url: 'server/jquery/check_username.php', // Ensure this file handles the check
            data: { username: username },
            success: function (response) {
                var data = JSON.parse(response);
                if (data.exists) {
                    showToast('Username already exists. Please choose a different username.', 'error');
                    isUsernameValid = false;
                } else {
                    isUsernameValid = true;
                }
                registerBtn.disabled = !isUsernameValid; // Enable or disable the button based on validation
            },
            error: function () {
                showToast('Error checking username. Please try again.', 'error');
                isUsernameValid = false;
                registerBtn.disabled = true;
            }
        });
    }

    // Event listener for username input field
    $('#username').on('input', function () {
        var username = $(this).val().trim();
        if (username.length > 0) {
            checkUsernameExists(username);
        } else {
            isUsernameValid = false;
            registerBtn.disabled = true; // Ensure the button is disabled if input is empty
        }
    });
});
