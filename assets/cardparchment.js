let btnDisplayResponse = document.getElementById("btn-display-response");
let response = document.getElementById("response");

const modal = document.getElementById("myModal");

if (btnDisplayResponse) {
    btnDisplayResponse.addEventListener("click", () => {
        response.classList.toggle('d-none');
    });
}
