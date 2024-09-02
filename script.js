const container = document.getElementById('container');
const registerBtn = document.getElementById('register');
const loginBtn = document.getElementById('login');

registerBtn.addEventListener('click', () => {
    container.classList.add("active");
});

loginBtn.addEventListener('click', () => {
    container.classList.remove("active");
});

    // Récupérer le message de l'URL
    const urlParams = new URLSearchParams(window.location.search);
    const message = urlParams.get('message');

    // Sélectionner l'élément où afficher le message
    const messageElement = document.getElementById('message');

    // Afficher le message
    if (message) {
        messageElement.textContent = message;

        // Ajouter une classe CSS en fonction du type de message
        if (message.startsWith('Erreur')) {
            messageElement.classList.add('error');
        } else {
            messageElement.classList.add('success');
        }
    }


    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };