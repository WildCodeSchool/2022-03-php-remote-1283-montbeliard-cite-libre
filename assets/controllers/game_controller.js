import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["modal", "bodyGame"];
    openModal(event) {
        console.log("open");
        this.bodyGameTarget.classList.add("pointer-event-none");
        this.modalTarget.classList.remove("d-none");
    }
    closeModal(event) {
        this.bodyGameTarget.classList.remove("pointer-event-none");
        this.modalTarget.classList.add("d-none");
    }
}
