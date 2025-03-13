
$(document).ready(function () {
    $('#search_employee').click(function (event) {
        event.preventDefault(); // Prevent form submission
        var searchQuery = $('#search_employee_id').val().trim();

        if (searchQuery === "") {
            return;
        }

        $.ajax({
            method: 'POST',
            url: 'server/jquery/search_employee.php',
            data: { searchQuery: searchQuery },
            success: function (response) {
                try {
                    var data = JSON.parse(response);
                    var employeeDropdown = $('#allocate_employee_id');

                    employeeDropdown.empty(); // Clear existing options
                    // employeeDropdown.append('<option value="">Select Employee</option>');

                    if (data.length === 0) {
                        showToast("No employee data found");
                        return;
                    }

                    data.forEach(function (item) {
                        let newOption = $('<option>', {
                            value: item.employee_id,
                            text: item.fname + ' ' + item.lname
                        });
                        employeeDropdown.append(newOption);
                    });

                    $('#allocate_employee_id').trigger('change');
                    console.log($('#allocate_employee_id').html());


                    // console.log("Dropdown updated with employees:", employeeDropdown.html());
                } catch (e) {
                    console.error("Error parsing JSON response", e);
                }
            },
            error: function () {
                showToast("An error occurred while fetching data.");
            }
        });
    });

    //transfer allocation **from**
    $('#search_original').click(function (event) {
        event.preventDefault(); // Prevent form submission
        var originalQuery = $('#search_original_id').val().trim();

        if (originalQuery === "") {
            return;
        }

        $.ajax({
            method: 'POST',
            url: 'server/jquery/search_employee.php',
            data: { originalQuery: originalQuery },
            success: function (response) {
                try {
                    var data = JSON.parse(response);
                    var employeeDropdown = $('#original_employee_id');

                    employeeDropdown.empty(); // Clear existing options
                    // employeeDropdown.append('<option value="">Select Employee</option>');

                    if (data.length === 0) {
                        showToast("No employee data found");
                        return;
                    }

                    data.forEach(function (item) {
                        let newOption = $('<option>', {
                            value: item.employee_id,
                            text: item.fname + ' ' + item.lname
                        });
                        employeeDropdown.append(newOption);
                    });

                    $('#original_employee_id').trigger('change');
                    console.log($('#original_employee_id').html());


                    // console.log("Dropdown updated with employees:", employeeDropdown.html());
                } catch (e) {
                    console.error("Error parsing JSON response", e);
                }
            },
            error: function () {
                showToast("An error occurred while fetching data.");
            }
        });
    });

    //transfer allocation **to**
    $('#transfer_employee').click(function (event) {
        event.preventDefault(); // Prevent form submission
        var transferQuery = $('#search_transfer').val().trim();

        if (transferQuery === "") {
            return;
        }

        $.ajax({
            method: 'POST',
            url: 'server/jquery/search_employee.php',
            data: { transferQuery: transferQuery },
            success: function (response) {
                try {
                    var data = JSON.parse(response);
                    var employeeDropdown = $('#transfer_employee_id');

                    employeeDropdown.empty(); // Clear existing options
                    // employeeDropdown.append('<option value="">Select Employee</option>');

                    if (data.length === 0) {
                        showToast("No employee data found");
                        return;
                    }

                    data.forEach(function (item) {
                        let newOption = $('<option>', {
                            value: item.employee_id,
                            text: item.fname + ' ' + item.lname
                        });
                        employeeDropdown.append(newOption);
                    });

                    $('#transfer_employee_id').trigger('change');
                    console.log($('#transfer_employee_id').html());


                    // console.log("Dropdown updated with employees:", employeeDropdown.html());
                } catch (e) {
                    console.error("Error parsing JSON response", e);
                }
            },
            error: function () {
                showToast("An error occurred while fetching data.");
            }
        });
    });

    // return allocation
    $('#return_id').click(function (event) {
        event.preventDefault(); // Prevent form submission
        var originalQuery = $('#search_id').val().trim();

        if (originalQuery === "") {
            return;
        }

        $.ajax({
            method: 'POST',
            url: 'server/jquery/search_employee.php',
            data: { originalQuery: originalQuery },
            success: function (response) {
                try {
                    var data = JSON.parse(response);
                    var employeeDropdown = $('#return_employee_id');

                    employeeDropdown.empty(); // Clear existing options
                    // employeeDropdown.append('<option value="">Select Employee</option>');

                    if (data.length === 0) {
                        showToast("No employee data found");
                        return;
                    }

                    data.forEach(function (item) {
                        let newOption = $('<option>', {
                            value: item.employee_id,
                            text: item.fname + ' ' + item.lname
                        });
                        employeeDropdown.append(newOption);
                    });

                    $('#return_employee_id').trigger('change');
                    console.log($('#return_employee_id').html());


                    // console.log("Dropdown updated with employees:", employeeDropdown.html());
                } catch (e) {
                    console.error("Error parsing JSON response", e);
                }
            },
            error: function () {
                showToast("An error occurred while fetching data.");
            }
        });
    });


    function showToast(message) {
        var toast = $('<div class="toast align-items-center text-white bg-danger border-0 position-fixed top-0 end-0 m-3" role="alert" aria-live="assertive" aria-atomic="true">' +
            '<div class="d-flex">' +
            '<div class="toast-body">' + message + '</div>' +
            '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>' +
            '</div>' +
            '</div>');

        $('body').append(toast);
        var toastElement = new bootstrap.Toast(toast[0]);
        toastElement.show();

        setTimeout(function () {
            toast.remove();
        }, 3000);
    }
});

