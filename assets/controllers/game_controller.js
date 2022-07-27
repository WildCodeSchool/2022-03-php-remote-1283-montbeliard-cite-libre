import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["modal", "modal2", "bodyGame", "chrono", "chronoProgress", "response"];
    openModal() {
        this.bodyGameTarget.classList.add("pointer-event-none");
        this.modalTarget.classList.remove("d-none");
    }
    closeModal(event) {
        this.bodyGameTarget.classList.remove("pointer-event-none");
        this.modalTarget.classList.add("d-none");
        this.modalTarget.classList.remove("card-parchment--modal-auto-show");
    }
    closeModal2(event) {
        this.bodyGameTarget.classList.remove("pointer-event-none");
        this.modal2Target.classList.add("d-none");
        this.modal2Target.classList.remove("card-parchment--modal-auto-show");
    }
    displayResponse(event) {
        this.responseTarget.classList.remove("d-none")
    }
    connect(event) {
        const updateChrono = (seconds) => {
            const duration = getCookie("duration") * 60;
            if (seconds > 0) {
                this.chronoTarget.innerText = new Date(seconds * 1000)
                    .toISOString()
                    .substr(11, 8);
            } else {
                this.chronoTarget.innerText = "END OF GAME";
                this.chronoTarget.classList.add("small");
            }
            this.chronoProgressTarget.style.width = `${
                ((duration - seconds) * 100) / duration
            }%`;
        };
        const getCookie = (cname) => {
            let name = cname + "=";
            let ca = document.cookie.split(";");
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) === " ") {
                    c = c.substring(1);
                }
                if (c.indexOf(name) === 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        };
        let timer;
        const chronometer = (pageLoaded = true) => {
            let endedAt = new Date(getCookie("endedAt") * 1000);

            let currentDate = new Date();
            // calculate time before the end of laps in milliseconds
            let endedIn = endedAt.getTime() - currentDate.getTime();

            if (endedIn > 0) {
                timer = setTimeout(function () {
                    chronometer(false);
                }, 1000);
            } else {
                clearTimeout(timer);
                endedIn = 0;
                if (!pageLoaded) {
                    // window.location.href = "/testing/end";
                }
            }
            updateChrono(Math.ceil(endedIn / 1000));
        };

        chronometer();

    }
}
