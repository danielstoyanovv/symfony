import { Controller } from '@hotwired/stimulus';
import {Modal} from "bootstrap";

export default class extends Controller {
    static targets = ['modal', 'content'];

    connect() {
        this.contentTarget.innerHTML = this.element.dataset.content;
    }

    openModel() {
        const modal = new Modal(this.modalTarget);
        modal.show();
    }
}
