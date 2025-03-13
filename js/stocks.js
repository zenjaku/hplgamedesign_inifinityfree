document.addEventListener('DOMContentLoaded', () => {
    const overlay = document.getElementById('overlay');
    const inventory = document.getElementById('inventory');
    const inventoryBtn = document.getElementById('inventoryBtn');

    if (!overlay || !inventory || !inventoryBtn) return;

    inventoryBtn.onclick = () => {
        overlay.classList.remove('d-none');
        inventory.classList.remove('d-none');
    }

    overlay.onclick = () => {
        overlay.classList.add('d-none');
        inventory.classList.add('d-none');
    }
});

$(document).ready(function () {
    let currentPage = 1;
    let currentAsset = '';

    // Function to fetch assets via AJAX
    function fetchAssets(asset, page = 1) {
        currentAsset = asset;
        currentPage = page;

        $.ajax({
            method: 'POST',
            url: 'server/jquery/fetch_assets_count.php',
            data: {
                asset: asset,
                page: page
            },
            success: function (response) {
                try {
                    var data = JSON.parse(response);
                    var html = '';

                    if (data.data.length > 0) {
                        data.data.forEach(function (item) {
                            html += "<tr>";
                            html += "<td>" + item.brand + "</td>";
                            html += "<td>" + item.model + "</td>";
                            html += "<td>" + item.sn + "</td>";

                            var statusText = "";

                            if (item.status === "Defective") {
                                statusText = "<span class='badge bg-danger'>Defective</span>";
                            } else if (item.status === "Installed") {
                                statusText = "Installed <a href='/specs?cname_id=" + item.installed_to + "'><span class='badge bg-success'>to " + item.installed_to + "</span></a>";
                            } else {
                                statusText = "<span class='badge bg-primary'>Available</span>";
                            }

                            html += "<td>" + statusText + "</td>";
                            html += "</tr>";
                        });

                        $('#asset-data').html(html);
                        generatePagination(data.totalPages);
                    } else {
                        $('#asset-data').html("<tr><td colspan='4'>No assets found</td></tr>");
                    }
                } catch (e) {
                    console.error("Error parsing JSON response", e);
                    $('#asset-data').html("<tr><td colspan='4'>An error occurred while fetching data.</td></tr>");
                }
            },
            error: function () {
                $('#asset-data').html("<tr><td colspan='4'>An error occurred while fetching data.</td></tr>");
            }
        });
    }


    // Function to generate pagination dynamically
    function generatePagination(totalPages) {
        var paginationHtml = '';

        // Add "First" and "Previous" links
        paginationHtml += "<li class='page-item " + (currentPage === 1 ? 'disabled' : '') + "'>";
        paginationHtml += "<a class='page-link' href='#' data-page='1' title='First'><span aria-hidden='true'>&laquo;&laquo;</span></a></li>";

        paginationHtml += "<li class='page-item " + (currentPage === 1 ? 'disabled' : '') + "'>";
        paginationHtml += "<a class='page-link' href='#' data-page='" + (currentPage - 1) + "' title='Previous'><span aria-hidden='true'>&laquo;</span></a></li>";

        // Define the range of pages to display dynamically
        var pageLimit = 4; // Number of pages to display before and after the current page
        var startPage = Math.max(1, currentPage - Math.floor(pageLimit / 2));
        var endPage = Math.min(totalPages, currentPage + Math.floor(pageLimit / 2));

        // Adjust to ensure we show at least a few pages before and after
        if (endPage - startPage < pageLimit) {
            if (startPage === 1) {
                endPage = Math.min(totalPages, startPage + pageLimit - 1);
            } else if (endPage === totalPages) {
                startPage = Math.max(1, endPage - pageLimit + 1);
            }
        }

        // Add page number links with ellipsis for skipped pages
        for (var i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= startPage && i <= endPage)) {
                var activeClass = (i === currentPage) ? 'active' : '';
                paginationHtml += "<li class='page-item " + activeClass + "'><a class='page-link' href='#' data-page='" + i + "'>" + i + "</a></li>";
            } else if (i === startPage - 1 || i === endPage + 1) {
                paginationHtml += "<li class='page-item disabled'><span class='page-link'>...</span></li>";
            }
        }

        // Add "Next" and "Last" links
        paginationHtml += "<li class='page-item " + (currentPage === totalPages ? 'disabled' : '') + "'>";
        paginationHtml += "<a class='page-link' href='#' data-page='" + (currentPage + 1) + "' title='Next'><span aria-hidden='true'>&raquo;</span></a></li>";

        paginationHtml += "<li class='page-item " + (currentPage === totalPages ? 'disabled' : '') + "'>";
        paginationHtml += "<a class='page-link' href='#' data-page='" + totalPages + "' title='Last'><span aria-hidden='true'>&raquo;&raquo;</span></a></li>";

        // Inject the pagination HTML
        $('#pagination').html(paginationHtml);
    }

    // Handle asset link click
    $(document).on('click', '.asset-link', function (e) {
        e.preventDefault();

        // Remove active class from all links
        $('.asset-link').removeClass('active');

        // Add active class to clicked link
        $(this).addClass('active');

        // Fetch assets for selected component
        var asset = $(this).data('asset');
        fetchAssets(asset, 1);  // Fetch assets for page 1
    });

    // Handle pagination link click
    $(document).on('click', '#pagination .page-link', function (e) {
        e.preventDefault();
        var page = $(this).data('page');
        fetchAssets(currentAsset, page);  // Fetch assets for the selected page
    });

    // Automatically select and load the first asset on page load
    var firstAssetLink = $('.asset-link').first();
    if (firstAssetLink.length) {
        firstAssetLink.addClass('active');
        fetchAssets(firstAssetLink.data('asset'), 1);  // Load the first asset
    }
});