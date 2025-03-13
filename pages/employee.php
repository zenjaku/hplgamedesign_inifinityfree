<?php
/**
 * Employee Management Page
 * Handles employee data display and search functionality
 */
?>
<div class="container-fluid table-responsive d-flex flex-column" id="employeeDashboard">
    <div class="d-flex justify-content-between align-items-center mb-1">
        <h2>Employee Data</h2>
        <button class="btn btn-danger" id="resignedBtn">Resigned Employee</button>
    </div>
    <div class="input-group" id="searchAssets">
        <input type="text" id="getAssets" placeholder="SEARCH EMPLOYEE HERE"
            class="form-control border border-black input-search" />
        <label for="getAssets" class="input-group-text bg-dark text-white border border-black">Search</label>
    </div>
    <div class="admin-btn d-flex justify-content-between align-items-center">
        <form action="/tsv" method="post" id="csvForm" enctype="multipart/form-data" class="mb-1">
            <div class="input-group">
                <label for="tsv" class="input-group-text bg-dark border border-black text-white">Import Employee
                    Data</label>
                <label for="tsv" class="input-group-text border border-black" id="file-name-label">Upload TSV
                    file</label>
                <input type="file" name="tsv" id="tsv" class="form-control" accept=".tsv" style="display:none;"
                    onchange="updateFileName()" required>
                <button type="submit" name="csvBtn" class="btn btn-dark">Submit</button>
            </div>
        </form>
        <div class="d-flex gap-3">
            <label>Filter: </label>
            <div id="statusFilter" class="d-flex gap-2">
                <input type="radio" id="all" name="status" value="" checked>
                <label for="all" class="me-2">All</label>

                <input type="radio" id="allocated" name="status" value="1">
                <label for="allocated" class="me-2">Allocated</label>

                <input type="radio" id="not" name="status" value="0">
                <label for="not" class="me-2">No Allocated</label>
            </div>
        </div>
    </div>

    <table class="table text-center table-bordered">
        <thead class="table-dark">
            <tr>
                <th scope="col">Employee ID</th>
                <th scope="col">Name</th>
                <th scope="col">Email Address</th>
                <th scope="col">Department</th>
                <th scope="col">Status</th>
                <th scope="col">Documents</th>
                <th scope="col">Action</th>
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
</div>

<div class="overlay d-none" id="overlay"></div>
<div class="d-none rounded bg-body-tertiary p-3" id="resignedEmployee">
    <h2>Resigned Employee</h2>
    <table class="table text-center table-bordered mt-4">
        <thead class="table-dark">
            <tr>
                <th scope="col">Employee ID</th>
                <th scope="col">Name</th>
            </tr>
        </thead>
        <tbody id="resignedData">
        </tbody>
    </table>
    <div class="text-white">
        <nav>
            <ul class="bg-dark rounded p-2 d-flex justify-content-center align-items-center gap-3 border border-2 border-white"
                id="resignedPagination">
                <!-- Pagination links will be inserted dynamically by JavaScript -->
            </ul>
        </nav>
    </div>
</div>

<script src="../js/employee.js"></script>