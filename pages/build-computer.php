<?php
require('server/drop-downs/parts.php');
?>
<div class="row d-flex flex-column justify-content-center align-items-center">
    <div class="col-12 py-5">
        <div class="card">
            <div class="card-header">
                <h3>Build PC</h3>
            </div>
            <form action="/build-pc" method="post" id="buildForm">
                <div class="card-body">
                    <div class="form-floating mb-3">
                        <input type="text" name="cname" id="cname" class="form-control" placeholder="Computer Name"
                            required>
                        <label for="cname">Computer Name</label>
                        <table class="table table-bordered text-center table-responsive">
                            <thead class="table-dark">
                                <tr>
                                    <td>ASSETS ID</td>
                                    <td>SPECIFIED</td>
                                    <td>BRAND</td>
                                    <td>MODEL</td>
                                    <td>S/N</td>
                                    <td>REMOVE</td>
                                </tr>
                            </thead>
                            <tbody id="showassets">
                                <script src="../js/build_computer.js"></script>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-dark" id="submitBtn" name="build-pc">Save</button>
                    <button type="reset" class="btn btn-danger" onclick="window.location = ''">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    <div class="col">
        <div class="">
            <table class="table table-bordered text-center table-responsive">
                <div id="searchAssets" class="form-outline">
                    <input type="text" class="form-control parts-search" name="assets_id" id="getAssets"
                        placeholder="Search by Serial Number">
                </div>
                <thead class="table-dark">
                    <tr>
                        <td>ASSETS ID</td>
                        <td>SPECIFIED</td>
                        <td>BRAND</td>
                        <td>MODEL</td>
                        <td>S/N</td>
                        <td>ADD</td>
                    </tr>
                </thead>
                <tbody id="showdata">
                    <tr>
                        <td>ASSETS ID</td>
                        <td>SPECIFIED</td>
                        <td>BRAND</td>
                        <td>MODEL</td>
                        <td>S/N</td>
                        <td>
                            <button type="button" class="btn btn-warning mb-3 add-part">
                                <i class="fa-solid fa-circle-plus"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6">
                            <div class="text-white">
                                <nav>
                                    <ul class="bg-dark rounded p-2 d-flex justify-content-center align-items-center gap-3 border border-2 border-white"
                                        id="pagination">
                                        <!-- Pagination links will be inserted dynamically by JavaScript -->
                                    </ul>
                                </nav>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>

        </div>
    </div>
</div>