// script for allocating pc
$(document).ready(function () {

    $('#allocate_cname_id').on("change", function () {
        var assetID = $(this).val().trim();
        var formID = $(this).closest('form').attr('id');

        if (assetID === "") {
            $("#showdata").html("");
            return;
        }

        $.ajax({
            method: 'POST',
            url: 'server/jquery/employee_allocation.php',
            data: {
                assetID: assetID,
                formType: formID
            },
            success: function (response) {
                try {
                    var data = JSON.parse(response);
                    var html = '';

                    if (data.message) {
                        html = "<tr><td colspan='6'>No assets found</td></tr>";
                    } else {
                        data.forEach(function (item) {
                            html += "<tr>";
                            html += "<td class='p-4'><div class='form-floating'><input type='text' class='form-control input-fields' readonly name='assets_id' value='" + item.assets_id + "'><label>Assets ID</label></div></td>";
                            html += "<td class='p-4'><div class='form-floating'><input type='text' class='form-control input-fields' readonly name='c_assets' value='" + item.assets + "'><label>Assets</label></div></td>";
                            html += "<td class='p-4'><div class='form-floating'><input type='text' class='form-control input-fields' readonly name='brand' value='" + item.brand + "'><label>Brand</label></div></td>";
                            html += "<td class='p-4'><div class='form-floating'><input type='text' class='form-control input-fields' readonly name='model' value='" + item.model + "'><label>Model</label></div></td>";
                            html += "<td class='p-4'><div class='form-floating'><input type='text' class='form-control input-fields' readonly name='sn' value='" + item.sn + "'><label>Serial Number</label></div></td>";
                            html += "</tr>";
                        });
                    }
                    $("#showdata").html(html);
                } catch (e) {
                    console.error("Error parsing JSON response", e);
                    $("#showdata").html("<tr><td colspan='6'>An error occurred while fetching data.</td></tr>");
                }
            },
            error: function () {
                $("#showdata").html("<tr><td colspan='6'>An error occurred while fetching data.</td></tr>");
            }
        });
    });
});

