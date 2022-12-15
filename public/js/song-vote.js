$(document).ready(function(){
    $(".song-vote-button").click(function (e) {
        var rating = $(this).parent('.song-vote-form').children('.rating').val();
        var song = $(this).parent('.song-vote-form').children('.song').val();
        var action = $(this).parent('.song-vote-form').children('.song-vote-action').val();

        if (rating && song && action) {
            $.ajax({
                type: 'POST',
                async: true,
                url: action,
                data: { song: song, rating: rating },
                success: function(result) {
                    location.reload();
                }
            });
        }
    });
});