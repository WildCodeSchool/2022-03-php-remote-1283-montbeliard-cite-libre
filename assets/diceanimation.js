window.rollDice = (result) => {
    let closeModal = document.querySelector(".close-modal");

    let dice = document.getElementById("dice");
    dice.dataset.side = result;
    dice.classList.toggle("reRoll");
    const modal = document.getElementById("myModal");
    const ingame = document.querySelector(".ingame");
    setTimeout(function () {
        modal.classList.remove("d-none");
        ingame.style.opacity = "0.5";
    }, 2000);
    closeModal.addEventListener("click", () => {
        modal.classList.add("d-none");
        ingame.style.opacity = "1";
    });
    const showModal = document.querySelector(".show-modal");
    showModal.addEventListener("click", () => {
        modal.classList.remove("d-none");
        ingame.style.opacity = "0.5";
    });
};
