$(document).ready(function() {
    var el = document.getElementById('sortable');
    if (el) {
        var sortUrl = el.getAttribute('data-action');
        var sortable = Sortable.create(el, {
            animation: 150,
            onEnd: () => {
                $.ajax({
                    type: 'POST',
                    url: sortUrl,
                    data: { products: sortable.toArray() },
                    success: function(result) {
                        location.reload();
                    }
                });
            }
        });
    }
});
