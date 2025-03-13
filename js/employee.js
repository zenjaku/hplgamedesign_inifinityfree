function updateFileName() {
    const fileInput = document.getElementById('tsv');
    const label = document.getElementById('file-name-label');
    if (fileInput.files.length > 0) {
        label.textContent = fileInput.files[0].name;  // Update label with file name
    } else {
        label.textContent = 'Upload TSV file';  // Default text if no file is selected
    }
}

//employee table
$(document).ready(function () {
    var currentPage = 1; // Track the current page globally

    function fetchData(page) {
        currentPage = page; // Update the global currentPage variable
        var getAssets = $('#getAssets').val().trim(); // Get search query
        var statusFilter = $('input[name="status"]:checked').val(); // Get selected radio button value

        $.ajax({
            method: 'POST',
            url: 'server/jquery/fetch_employee.php',
            data: {
                name: getAssets,  // Include search query
                status: statusFilter, // Include status filter
                page: page        // Include current page for pagination
            },
            success: function (response) {
                try {
                    var data = JSON.parse(response);
                    if (data.data.length > 0) {
                        var html = '';

                        data.data.forEach(function (item) {
                            // Only make button clickable if employee is allocated AND allocation is active
                            var buttonHtml = item.allocated == 1
                                ? `<a href="/admin-view?employee_id=${item.employee_id}">
                                        <button class="btn btn-dark">View</button>
                                    </a>`
                                : `<button class="btn btn-secondary" disabled>View</button>`;

                            var resignButtonHtml = item.status == 'WFH' || item.status == 'NEW HIRE' || item.status == 'TEMP WFH'
                                ? `<a href="/resigned?employee_id=${item.employee_id}">
                                        <button class="btn btn-danger">Resigned?</button>
                                    </a>`
                                : `<button class="btn btn-secondary" disabled>Resigned</button>`;

                            html += "<tr>";
                            html += "<td>" + item.employee_id + "</td>";
                            html += "<td>" + item.fname + ' ' + item.lname + "</td>";
                            html += "<td>" + item.email + "</td>";
                            html += "<td>" + item.dept + "</td>";
                            html += "<td>" + item.status + "</td>";
                            html += `<td>${buttonHtml}</td>`;
                            html += `<td>${resignButtonHtml}</td>`;
                            html += "</tr>";
                        });

                        $('#showdata').html(html); // Inject data into the table

                        // Create pagination links dynamically
                        var paginationHtml = '';

                        // Add the "First" and "Previous" links
                        paginationHtml += "<li class='page-item " + (currentPage === 1 ? 'disabled' : '') + "'>";
                        paginationHtml += "<a class='page-link' href='#' data-page='1' title='First'><span aria-hidden='true'>&laquo;&laquo;</span></a></li>";

                        paginationHtml += "<li class='page-item " + (currentPage === 1 ? 'disabled' : '') + "'>";
                        paginationHtml += "<a class='page-link' href='#' data-page='" + (currentPage - 1) + "' title='Previous'><span aria-hidden='true'>&laquo;</span></a></li>";

                        // Define the range of pages to display dynamically
                        var pageLimit = 4; // Number of pages to display before and after the current page
                        var startPage = Math.max(1, currentPage - Math.floor(pageLimit / 2));
                        var endPage = Math.min(data.totalPages, currentPage + Math.floor(pageLimit / 2));

                        // Adjust to ensure we show at least a few pages before and after
                        if (endPage - startPage < pageLimit) {
                            if (startPage === 1) {
                                endPage = Math.min(data.totalPages, startPage + pageLimit - 1);
                            } else if (endPage === data.totalPages) {
                                startPage = Math.max(1, endPage - pageLimit + 1);
                            }
                        }

                        // Add page number links with ellipsis for skipped pages
                        for (var i = 1; i <= data.totalPages; i++) {
                            if (i === 1 || i === data.totalPages || (i >= startPage && i <= endPage)) {
                                var activeClass = (i === currentPage) ? 'active' : '';
                                paginationHtml += "<li class='page-item " + activeClass + "'><a class='page-link' href='#' data-page='" + i + "'>" + i + "</a></li>";
                            } else if (i === startPage - 1 || i === endPage + 1) {
                                paginationHtml += "<li class='page-item disabled'><span class='page-link'>...</span></li>";
                            }
                        }

                        // Add the "Next" and "Last" links
                        paginationHtml += "<li class='page-item " + (currentPage === data.totalPages ? 'disabled' : '') + "'>";
                        paginationHtml += "<a class='page-link' href='#' data-page='" + (currentPage + 1) + "' title='Next'><span aria-hidden='true'>&raquo;</span></a></li>";

                        paginationHtml += "<li class='page-item " + (currentPage === data.totalPages ? 'disabled' : '') + "'>";
                        paginationHtml += "<a class='page-link' href='#' data-page='" + data.totalPages + "' title='Last'><span aria-hidden='true'>&raquo;&raquo;</span></a></li>";

                        paginationHtml += '</ul></nav></div>';

                        $('#pagination').html(paginationHtml); // Inject pagination links

                    } else {
                        $('#showdata').html("<tr><td colspan='7'>No employee found</td></tr>");
                    }
                } catch (e) {
                    console.error("Error parsing JSON response", e);
                    $('#showdata').html("<tr><td colspan='7'>An error occurred while fetching data.</td></tr>");
                }
            },
            error: function () {
                $('#showdata').html("<tr><td colspan='7'>An error occurred while fetching data.</td></tr>");
            }
        });
    }

    // Initialize data fetch
    fetchData(1);

    // Handle pagination clicks
    $(document).on('click', '#pagination .page-link', function (e) {
        e.preventDefault();
        fetchData($(this).data('page'));
    });

    // Handle status filter changes
    $(document).on('change', 'input[name="status"]', function () {
        fetchData(1);
    });

    // Real-time search functionality
    $('#getAssets').on('keyup', function () {
        fetchData(1);
    });
});


