<div>
    <h2 class="my-2 mb-4">Parts and Components</h2>
    <div class="input-group my-2" id="searchAssets">
        <input type="text" id="getAssets" placeholder="SEARCH ASSETS HERE"
            class="form-control border border-black input-search" />
        <label for="getAssets" class="input-group-text bg-dark text-white border border-black">Search</label>
    </div>
    <div class="d-flex justify-content-between">
        <!-- Asset Filter -->
        <div class="d-flex gap-3 mb-3">
            <label>Filter by Asset: </label>
            <div id="assetFilter" class="d-flex gap-2 flex-wrap">
                <input type="radio" id="assets" name="assetFilter" value="">
                <label for="assets">All</label>
            </div>
        </div>
        <!-- Status Filter -->
        <div class="d-flex gap-3 mb-3">
            <label>Filter by Status: </label>
            <div id="statusFilter" class="d-flex gap-2 flex-wrap">
                <!-- <input type="radio" id="assetsStatus" name="statusFilter" value="">
                    <label for="assetsStatus">All</label> -->
            </div>
        </div>
    </div>
    <table class="table table-bordered text-center">
        <thead class="table-dark">
            <tr>
                <th>ASSOCIATED PC</th>
                <th>ASSETS ID</th>
                <th>SPECIFIED</th>
                <th>BRAND</th>
                <th>MODEL</th>
                <th>S/N</th>
                <th>ADD TO</th>
                <th>DEFECTIVE ?</th>
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
<script src="../js/parts.js"></script>