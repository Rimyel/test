import "./bootstrap";
document.addEventListener("DOMContentLoaded", function () {
    document
        .getElementById("sortButton")
        .addEventListener("click", function () {
            var sortModal = document.getElementById("sortModal");
            if (
                sortModal.style.maxHeight === "0px" ||
                sortModal.style.maxHeight === ""
            ) {
                sortModal.style.maxHeight = sortModal.scrollHeight + "px";
            } else {
                sortModal.style.maxHeight = "0px";
            }
        });
});
