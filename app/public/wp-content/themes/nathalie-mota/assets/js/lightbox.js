function addLightboxEvents() {
    let lightbox = document.getElementById('custom-lightbox');
    let lightboxImg = document.getElementById('lightbox-img');
    let caption = document.querySelector('.caption');
    let categoryElement = document.querySelector('.category');
    let currentImageIndex = 0;
    let images = document.querySelectorAll('.photo-item');

    if (!lightbox || !lightboxImg || !caption || !categoryElement || images.length === 0) {
        console.warn('Lightbox elements or images not found');
        return;
    }

    images.forEach(function(image, index) {
        let fullscreenElement = image.querySelector('.fullscreen');
        if (fullscreenElement) {
            fullscreenElement.addEventListener('click', function() {
                currentImageIndex = index;
                let imgSrc = image.getAttribute('data-full-image');
                let imgReference = image.getAttribute('data-reference');
                let imgCategory = image.getAttribute('data-category');
                lightbox.style.display = 'block';
                lightboxImg.src = imgSrc;
                caption.textContent = ' ' + imgReference;
                categoryElement.textContent = '' + imgCategory;
                resizeLightboxContainer();
            });
        } else {
            console.warn('Fullscreen element not found for image index ' + index);
        }
    });

    let closeElement = document.querySelector('.close');
    let prevElement = document.querySelector('.custom-prev');
    let nextElement = document.querySelector('.custom-next');

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
        let imgSrc = images[currentImageIndex].getAttribute('data-full-image');
        let imgReference = images[currentImageIndex].getAttribute('data-reference');
        let imgCategory = images[currentImageIndex].getAttribute('data-category');
        lightboxImg.src = imgSrc;
        caption.textContent = ' ' + imgReference;
        categoryElement.textContent = '' + imgCategory;
        resizeLightboxContainer();
    }

    // Fermer la lightbox en cliquant à l'extérieur de l'image
    lightbox.addEventListener('click', function(event) {
        if (event.target === lightbox || event.target.classList.contains('close')) {
            lightbox.style.display = 'none';
        }
    });
}

// function resizeLightboxContainer() {
//     var lightboxImg = document.getElementById('lightbox-img');
//     var lightboxContainer = document.querySelector('.lightbox-container');
//     var infoContainer = document.getElementById('lightbox-photo-datas');
//     lightboxImg.addEventListener('load', function() {
//         lightboxContainer.style.width = lightboxImg.clientWidth + 'px';
//         infoContainer.style.width = lightboxImg.clientWidth + 'px';
//     });
// }

document.addEventListener('DOMContentLoaded', function() {
    addLightboxEvents();
    document.getElementById('lightbox-img').addEventListener('load', resizeLightboxContainer);
});

jQuery(document).ready(function($) {
    let totalPhotos = 0;
    let loadedPhotos = 0;

    function loadPhotos() {
        let category = $('#category-filter').val() || '';
        let format = $('#format-filter').val() || '';
        let order = $('#order-filter').val() || 'DESC';
        let data = {
            action: 'filter_photos',
            category: category,
            format: format,
            order: order,
        };

        $.post(nathalie_mota_ajax.url, data, function(response) {
            let responseData = JSON.parse(response);
            $('#photo-list').html(responseData.html);
            totalPhotos = responseData.total;
            loadedPhotos = $('#photo-list .photo-item').length;
            addLightboxEvents();
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error('Error: ' + textStatus, errorThrown);
        });
    }

    $('#category-filter, #format-filter, #order-filter').change(function() {
        loadPhotos();
    });

    $('#load-more').click(function() {
        let offset = $('#photo-list .photo-item').length;

        if (loadedPhotos >= totalPhotos) {
            return;
        }

        let category = $('#category-filter').val() || '';
        let format = $('#format-filter').val() || '';
        let order = $('#order-filter').val() || 'DESC';
        let data = {
            action: 'load_more_photos',
            offset: offset,
            category: category,
            format: format,
            order: order,
        };

        $.post(nathalie_mota_ajax.url, data, function(response) {
            let responseData = JSON.parse(response);
            $('#photo-list').append(responseData.html);
            loadedPhotos += responseData.loaded;
            addLightboxEvents();
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error('Error: ' + textStatus, errorThrown);
        });
    });

    loadPhotos();
});
