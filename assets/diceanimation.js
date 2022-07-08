window.rollDice = (result) => {
    let closeModal = document.querySelector(".close-modal");

    let dice = document.getElementById("dice");
    dice.dataset.side = result;
    dice.classList.toggle("reRoll");
    const modal = document.getElementById("myModal");
    const bodyGame = document.querySelector(".body-game");
    const showModal = () => {
        bodyGame.classList.add('pointer-event-none')
        modal.classList.remove("d-none");
    }
    setTimeout(function () {
        showModal();
    }, 2000);
    closeModal.addEventListener("click", () => {
        modal.classList.add("d-none");
        bodyGame.classList.remove('pointer-event-none')
    });
    const showModalBtn = document.querySelector(".show-modal");
    showModalBtn.addEventListener("click", () => {
        showModal();
    });
};
