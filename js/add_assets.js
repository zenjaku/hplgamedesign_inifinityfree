
document.addEventListener('DOMContentLoaded', () => {
    function addInput() {
        const container = document.querySelector(".row");

        if (!container) return;

        // Function to check if all fields in a row are filled
        function checkInputs(row) {
            let inputs = row.querySelectorAll("input");
            let addButton = row.querySelector(".btn-warning");
            let allFilled = Array.from(inputs).every(input => input.value.trim() !== "");

            addButton.disabled = !allFilled; // Enable button if all fields are filled
        }

        // Function to add new input row
        function addInputRow() {
            let originalGroups = document.querySelectorAll(".col.d-flex");

            // Disable buttons on all previous rows
            originalGroups.forEach(group => {
                group.querySelector(".btn-warning").disabled = true;
                group.querySelector(".btn-danger").disabled = true;
            });

            // Clone the last group and clear inputs
            let newGroup = originalGroups[originalGroups.length - 1].cloneNode(true);
            newGroup.querySelectorAll("input").forEach(input => input.value = "");

            // Enable the new row's buttons
            let addButton = newGroup.querySelector(".btn-warning");
            let deleteButton = newGroup.querySelector(".btn-danger");

            addButton.disabled = true; // Initially disabled until fields are filled
            deleteButton.disabled = false;
            deleteButton.style.display = "inline-block";

            // Attach event listeners to the new row's inputs
            newGroup.querySelectorAll("input").forEach(input => {
                input.addEventListener("input", function () {
                    checkInputs(newGroup);
                });
            });

            // Attach event listeners for buttons
            addButton.addEventListener("click", addInputRow);
            deleteButton.addEventListener("click", function () {
                newGroup.remove();
                enableLastRowButtons(); // Re-enable buttons on the last remaining row
            });

            // Append new input group
            container.appendChild(newGroup);
        }

        // Function to enable buttons on the last row after deletion
        function enableLastRowButtons() {
            let lastRow = document.querySelectorAll(".col.d-flex");
            if (lastRow.length > 0) {
                let lastAddButton = lastRow[lastRow.length - 1].querySelector(".btn-warning");
                let lastDeleteButton = lastRow[lastRow.length - 1].querySelector(".btn-danger");
                lastAddButton.disabled = false;
                lastDeleteButton.disabled = false;
            }
        }

        // Attach event listener to the first row's inputs
        let firstRow = document.querySelector(".col.d-flex");
        firstRow.querySelectorAll("input").forEach(input => {
            input.addEventListener("input", function () {
                checkInputs(firstRow);
            });
        });

        // Attach event listener to the first "Add" button
        document.querySelector(".btn-warning").addEventListener("click", addInputRow);
    }

    addInput();
});