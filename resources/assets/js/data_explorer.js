// global dependencies
Chart = require('chart.js');

window.output = function (callback) {
    return callback(document.querySelector('#sql-output'));
};

window.chart = function (labels, data) {
    if (typeof window.chart.instance === 'undefined') {
        window.chart.instance = new Chart(document.querySelector('#chart'), {
            type: 'line',
            data: {
                labels: [],
                datasets: []
            },
            options: {}
        });
    }

    window.chart.instance.data.labels = labels;
    window.chart.instance.data.datasets = data;
    window.chart.instance.update();

    return window.chart.instance;
};

window.renderError = function (error) {
    output(function (element) {
        element.innerText = error;
        element.className = 'red';
    });
};

window.renderChart = function (results) {
    if (!results.length) {
        return;
    }

    var chartDatasets = Object.keys(results[0]).map(function (key) {
        return {
            label: key,
            data: results.map(function (row) {
                return row[key];
            })
        };
    });

    var labels = new Array(chartDatasets[0].data.length);

    chart(labels, chartDatasets);
};

window.renderTable = function (results) {
    var html;

    if (results.length) {
        html = '<h5 class="mt5">Results <em>(' + results.length + ')</em></h5>';

        // format table
        html += '<table>';

        // add header row
        html += '<tr><th>#</th><th>' +
            Object.keys(results[0]).join('</th><th>') +
            '</th></tr>';

        // add data
        for (var i = 0; i < results.length; i++) {
            html += '<tr><td>' + (i+1) + '</td><td>' +
                Object.values(results[i]).join('</td><td>') +
                '</td></tr>';
        }

        html += '<table>';
    }
    else {
        html = '<p>Nothing found.</p>';
    }

    // set output
    output(function (element) {
        element.className = '';
        element.innerHTML = html;
    });
};

window.runQuery = function (query, verbose) {
    if (!query) {
        return;
    }

    var r = new XMLHttpRequest();

    r.open('POST', '/api/snapshots/' + window.snapshot_id + '/query');
    r.setRequestHeader('Content-Type', 'application/json');
    r.setRequestHeader('Authorization', 'Bearer ' + window.api_token);

    r.onload = function() {
        var results = JSON.parse(r.responseText);

        if (r.status === 200) {

            renderChart(results.data);
            renderTable(results.data);
        }

        // error
        else if (results.errors) {
            renderError(results.errors[0]);
        }
        // unknown error
        else {
            renderError('Something went wrong, please try again shortly');
        }
    };

    r.send(JSON.stringify({ query: query }));
};

// TODO FIXME
// window.exportToCSV = function (query) {
//     alasql.promise('SELECT * INTO CSV("query_results.csv", { separator: ","}) FROM (' + query + ')', [dataset])
//         .then(function(){
//              console.log('File was saved');
//         }).catch(function(error){
//              console.log('Error:', error);
//         });
// };

window.onload = function () {
    runQuery(document.querySelector('#query').value)
};
