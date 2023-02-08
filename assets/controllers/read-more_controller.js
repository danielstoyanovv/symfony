import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['content'];

    showContent(e) {
        let description = e.currentTarget.dataset.description;
        this.contentTarget.innerHTML = description;
        e.currentTarget.classList.add('d-none');
        this.element.getElementsByClassName('read-less-link')[0].classList.remove('d-none');

    }

    hideContent(e) {
        let shortDescription = e.currentTarget.dataset.shortDescription;
        this.contentTarget.innerHTML = shortDescription + "...";
        e.currentTarget.classList.add('d-none');
        this.element.getElementsByClassName('read-more-link')[0].classList.remove('d-none');
    }
}
