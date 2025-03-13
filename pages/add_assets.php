<div class="container my-5">
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3>Add Assets</h3>
            <a href="/inventory">
                <button type="button" class="btn btn-danger">
                    Close
                </button>
            </a>
        </div>
        <form action="/add-assets" method="post" id="addAssets">
            <div class="card-body">
                <div class="row d-flex flex-column gap-3">
                    <div class="col d-flex gap-3 justify-content-start align-items-center">
                        <div class="input-group">
                            <label class="input-group-text" for="assets">Assets</label>
                            <select class="form-select" name="assets[]" id="assets">
                                <option selected>-- Choose Assets --</option>
                                <option value="PROCESSOR">PROCESSOR</option>
                                <option value="MOTHERBOARD">MOTHERBOARD</option>
                                <option value="GPU">GPU</option>
                                <option value="HDD">HDD</option>
                                <option value="SSD">SSD</option>
                                <option value="RAM">RAM</option>
                                <!--<option value="CABLE">CABLE</option>-->
                                <option value="HEADSET">HEADSET</option>
                                <option value="WEBCAM">WEBCAM</option>
                                <option value="MONITOR">MONITOR</option>
                                <option value="KEYBOARD">KEYBOARD</option>
                                <option value="MOUSE">MOUSE</option>
                                <option value="PEN DISPLAY">PEN DISPLAY</option>
                                <option value="PEN TABLET">PEN TABLET</option>
                            </select>
                        </div>
                        <div class="form-floating">
                            <input type="text" name="brand[]" class="form-control group" required>
                            <label>Brand</label>
                        </div>
                        <div class="form-floating">
                            <input type="text" name="model[]" class="form-control group" required>
                            <label>Model</label>
                        </div>
                        <div class="form-floating">
                            <input type="text" name="sn[]" class="form-control group" required>
                            <label>Serial Number</label>
                        </div>
                        <button type="button" class="btn btn-warning">
                            <i class="fa-solid fa-circle-plus"></i>
                        </button>
                        <button type="button" class="btn btn-danger cancel">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-end align-items-center gap-3">
                    <button type="submit" name="addAssets" class="btn btn-dark">Submit</button>
                    <button type="reset" class="btn btn-danger" onclick="window.location =''">Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="../js/add_assets.js"></script>