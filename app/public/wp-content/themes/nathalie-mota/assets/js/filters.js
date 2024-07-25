jQuery(document).ready(function($) {
    let totalPhotos = 0;
    let loadedPhotos = 0;

    function loadPhotos(resetFilters = false) {
        let category = $('#category-filter').val() || '';
        let format = $('#format-filter').val() || '';
        let order = $('#order-filter').val() || 'DESC';
        let data = {
            action: 'filter_photos',
            category: category,
            format: format,
            order: order,
        };

        console.log(data);

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

        console.log(data);

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

    // Personnaliser les sélecteurs
    let x, i, j, selElmnt, a, b, c;
    x = document.getElementsByClassName("custom-select");
    for (i = 0; i < x.length; i++) {
        selElmnt = x[i].getElementsByTagName("select")[0];
        a = document.createElement("DIV");
        a.setAttribute("class", "select-selected");
        a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
        x[i].appendChild(a);
        b = document.createElement("DIV");
        b.setAttribute("class", "select-items select-hide");
        for (j = 1; j < selElmnt.length; j++) {
            c = document.createElement("DIV");
            c.innerHTML = selElmnt.options[j].innerHTML;
            c.addEventListener("click", function(e) {
                let y, i, k, s, h;
                s = this.parentNode.parentNode.getElementsByTagName("select")[0];
                h = this.parentNode.previousSibling;
                for (i = 0; i < s.length; i++) {
                    if (s.options[i].innerHTML == this.innerHTML) {
                        s.selectedIndex = i;
                        h.innerHTML = this.innerHTML;
                        y = this.parentNode.getElementsByClassName("same-as-selected");
                        for (k = 0; k < y.length; k++) {
                            y[k].removeAttribute("class");
                        }
                        this.setAttribute("class", "same-as-selected");
                        break;
                    }
                }
                h.click();
                loadPhotos();
            });
            b.appendChild(c);
        }
        x[i].appendChild(b);
        a.addEventListener("click", function(e) {
            e.stopPropagation();
            closeAllSelect(this);
            this.nextSibling.classList.toggle("select-hide");
            this.classList.toggle("select-arrow-active");
        });
    }

    function closeAllSelect(elmnt) {
        let x, y, i, arrNo = [];
        x = document.getElementsByClassName("select-items");
        y = document.getElementsByClassName("select-selected");
        for (i = 0; i < y.length; i++) {
            if (elmnt == y[i]) {
                arrNo.push(i);
            } else {
                y[i].classList.remove("select-arrow-active");
            }
        }
        for (i = 0; i < x.length; i++) {
            if (arrNo.indexOf(i)) {
                x[i].classList.add("select-hide");
            }
        }
    }

    document.addEventListener("click", closeAllSelect);
});
