let dice = document.getElementById('dice');
var outputDiv = document.getElementById('diceResult');

function rollDice() {
    let result = Math.floor(Math.random() * (6 - 1 + 1)) + 1;
    dice.dataset.side = result;
    dice.classList.toggle("reRoll");

    outputDiv.classList.remove("reveal");
    outputDiv.classList.add("hide");
    outputDiv.innerHTML = "You've got " + result;
    setTimeout(function () {
        outputDiv.classList.add("reveal");
    }, 1500);
}

dice.addEventListener("click", rollDice);
