window.rollDice = (result) => {
    let dice = document.getElementById('dice');
    dice.dataset.side = result;
    buttonTrue.value = result;
    dice.classList.toggle("reRoll");
    const modal = document.getElementById("myModal");
    const ingame = document.querySelector('.ingame');
    setTimeout(function() {
        modal.classList.remove('d-none');
        ingame.style.opacity = '0.5'
    }, 2000)
}
