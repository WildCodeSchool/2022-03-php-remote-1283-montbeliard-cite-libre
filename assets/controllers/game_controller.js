import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["modal", "bodyGame"];
    openModal() {
        this.bodyGameTarget.classList.add("pointer-event-none");
        this.modalTarget.classList.remove("d-none");
    }
    closeModal(event) {
        this.bodyGameTarget.classList.remove("pointer-event-none");
        this.modalTarget.classList.add("d-none");
    }
    openRoll() {
        setTimeout(() => {
            this.openModal();
        }, 3000);
    }
}
