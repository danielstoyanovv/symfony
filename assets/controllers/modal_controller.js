import { Controller } from '@hotwired/stimulus';
import {Modal} from "bootstrap";
import * as Turbo from '@hotwired/turbo';

export default class extends Controller {
    static targets = ['modal', 'content'];

    connect() {
        let classObject = this;

        this.element.addEventListener('turbo:submit-end', (event) => {

            console.log(event.detail.success);
            if (event.detail.success === true) {
                return Turbo.visit(document.URL);
            } else if (event.detail.success === false) {
                event.detail.fetchResponse.response.text().then((html) => {
                    return classObject.contentTarget.innerHTML = html;
                });
            }
        });

        if (this.element.dataset.content) {
            this.contentTarget.innerHTML = this.element.dataset.content;
        }
    }

    openModel(e) {
        const modal = new Modal(this.modalTarget);
        modal.show();
        e.preventDefault();
    }
}
