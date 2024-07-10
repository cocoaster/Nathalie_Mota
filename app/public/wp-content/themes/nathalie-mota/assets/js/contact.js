jQuery(document).ready(function($) {
    var contactModal = document.getElementById("contact-modal");
    var contactButtons = document.querySelectorAll(".contact-menu-item a, .open-contact-modal");
    var closeBtn = document.getElementsByClassName("close")[0];

    contactButtons.forEach(function(button) {
        button.addEventListener("click", function(event) {
            event.preventDefault(); // Empêche le comportement par défaut du lien
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
            name: $("#name").val(),
            email: $("#email").val(),
            photo_reference: $("#photo-reference").val(),
            message: $("#message").val(),
        };

        $.post(nathalie_mota_ajax.url, data, function(response) {
            alert("Votre message a bien été envoyé. Vous allez recevoir un e-mail de confirmation.");
            contactModal.style.display = "none";
        }).fail(function() {
            alert("Une erreur est survenue. Veuillez réessayer.");
        });
    });
});
