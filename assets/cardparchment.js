let btnDisplayResponse = document.getElementById("btn-display-response");
let response = document.getElementById("response");

if (btnDisplayResponse) {
    btnDisplayResponse.addEventListener("click", () => {
        response.classList.toggle('d-none');
    });
}
