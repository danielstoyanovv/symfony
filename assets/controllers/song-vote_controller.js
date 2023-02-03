import { Controller } from '@hotwired/stimulus';
import $ from 'jquery';

export default class extends Controller {
    vote(e) {
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
                            $('#song-vote-list').html(result);
                        }
                    });
                }
            });
        }
    }
}
