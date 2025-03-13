
$(document).ready(function () {
    var currentPage = 1; // Track the current page globally

    // Function to fetch data based on the search query
    function fetchData(page, searchQuery = '', assetFilter = '', statusFilter = '') {
        currentPage = page; // Update the global currentPage variable
        $.ajax({
            method: 'POST',
            url: 'server/jquery/parts.php',
            data: {
                name: searchQuery,  // Send search query (empty if no search)
                assetFilter: assetFilter, // Send asset filter (empty if no filter)
                statusFilter: statusFilter, // Send asset filter (empty if no filter)
                page: page          // Include current page for pagination
            },
            success: function (response) {
                try {
                    var data = JSON.parse(response);

                    // Render asset filter radio buttons
                    var assetFilterHtml = `
                    <input type="radio" id="assets" name="assetFilter" value="" ${assetFilter === '' ? 'checked' : ''}>
                    <label for="assets" class="me-2">All</label>
                    `;

                    if (data.assetNames && data.assetNames.length > 0) {
                        data.assetNames.forEach(function (assetName, index) {
                            let assetId = `asset_${index}`;
                            assetFilterHtml += `
                            <input type="radio" id="${assetId}" name="assetFilter" value="${assetName}" ${assetFilter === assetName ? 'checked' : ''}>
                            <label for="${assetId}" class="me-2">${assetName}</label>
                            `;
                        });
                    }

                    $('#assetFilter').html(assetFilterHtml);

                    var statusFilterHtml = `
                        <input type="radio" id="status_all" name="statusFilter" value="" ${statusFilter === '' ? 'checked' : ''}>
                        <label for="status_all" class="me-2">All</label>
                        <input type="radio" id="status_installed" name="statusFilter" value="Installed" ${statusFilter === 'Installed' ? 'checked' : ''}>
                        <label for="status_installed" class="me-2">Installed</label>
                        <input type="radio" id="status_available" name="statusFilter" value="Available" ${statusFilter === 'Available' ? 'checked' : ''}>
                        <label for="status_available" class="me-2">Available</label>
                        <input type="radio" id="status_defective" name="statusFilter" value="Defective" ${statusFilter === 'Defective' ? 'checked' : ''}>
                        <label for="status_defective" class="me-2">Defective</label>
                    `;
                    $('#statusFilter').html(statusFilterHtml);


                    if (data.data.length > 0) {
                        var html = '';

                        data.data.forEach(function (item) {
                            html += "<tr>";
                            html += "<td>" + item.cname + "</td>"; // Associated PC name
                            html += "<td>" + item.assets_id + "</td>";
                            html += "<td>" + item.assets + "</td>";
                            html += "<td>" + item.brand + "</td>";
                            html += "<td>" + item.model + "</td>";
                            html += "<td>" + item.sn + "</td>";

                            // Check if asset is installed or defective
                            let installDisabled = (item.status === 'Defective' || item.status === 'Installed') ? 'disabled' : '';
                            let buttonClass = (item.status === 'Defective' || item.status === 'Installed') ? 'btn btn-secondary text-xs' : 'btn btn-dark';
                            let buttonText = item.status === 'Defective' ? 'DEFECTIVE' : (item.status === 'Installed' ? 'Installed to ' + item.cname_id : 'Install');

                            // Install button
                            html += `<td>
                    <a href="/add-to?assets_id=${item.assets_id}" style="${installDisabled ? 'pointer-events: none; opacity: 0.5;' : ''}">
                        <button class="${buttonClass}" ${installDisabled}>
                            ${buttonText}
                        </button>
                    </a>
                </td>`;

                            // Defective button
                            if (item.status === 'Defective') {
                                html += `<td>
                        <a href="javascript:void(0);" style="pointer-events: none; opacity: 0.5;">
                            <button class="btn btn-secondary" disabled>
                                DEFECTIVE
                            </button>
                        </a>
                    </td>`;
                            } else {
                                html += `<td>
                        <a href="/defective?assets_id=${item.assets_id}">
                            <button class="btn btn-danger">
                                DEFECTIVE ?
                            </button>
                        </a>
                    </td>`;
                            }

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
                        $('#showdata').html("<tr><td class='text-center' colspan='8'>No parts found</td></tr>");
                        $('#pagination').html(''); // Clear pagination if no data
                    }
                } catch (e) {
                    console.error("Error parsing JSON response", e);
                    $('#showdata').html("<tr><td colspan='8'>An error occurred while fetching data.</td></tr>");
                    $('#pagination').html(''); // Clear pagination if no data
                }
            },
            error: function () {
                $('#showdata').html("<tr><td colspan='8'>An error occurred while fetching data.</td></tr>");
                $('#pagination').html(''); // Clear pagination if no data
            }
        });
    }

    // Initial data fetch when the page loads (with empty search query)
    fetchData(1, '');

    // Handle pagination link clicks
    $(document).on('click', '#pagination .page-link', function (e) {
        e.preventDefault();
        var page = $(this).data('page');
        var getAssets = $('#getAssets').val().trim();
        var assetFilter = $('input[name="assetFilter"]:checked').val();
        var statusFilter = $('input[name="statusFilter"]:checked').val();
        fetchData(page, getAssets, assetFilter, statusFilter);
    });

    // Search function when typing in the search input
    $('#getAssets').on('keyup', function () {
        var searchQuery = $(this).val().trim();
        var assetFilter = $('input[name="assetFilter"]:checked').val();
        var statusFilter = $('input[name="statusFilter"]:checked').val();
        fetchData(1, searchQuery, assetFilter, statusFilter);
    });

    // Handle asset filter change
    $(document).on('change', 'input[name="assetFilter"]', function () {
        var searchQuery = $('#getAssets').val().trim();
        var assetFilter = $(this).val();
        var statusFilter = $('input[name="statusFilter"]:checked').val();
        fetchData(1, searchQuery, assetFilter, statusFilter);
    });

    // Handle status filter change
    $(document).on('change', 'input[name="statusFilter"]', function () {
        var searchQuery = $('#getAssets').val().trim();
        var assetFilter = $('input[name="assetFilter"]:checked').val();
        var statusFilter = $(this).val();
        fetchData(1, searchQuery, assetFilter, statusFilter);
    });
});