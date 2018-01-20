var Turbolinks = require('turbolinks');

// Turbolinks.start();

document.addEventListener('turbolinks:click', function (event) {
    if (event.target.getAttribute('href').charAt(0) === '#') {
        return event.preventDefault();
    }
});
