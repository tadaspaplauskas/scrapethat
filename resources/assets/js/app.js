// Turbolinks = require('turbolinks');
// Turbolinks.start();

// document.addEventListener('turbolinks:click', function (event) {
//     if (event.target.getAttribute('href').charAt(0) === '#') {
//         return event.preventDefault();
//     }
// });

// function whenReady(fn) {
//     document.addEventListener('turbolinks:load', function (event) {
//         fn();
//     });
// }


window.drawChart = function (id, filters) {
    var elem = document.getElementById(id);
    var datasets = filters.map(function (item) {
        return {
            label: item.name,
            data: item.values
        };
    });

    var chart = new Chart(elem, {
        type: 'line',
        data: {
            labels: new Array(datasets[0].data.length),
            datasets: datasets
        },
        options: {
        }
    });
};