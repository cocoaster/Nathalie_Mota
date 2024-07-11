jQuery(document).ready(function($) {
    var contactModal = document.getElementById("contact-modal");
    var contactButtons = document.querySelectorAll(".contact-menu-item a, .open-contact-modal");
    var closeBtn = document.getElementsByClassName("close")[0];

    if (contactModal && closeBtn) {
        contactButtons.forEach(function(button) {
            button.addEventListener("click", function(event) {
                event.preventDefault(); // Empêche le comportement par défaut du lien
                var photoReference = button.getAttribute("data-photo-reference") || '';
                document.getElementById("photo-reference").value = photoReference;
                console.log("Bouton de contact cliqué");
                contactModal.style.display = "block";
            });
        });

        closeBtn.onclick = function() {
            console.log("Bouton de fermeture cliqué");
            contactModal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == contactModal) {
                console.log("Clic en dehors de la modal détecté");
                contactModal.style.display = "none";
            }
        }

        $("#contact-form").on("submit", function(e) {
            e.preventDefault();
            var data = {
                action: 'submit_contact_form',
                nonce: nathalie_mota_ajax.nonce, // Utiliser le nonce localisé
                name: $("#name").val(),
                email: $("#email").val(),
                photo_reference: $("#photo-reference").val(),
                message: $("#message").val(),
            };

            console.log('Données envoyées :', data); //  Vérifie les données

            $.post(nathalie_mota_ajax.url, data, function(response) {
                console.log('Réponse reçue :', response); // Vérifie la réponse
                if (response.success) {
                    alert(response.data.message || "Votre message a bien été envoyé. Vous allez recevoir un e-mail de confirmation.");
                    contactModal.style.display = "none";
                } else {
                    alert(response.data.message || "Une erreur est survenue. Veuillez réessayer.");
                }
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.error('Erreur AJAX :', textStatus, errorThrown); // Log l'erreur AJAX
                alert("Une erreur est survenue. Veuillez réessayer.");
            });
        });
    } else {
        console.warn('Contact modal elements not found');
    }
});
