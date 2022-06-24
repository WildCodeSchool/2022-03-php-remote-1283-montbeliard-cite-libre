const modal = document.getElementById("myModal");
const btn = document.querySelector('.btn-dice');

btn.addEventListener('click', () => {
    setTimeout(showModal, 3000)
})

function showModal() {
    modal.classList.remove('d-none');
}
