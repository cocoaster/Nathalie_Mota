function addLightboxEvents() {
    var lightbox = document.getElementById('custom-lightbox');
    var lightboxImg = document.getElementById('lightbox-img');
    var caption = document.querySelector('.caption');
    var categoryElement = document.querySelector('.category');
    var currentImageIndex = 0;
    var images = document.querySelectorAll('.photo-item');

    if (!lightbox || !lightboxImg || !caption || !categoryElement || images.length === 0) {
        console.warn('Lightbox elements or images not found');
        return;
    }

    images.forEach(function(image, index) {
        var fullscreenElement = image.querySelector('.fullscreen');
        if (fullscreenElement) {
            fullscreenElement.addEventListener('click', function() {
                currentImageIndex = index;
                var imgSrc = image.getAttribute('data-full-image');
                var imgReference = image.getAttribute('data-reference');
                var imgCategory = image.getAttribute('data-category');
                lightbox.style.display = 'block';
                lightboxImg.src = imgSrc;
                caption.textContent = ' ' + imgReference;
                categoryElement.textContent = '' + imgCategory;
            });
        } else {
            console.warn('Fullscreen element not found for image index ' + index);
        }
    });

    var closeElement = document.querySelector('.close');
    var prevElement = document.querySelector('.custom-prev');
    var nextElement = document.querySelector('.custom-next');

    if (closeElement) {
        closeElement.addEventListener('click', function() {
            lightbox.style.display = 'none';
        });
    } else {
        console.warn('Close element not found');
    }

    if (prevElement) {
        prevElement.addEventListener('click', function() {
            currentImageIndex = (currentImageIndex > 0) ? currentImageIndex - 1 : images.length - 1;
            updateLightbox();
        });
    } else {
        console.warn('Previous element not found');
    }

    if (nextElement) {
        nextElement.addEventListener('click', function() {
            currentImageIndex = (currentImageIndex < images.length - 1) ? currentImageIndex + 1 : 0;
            updateLightbox();
        });
    } else {
        console.warn('Next element not found');
    }

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
        if (event.target === lightbox || event.target.classList.contains('close')) {
            lightbox.style.display = 'none';
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    addLightboxEvents();
});