// script for transferring pc
$(document).ready(function () {
    $('#original_employee_id').on("change", function () {
        var transferID = $(this).val().trim();
        var formID = $(this).closest('form').attr('id');

        if (transferID === "") {
            $("#showdata").html("");
            return;
        }

        $.ajax({
            method: 'POST',
            url: 'server/jquery/transfer_allocation.php',
            data: {
                transferID: transferID,
                formType: formID
            },
            success: function (response) {
                try {
                    var data = JSON.parse(response);
                    var html = '';

                    if (data.message) {
                        html = "<tr><td colspan='6'>No assets found</td></tr>";
                    } else {
                        data.forEach(function (item) {
                            html += "<tr>";
                            html += "<td class='p-4'><div class='form-floating'><input type='text' class='form-control input-fields' readonly name='assets_id' value='" + item.assets_id + "'><label>Assets ID</label></div></td>";
                            html += "<td class='p-4'><div class='form-floating'><input type='text' class='form-control input-fields' readonly name='c_assets' value='" + item.assets + "'><label>Assets</label></div></td>";
                            html += "<td class='p-4'><div class='form-floating'><input type='text' class='form-control input-fields' readonly name='brand' value='" + item.brand + "'><label>Brand</label></div></td>";
                            html += "<td class='p-4'><div class='form-floating'><input type='text' class='form-control input-fields' readonly name='model' value='" + item.model + "'><label>Model</label></div></td>";
                            html += "<td class='p-4'><div class='form-floating'><input type='text' class='form-control input-fields' readonly name='sn' value='" + item.sn + "'><label>Serial Number</label></div></td>";
                            html += "</tr>";
                        });
                    }
                    $("#showdata").html(html);
                } catch (e) {
                    console.error("Error parsing JSON response", e);
                    $("#showdata").html("<tr><td colspan='6'>An error occurred while fetching data.</td></tr>");
                }
            },
            error: function () {
                $("#showdata").html("<tr><td colspan='6'>An error occurred while fetching data.</td></tr>");
            }
        });
    });
});

// script for returning pc
$(document).ready(function () {
    $('#return_employee_id').on("change", function () {
        var returnID = $(this).val().trim();
        var formID = $(this).closest('form').attr('id');

        if (returnID === "") {
            $("#showdata").html("");
            return;
        }

        $.ajax({
            method: 'POST',
            url: 'server/jquery/return_allocations.php',
            data: {
                returnID: returnID,
                formType: formID
            },
            success: function (response) {
                try {
                    var data = JSON.parse(response);
                    var html = '';

                    if (data.message) {
                        html = "<tr><td colspan='6'>No assets found</td></tr>";
                    } else {
                        data.forEach(function (item) {
                            html += "<tr>";
                            html += "<td class='p-4'><div class='form-floating'><input type='text' class='form-control input-fields' readonly name='assets_id' value='" + item.assets_id + "'><label>Assets ID</label></div></td>";
                            html += "<td class='p-4'><div class='form-floating'><input type='text' class='form-control input-fields' readonly name='c_assets' value='" + item.assets + "'><label>Assets</label></div></td>";
                            html += "<td class='p-4'><div class='form-floating'><input type='text' class='form-control input-fields' readonly name='brand' value='" + item.brand + "'><label>Brand</label></div></td>";
                            html += "<td class='p-4'><div class='form-floating'><input type='text' class='form-control input-fields' readonly name='model' value='" + item.model + "'><label>Model</label></div></td>";
                            html += "<td class='p-4'><div class='form-floating'><input type='text' class='form-control input-fields' readonly name='sn' value='" + item.sn + "'><label>Serial Number</label></div></td>";
                            html += "</tr>";
                        });
                    }
                    $("#showdata").html(html);
                } catch (e) {
                    console.error("Error parsing JSON response", e);
                    $("#showdata").html("<tr><td colspan='6'>An error occurred while fetching data.</td></tr>");
                }
            },
            error: function () {
                $("#showdata").html("<tr><td colspan='6'>An error occurred while fetching data.</td></tr>");
            }
        });
    });
});