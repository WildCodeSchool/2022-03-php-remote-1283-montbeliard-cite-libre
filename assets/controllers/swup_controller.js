import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = [ "audio", "audioButton"];
    connect()
    {
        this._onConnect()
        document.addEventListener('swup:contentReplaced', this._onConnect);
    }

    disconnect()
    {
        // You should always remove listeners when the controller is disconnected to avoid side-effects
        this.element.removeEventListener('swup:connect', this._onConnect);
    }

    _onConnect()
    {
        // Swup has just been intialized and you can access details from the event

    }
    togglePlay(event) {
        if (this.audioTarget.paused) {
            this.audioButtonTarget.classList.add('fa-volume')
            this.audioButtonTarget.classList.remove('fa-volume-mute')
            this.audioTarget.play();
        } else {
            this.audioButtonTarget.classList.remove('fa-volume')
            this.audioButtonTarget.classList.add('fa-volume-mute')
            this.audioTarget.pause();
        }
    }
}
