/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// import js
<<<<<<< HEAD

=======
import './confettiparty';
import './cardparchment';
import './cardquestion';
import './diceanimation';
>>>>>>> 3ac7840c7935651655b0d5fb4ee85f3c9bcd071f

// start the Stimulus application
import './bootstrap';

require('bootstrap');

<<<<<<< HEAD
// import './cardparchment.js';
// import './cardquestion.js';

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

let btnDisplayResponse = document.getElementById("btn-display-response");
let response = document.getElementById("response");

btnDisplayResponse.addEventListener("click", () => {
    response.classList.toggle('d-none');
});
=======
>>>>>>> 3ac7840c7935651655b0d5fb4ee85f3c9bcd071f
