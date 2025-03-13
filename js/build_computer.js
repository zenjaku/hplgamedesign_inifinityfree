
$(document).ready(function () {
    var currentPage = 1;
    var addedAssets = []; // Track added asset IDs
    var singleAddParts = ['PROCESSOR', 'MOTHERBOARD', 'GPU', 'POWER SUPPLY']; // Parts that can only be added once

    // Function to fetch data based on search term
    function fetchData(page) {
        currentPage = page;
        var getAssets = $('#getAssets').val().trim();

        $.ajax({
            method: 'POST',
            url: 'server/jquery/fetch_parts.php',
            data: {
                name: getAssets,
                page: page,
                exclude: addedAssets // Send the excluded assets
            },
            success: function (response) {
                console.log("Response from fetch_parts.php:", response); // Log the response for debugging
                try {
                    var data = JSON.parse(response);
                    var html = '';
                    if (data.data.length > 0) {
                        data.data.forEach(function (item) {
                            html += `<tr>
                                        <td>${item.assets_id}</td>
                                        <td>${item.assets}</td>
                                        <td>${item.brand}</td>
                                        <td>${item.model}</td>
                                        <td>${item.sn}</td>
                                        <td>
                                            <button type="button" class="btn btn-warning mb-3 add-part" data-assets-id="${item.assets_id}" data-assets="${item.assets}">
                                                <i class="fa-solid fa-circle-plus"></i>
                                            </button>
                                        </td>
                                    </tr>`;
                        });
                    } else {
                        html = '<tr><td colspan="6">No parts found</td></tr>';
                    }
                    $('#showdata').html(html);

                    // Pagination logic
                    var paginationHtml = '';
                    for (var i = 1; i <= data.totalPages; i++) {
                        var activeClass = (i === currentPage) ? 'active' : '';
                        paginationHtml += `<li class='page-item ${activeClass}'><a class='page-link' href='#' data-page='${i}'>${i}</a></li>`;
                    }
                    $('#pagination').html(paginationHtml); // Inject pagination links

                    // Attach click event handlers to pagination links
                    $('#pagination .page-link').on('click', function (e) {
                        e.preventDefault(); // Prevent default link behavior
                        var page = $(this).data('page'); // Get the page number
                        fetchData(page); // Fetch data for the selected page
                    });
                } catch (e) {
                    console.error("Error parsing response:", e);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    }

    // Fetch data on page load
    fetchData(1);

    // Add Part to Build
    $(document).on('click', '.add-part', function () {
        var row = $(this).closest('tr');
        var assets_id = $(this).data('assets-id');
        var assets = $(this).data('assets').trim().toUpperCase(); // Trim and convert to uppercase
        var brand = row.find('td:eq(2)').text();
        var model = row.find('td:eq(3)').text();
        var sn = row.find('td:eq(4)').text();

        if (addedAssets.includes(assets_id)) {
            showToast('This part is already added.', 'error');
            return;
        }

        // Check if the part is in singleAddParts and already added
        if (singleAddParts.includes(assets)) {
            var alreadyAdded = false;
            $('#showassets input[name="assets[]"]').each(function () {
                var existingAsset = $(this).val().trim().toUpperCase(); // Trim and convert to uppercase
                if (existingAsset === assets) {
                    alreadyAdded = true;
                    return false; // Break the loop
                }
            });

            if (alreadyAdded) {
                showToast(`Only one ${assets} can be added.`, 'error');
                return;
            }
        }

        // Add the part to the list
        addedAssets.push(assets_id);
        $('#showassets').append(`<tr>
                                    <td><input type="text" class="border-0" readonly name="assets_id[]" value="${assets_id}"></td>
                                    <td><input type="text" class="border-0" readonly name="assets[]" value="${assets}"></td>
                                    <td><input type="text" class="border-0" readonly name="brand[]" value="${brand}"></td>
                                    <td><input type="text" class="border-0" readonly name="model[]" value="${model}"></td>
                                    <td><input type="text" class="border-0" readonly name="sn[]" value="${sn}"></td>
                                    <td><button type="button" class="btn btn-danger mb-3 remove-part"><i class="fa-solid fa-trash-can"></i></button></td>
                                </tr>`);

        showToast(`${assets} added successfully.`, 'success');
    });

    // Remove Part from Build
    $(document).on('click', '.remove-part', function () {
        var row = $(this).closest('tr');
        var assets_id = row.find('input[name="assets_id[]"]').val();
        var assets = row.find('input[name="assets[]"]').val();

        // Remove from the list
        addedAssets = addedAssets.filter(id => id !== assets_id);
        row.remove();

        showToast(`${assets} removed successfully.`, 'success');
    });

    // Search handler for real-time search
    $('#getAssets').on('input', function () {
        fetchData(1); // Reload parts on every search term change
    });

    // Function to show toast notifications
    function showToast(message, status) {
        const bgColor = status === 'success' ? 'warning' : 'danger';
        const textColor = bgColor === 'danger' ? 'text-white' : '';
        const toast = document.createElement('div');
        toast.className = `toast show bg-${bgColor} ${textColor}`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
                        <div class="toast-body justify-content-between d-flex">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                        </div>
                        `;
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            document.body.appendChild(toastContainer);
        }
        toastContainer.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }
});

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
    var isCnameValid = false; // Flag to track if cname is valid
    const submitBtn = document.getElementById('submitBtn');

    submitBtn.disabled = true; // Correct way to disable the button initially

    // Function to check if cname exists
    function checkCnameExists(cname) {
        $.ajax({
            method: 'POST',
            url: 'server/jquery/check_cname.php', // Ensure this file handles the check
            data: { cname: cname },
            success: function (response) {
                var data = JSON.parse(response);
                if (data.exists) {
                    showToast('Computer name already exists. Please choose a different name.', 'error');
                    isCnameValid = false;
                } else {
                    isCnameValid = true;
                }
                submitBtn.disabled = !isCnameValid; // Enable or disable the button based on validation
            },
            error: function () {
                showToast('Error checking computer name. Please try again.', 'error');
                isCnameValid = false;
                submitBtn.disabled = true;
            }
        });
    }

    // Event listener for cname input field
    $('#cname').on('input', function () {
        var cname = $(this).val().trim();
        if (cname.length > 0) {
            checkCnameExists(cname);
        } else {
            isCnameValid = false;
            submitBtn.disabled = true; // Ensure the button is disabled if input is empty
        }
    });
});
