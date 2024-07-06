jQuery(document).ready(function($) {
    console.log('jQuery is loaded'); // Vérifiez si jQuery est bien chargé

    // Créez la prévisualisation de la vignette
    const thumbnailPreview = $('<div id="thumbnail-preview" style="position: absolute; bottom: 40px; right: 40px; display: none;"><img id="thumbnail-image" src="" alt="Thumbnail" style="width: 100px; height: auto; border: 1px solid #ccc; box-shadow: 0px 0px 6px rgba(0,0,0,0.5);" /></div>');
    $('body').append(thumbnailPreview);

    // Vérifiez si les éléments existent
    const prevLink = $('.navigation-links .prev');
    const nextLink = $('.navigation-links .next');

    console.log('Previous Link:', prevLink);
    console.log('Next Link:', nextLink);

    // Attachez les événements hover
    prevLink.hover(
        function() {
            console.log('Hover over prev link:', this);
            const thumbnailSrc = $(this).data('thumbnail');
            console.log('Thumbnail URL (prev):', thumbnailSrc);
            $('#thumbnail-image').attr('src', thumbnailSrc);
            $('#thumbnail-preview').css('display', 'block');
        },
        function() {
            $('#thumbnail-preview').css('display', 'none');
        }
    );

    nextLink.hover(
        function() {
            console.log('Hover over next link:', this);
            const thumbnailSrc = $(this).data('thumbnail');
            console.log('Thumbnail URL (next):', thumbnailSrc);
            $('#thumbnail-image').attr('src', thumbnailSrc);
            $('#thumbnail-preview').css('display', 'block');
        },
        function() {
            $('#thumbnail-preview').css('display', 'none');
        }
    );
});


