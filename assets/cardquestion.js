const modal = document.getElementById("myModal");
const btn = document.querySelector('.btn-dice');
const ingame = document.querySelector('.ingame');

btn.addEventListener('click', () => {
    setTimeout(showModal, 3000)
})

function showModal() {
    modal.classList.remove('d-none');
    ingame.style.opacity = '0.5'
}
