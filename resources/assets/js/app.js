Turbolinks = require('turbolinks');
Turbolinks.start();

document.addEventListener('turbolinks:click', function (event) {
    if (event.target.getAttribute('href').charAt(0) === '#') {
        return event.preventDefault();
    }
});

function whenReady(fn) {
    document.addEventListener('turbolinks:load', function (event) {
        fn();
    });
}