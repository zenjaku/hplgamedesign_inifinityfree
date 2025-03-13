<div class="container-fluid table-responsive d-flex flex-column" id="inventoryDashboard">
    <h2>Inventory Dashboard</h2>
    <div class="input-group" id="searchAssets">
        <label for="getAssets" class="input-group-text bg-dark text-white border border-black">Search</label>
        <input type="text" id="getAssets" placeholder="SEARCH BY USING COMPUTER ID OR COMPUTER NAME"
            class="form-control border border-black input-search" />
    </div>
    <div class="admin-btn d-flex justify-content-between align-items-center">
        <div class="d-flex gap-3">
            <label>Filter by Available PC: </label>
            <div id="pcFilter" class="d-flex gap-2 flex-wrap">
                <!-- jquery -->
            </div>
        </div>
        <div class="d-flex gap-2 mb-1">
            <a href="/stocks">
                <button class="btn btn-dark w-auto">Parts Dashboard</button>
            </a>
            <a href="/add">
                <button type="button" class="btn btn-danger w-auto" id="addAssets">Add Assets</button>
            </a>
        </div>
    </div>
    <table class="table text-center table-bordered">
        <thead class="table-dark">
            <tr>
                <th scope="col">Computer ID</th>
                <th scope="col">Computer Name</th>
                <th scope="col">Specifications</th>
                <th scope="col">History</th>
            </tr>
        </thead>
        <tbody id="showdata">
        </tbody>
    </table>
    <div class="text-white">
        <nav>
            <ul class="bg-dark rounded p-2 d-flex justify-content-center align-items-center gap-3 border border-2 border-white"
                id="pagination">
                <!-- Pagination links will be inserted dynamically by JavaScript -->
            </ul>
        </nav>
    </div>
    <script>
        $(document).ready(function () {
            var currentPage = 1; // Track the current page globally

            // Function to fetch data with pagination
            function fetchData(page) {
                currentPage = page;
                var getAssets = $('#getAssets').val().trim();
                var pcFilter = $('input[name="pcFilter"]:checked').val() || '';

                $.ajax({
                    method: 'POST',
                    url: 'server/jquery/inventory.php',
                    data: {
                        name: getAssets,
                        pcFilter: pcFilter,
                        page: page
                    },
                    success: function (response) {
                        try {
                            var data = JSON.parse(response);

                            // Render pc filter radio buttons
                            var pcFilterHtml = `
                            <input type="radio" id="status_all" name="pcFilter" value="" ${pcFilter === '' ? 'checked' : ''}>
                            <label for="status_all" class="me-2">All</label>
                            <input type="radio" id="status_available" name="pcFilter" value="Available" ${pcFilter === 'Available' ? 'checked' : ''}>
                            <label for="status_available" class="me-2">Available</label>
                            <input type="radio" id="status_not" name="pcFilter" value="Not Available" ${pcFilter === 'Not Available' ? 'checked' : ''}>
                            <label for="status_not" class="me-2">Not Available</label>
                        `;
                            $('#pcFilter').html(pcFilterHtml);

                            if (data.message) {
                                $("#showdata").html("<tr><td colspan='5'>No assets found</td></tr>");
                                $('#pagination').html('');
                            } else {
                                var html = '';
                                data.data.forEach(function (item) {
                                    html += "<tr>";
                                    html += "<td>" + item.cname_id + "</td>";
                                    html += "<td>" + item.cname + "</td>";
                                    html += "<td><a href='/specs?cname_id=" + item.cname_id + "'><button type='button' class='btn btn-dark'>View Specifications</button></a></td>";
                                    html += "<td><a href='/history?cname_id=" + item.cname_id + "'><button type='button' class='btn btn-warning'>View History</button></a></td>";
                                    html += "</tr>";
                                });

                                $("#showdata").html(html);

                                // Pagination logic
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
                            }
                        } catch (e) {
                            console.error("Error parsing JSON response", e);
                            $("#showdata").html("<tr><td colspan='5'>An error occurred while fetching data.</td></tr>");
                            $('#pagination').html(''); // Clear pagination if no data
                        }
                    },
                    error: function () {
                        $("#showdata").html("<tr><td colspan='5'>An error occurred while fetching data.</td></tr>");
                        $('#pagination').html(''); // Clear pagination if no data
                    }
                });
            }

            // Initial data fetch when the page loads
            fetchData(1);

            // Handle pagination link clicks
            $(document).on('click', '#pagination .page-link', function (e) {
                e.preventDefault();
                var page = $(this).data('page');
                fetchData(page); // Fetch data for the clicked page
            });

            // Search function when typing in the search input
            $('#getAssets').on('keyup', function () {
                fetchData(1); // Reload data from the first page when the search input changes
            });

            // Handle pc filter change
            $(document).on('change', 'input[name="pcFilter"]', function () {
                fetchData(1); // Reload data from the first page when the filter changes
            });
        });
    </script>
</div>