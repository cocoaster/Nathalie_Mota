function addLightboxEvents() {
    var lightbox = document.getElementById('custom-lightbox');
    var lightboxImg = document.getElementById('lightbox-img');
    var caption = document.querySelector('.caption');
    var categoryElement = document.querySelector('.category');
    var currentImageIndex = 0;
    var images = document.querySelectorAll('.photo-item');

    images.forEach(function(image, index) {
        image.querySelector('.fullscreen').addEventListener('click', function() {
            currentImageIndex = index;
            var imgSrc = image.getAttribute('data-full-image');
            var imgReference = image.getAttribute('data-reference');
            var imgCategory = image.getAttribute('data-category');
            lightbox.style.display = 'block';
            lightboxImg.src = imgSrc;
            caption.textContent = ' ' + imgReference;
            categoryElement.textContent = '' + imgCategory;
        });
    });

    document.querySelector('.close').addEventListener('click', function() {
        lightbox.style.display = 'none';
    });

    document.querySelector('.custom-prev').addEventListener('click', function() {
        currentImageIndex = (currentImageIndex > 0) ? currentImageIndex - 1 : images.length - 1;
        updateLightbox();
    });

    document.querySelector('.custom-next').addEventListener('click', function() {
        currentImageIndex = (currentImageIndex < images.length - 1) ? currentImageIndex + 1 : 0;
        updateLightbox();
    });

    function updateLightbox() {
        if (currentImageIndex < 0 || currentImageIndex >= images.length) {
            return; // S'assurer que l'index est dans les limites du tableau
        }
        var imgSrc = images[currentImageIndex].getAttribute('data-full-image');
        var imgReference = images[currentImageIndex].getAttribute('data-reference');
        var imgCategory = images[currentImageIndex].getAttribute('data-category');
        lightboxImg.src = imgSrc;
        caption.textContent = ' ' + imgReference;
        categoryElement.textContent = '' + imgCategory;
    }

    // Fermer la lightbox en cliquant à l'extérieur de l'image
    lightbox.addEventListener('click', function(event) {
        if (event.target === lightbox) {
            lightbox.style.display = 'none';
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    addLightboxEvents();
});
