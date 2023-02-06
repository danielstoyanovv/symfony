import { Controller } from '@hotwired/stimulus';
import $ from 'jquery';

export default class extends Controller {
    static targets = ['songs'];

    vote(e) {
        var classObject = this;

        let song = e.currentTarget.dataset.song;
        let rating = $('#rating-song-' + song).val();
        let voteAction = e.currentTarget.dataset.url;
        let listAction =  e.currentTarget.dataset.list;
        let queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);

        if (urlParams.has('page')) {
            listAction += "?page=" + urlParams.get('page');
        }

        if (rating && song && voteAction && listAction) {
            $.ajax({
                type: 'POST',
                async: true,
                url: voteAction,
                data: { song: song, rating: rating },
                success: function(result) {
                    $.ajax({
                        type: 'GET',
                        url: listAction,
                        success: function(result) {
                            window.dispatchEvent(new Event("success"));
                            classObject.songsTarget.innerHTML = result;
                        }
                    });
                }
            });
        }
    }
}
