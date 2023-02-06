import { Controller } from '@hotwired/stimulus';
import { Sortable } from 'sortablejs';
import $ from 'jquery';

export default class extends Controller {
    connect() {
        var el = document.getElementById('sortable');

        if (el) {
            let sortUrl =  this.element.dataset.url;
            var sortable = Sortable.create(el, {
                animation: 150,
                onEnd: () => {
                    $.ajax({
                        type: 'POST',
                        url: sortUrl,
                        data: { products: sortable.toArray() },
                        success: function(result) {
                            window.dispatchEvent(new Event("success"));
                        }
                    });
                }
            });
        }
    }
}
