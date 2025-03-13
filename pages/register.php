<div class="container d-flex flex-column align-items-center" id="register">
    <div class="shadow-lg bg-white p-4 rounded d-flex flex-column align-items-center">
        <h1 class="mb-5">REGISTER</h1>
        <form action="/register-employee" method="post" autocomplete="off" id="registerForm">
            <div class="row d-flex">
                <div class="col-2 form-floating">
                    <input type="text" id="employee_id" name="employee_id" class="form-control"
                        placeholder="Employee ID" required>
                    <label for="employee_id" class="employee-id"><small>Employee ID</small></label>
                </div>
                <div class="col-5 form-floating">
                    <input type="text" id="fname" name="fname" class="form-control" placeholder="First Name" required>
                    <label for="fname">First Name</label>
                </div>
                <div class="col-5 form-floating">
                    <input type="text" id="lname" name="lname" class="form-control" placeholder="Last Name" required>
                    <label for="lname">Last Name</label>
                </div>
            </div>
            <div class="row">
                <div class="col-6 form-floating">
                    <input type="text" id="dept" name="dept" class="form-control" placeholder="Department" required>
                    <label for="dept">Department</label>
                </div>
                <div class="col-6 form-floating">
                    <input type="email" id="email" name="email" class="form-control" placeholder="Email Address" required>
                    <label for="email">Email Address</label>
                </div>
            </div>
            <div class="row d-flex my-3">
                <div class="col-md-12 form-floating">
                    <select type="text" id="status" name="status" class="form-control" required>
                        <option class="bg-secondary-subtle" readonly>Work Status</option>
                        <option value="NEW HIRE">
                            NEW HIRE </option>
                        <option value="WFH">
                            WFH </option>
                        <option value="TEMP WFH">
                            TEMP WFH </option>
                    </select>
                </div>
            </div>
            <div class="form-buttons">
                <button type="submit" class="btn btn-dark" id="registerBtn" name="register">Submit</button>
                <button type="reset" class="btn btn-danger" onclick="parent.location.href=''">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script src="../js/register.js"></script>