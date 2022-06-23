const confetti = require('canvas-confetti');

const myConfetti = confetti.create(false, {
    resize: true,
    useWorker: true
});
const colors = ["#DD7110", "#6a696b"];
const duration = 10 * 1000;
const end = Date.now() + duration;

window.gameWin = () => {
    window.launchConfetties();
}

window.launchConfetties = () => {
    myConfetti({
        particleCount: 2,
        angle: 60,
        spread: 55,
        origin: {
            x: 0
        },
        colors: colors,
    });
    myConfetti({
        particleCount: 2,
        angle: 120,
        spread: 55,
        origin: {
            x: 1
        },
        colors: colors,
    });
    if (Date.now() < end) {
        requestAnimationFrame(window.launchConfetties);
    } else {
        window.location.pathname = "/game"
    }
}