//employee table
$(document).ready(function () {
    var currentPage = 1; // Track the current page globally

    function fetchData(page) {
        currentPage = page; // Update the global currentPage variable

        $.ajax({
            method: 'POST',
            url: 'server/jquery/fetch_resigned_employee.php',
            data: {
                page: page        // Include current page for pagination
            },
            success: function (response) {
                try {
                    var data = JSON.parse(response);
                    if (data.data.length > 0) {
                        var html = '';

                        data.data.forEach(function (item) {
                            html += "<tr>";
                            html += "<td>" + item.employee_id + "</td>";
                            html += "<td>" + item.fname + ' ' + item.lname + "</td>";
                            html += "</tr>";
                        });

                        $('#resignedData').html(html); // Inject data into the table

                        // Create pagination links dynamically
                        var paginationHtml = '';

                        // Add the "First" and "Previous" links
                        paginationHtml += "<li class='page-item " + (currentPage === 1 ? 'disabled' : '') + "'>";
                        paginationHtml += "<a class='page-link' href='#' data-page='1' title='First'><span aria-hidden='true'>&laquo;&laquo;</span></a></li>";

                        paginationHtml += "<li class='page-item " + (currentPage === 1 ? 'disabled' : '') + "'>";
                        paginationHtml += "<a class='page-link' href='#' data-page='" + (currentPage - 1) + "' title='Previous'><span aria-hidden='true'>&laquo;</span></a></li>";

                        // Define the range of pages to display dynamically
                        var pageLimit = 4; // Number of pages to display before and after the current page
                        var startPage = Math.max(1, currentPage - Math.floor(pageLimit / 2));
                        var endPage = Math.min(data.totalPages, currentPage + Math.floor(pageLimit / 2));

                        // Adjust to ensure we show at least a few pages before and after
                        if (endPage - startPage < pageLimit) {
                            if (startPage === 1) {
                                endPage = Math.min(data.totalPages, startPage + pageLimit - 1);
                            } else if (endPage === data.totalPages) {
                                startPage = Math.max(1, endPage - pageLimit + 1);
                            }
                        }

                        // Add page number links with ellipsis for skipped pages
                        for (var i = 1; i <= data.totalPages; i++) {
                            if (i === 1 || i === data.totalPages || (i >= startPage && i <= endPage)) {
                                var activeClass = (i === currentPage) ? 'active' : '';
                                paginationHtml += "<li class='page-item " + activeClass + "'><a class='page-link' href='#' data-page='" + i + "'>" + i + "</a></li>";
                            } else if (i === startPage - 1 || i === endPage + 1) {
                                paginationHtml += "<li class='page-item disabled'><span class='page-link'>...</span></li>";
                            }
                        }

                        // Add the "Next" and "Last" links
                        paginationHtml += "<li class='page-item " + (currentPage === data.totalPages ? 'disabled' : '') + "'>";
                        paginationHtml += "<a class='page-link' href='#' data-page='" + (currentPage + 1) + "' title='Next'><span aria-hidden='true'>&raquo;</span></a></li>";

                        paginationHtml += "<li class='page-item " + (currentPage === data.totalPages ? 'disabled' : '') + "'>";
                        paginationHtml += "<a class='page-link' href='#' data-page='" + data.totalPages + "' title='Last'><span aria-hidden='true'>&raquo;&raquo;</span></a></li>";

                        paginationHtml += '</ul></nav></div>';

                        $('#resignedPagination').html(paginationHtml); // Inject pagination links

                    } else {
                        $('#resignedData').html("<tr><td colspan='7'>No employee found</td></tr>");
                    }
                } catch (e) {
                    console.error("Error parsing JSON response", e);
                    $('#resignedData').html("<tr><td colspan='7'>An error occurred while fetching data.</td></tr>");
                }
            },
            error: function () {
                $('#resignedData').html("<tr><td colspan='7'>An error occurred while fetching data.</td></tr>");
            }
        });
    }

    // Initialize data fetch
    fetchData(1);

    // Handle pagination clicks
    $(document).on('click', '#resignedPagination .page-link', function (e) {
        e.preventDefault();
        fetchData($(this).data('page'));
    });
});