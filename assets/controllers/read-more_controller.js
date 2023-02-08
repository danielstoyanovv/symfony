import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['content'];

    showContent(e) {
        this.contentTarget.innerHTML = e.currentTarget.dataset.description;
        e.currentTarget.classList.add('d-none');
        this.element.getElementsByClassName('read-less-link')[0].classList.remove('d-none');
        e.preventDefault();
    }

    hideContent(e) {
        this.contentTarget.innerHTML = e.currentTarget.dataset.shortDescription + "...";
        e.currentTarget.classList.add('d-none');
        this.element.getElementsByClassName('read-more-link')[0].classList.remove('d-none');
        e.preventDefault();
    }
}
