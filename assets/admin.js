/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/admin/admin.scss';

// start the Stimulus application
import './bootstrap';

require('bootstrap');


window.trigCKEditor = () => {
    const editors = document.querySelectorAll('[data-editor]');
    editors.forEach((editor) => {
        const instance = editor.id;
        const scripts = document.getElementById(instance).parentElement.getElementsByTagName('script')
        eval(scripts[0].innerHTML);
        eval(scripts[2].innerHTML);
    });
}
