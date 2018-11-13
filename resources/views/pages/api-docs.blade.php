@extends('layouts.app', ['title' => 'API documentation'])

@section('content')

<h4>API authentication</h4>

@if ($token)
<p>
    Your API token is <code>{{ $token }}</code>
</p>
@endif

<p>
    API url is <code>{{ route('api.index') }}</code>
</p>

<p>
    You must specify an API token when using any of the following endpoints. You can specify API token as part of query string or in the payload itself. Please keep it secret, do not share it or store in public code repositories.
</p>

<h4>API endpoints</h4>

<div id="index"></div>


<h5 id="list-snapshots"><a href="#list-snapshots">List snapshots</a></h5>
<h6>Request example</h6>
<pre><code>curl -X GET \
'{{ route('api.snapshots.index', ['api_token' => $token]) }}' \
-H 'Accept: application/json'
</code></pre>
<h6>Response example</h6>
<pre><code>[
    {
        "id": 1,
        "status": "completed",
        "refresh_daily": true,
        "name": "Hacker News",
        "url": "https://news.ycombinator.com/news?p=*",
        "from": 1,
        "to": 2,
        "current": 2,
        "created_at": "{{ $now }}",
        "updated_at": "{{ $now }}"
    }
]
</code>
</pre>

<h5 class="endpoint" id="show-snapshot"><a href="#show-snapshot">Show snapshot</a></h5>
<h6>Request example</h6>
<pre><code>curl -X GET \
'{{ route('api.snapshots.show', [1, 'api_token' => $token]) }}' \
-H 'Accept: application/json'
</code>
</pre>

<h6>Response example</h6>
<pre><code>{
    "id": 1,
    "status": "completed",
    "refresh_daily": true,
    "name": "Hacker News",
    "url": "https://news.ycombinator.com/news?p=*",
    "from": 1,
    "to": 2,
    "current": 2,
    "created_at": "{{ $now }}",
    "updated_at": "{{ $now }}"
}
</code>
</pre>

<h5 class="endpoint" id="create-snapshot"><a href="#create-snapshot">Create a snapshot</a></h5>
<h6>Request example</h6>
<pre><code>curl -X POST \
'{{ route('api.snapshots.store', ['api_token' => $token]) }}' \
-H 'Accept: application/json' \
-H 'Content-Type: application/json' \
-d '{
"name": "Hacker News",
"url": "https://news.ycombinator.com/news?p=*",
"from": 1,
"to": 2
}'
</code>
</pre>
<h6>Response example</h6>
<pre><code>{
    "id": 2,
    "status": "in_progress",
    "refresh_daily": false,
    "name": "Hacker News",
    "url": "https://news.ycombinator.com/news?p=*",
    "from": 1,
    "to": 2,
    "current": 0,
    "created_at": "{{ $now }}",
    "updated_at": "{{ $now }}"
}
</code>
</pre>

<h5 class="endpoint" id="update-snapshot"><a href="#update-snapshot">Update a snapshot</a></h5>
<h6>Request example</h6>
<pre><code>curl -X PUT \
'{{ route('api.snapshots.update', [1, 'api_token' => $token]) }}' \
-H 'Accept: application/json' \
-H 'Content-Type: application/json' \
-d '{
"name": "Updated name",
"url": "https://news.ycombinator.com/news?p=*",
"from": 1,
"to": 5,
"refresh_daily": true
}'
</code>
</pre>
<h6>Response example</h6>
<pre><code>{
    "id": 1,
    "status": "in_progress",
    "refresh_daily": true,
    "name": "Updated name",
    "url": "https://news.ycombinator.com/news?p=*",
    "from": 1,
    "to": 5,
    "current": 0,
    "created_at": "{{ $now }}",
    "updated_at": "{{ $now }}"
}
</code>
</pre>

<h5 class="endpoint" id="delete-snapshot"><a href="#delete-snapshot">Delete a snapshot</a></h5>

<h6>Request example</h6>
<pre><code>curl -X DELETE \
'{{ route('api.snapshots.destroy', [1, 'api_token' => $token]) }}' \
-H 'Accept: application/json'
</code>
</pre>
<h6>Response example</h6>
<pre><code>HTTP status code 204 (no content) on success.
</code>
</pre>

<h5 class="endpoint" id="refresh-snapshot"><a href="#refresh-snapshot">Refresh a snapshot</a></h5>
<p>
    Discards all pages and downloads them again.
</p>

<h6>Request example</h6>
<pre><code>curl -X POST \
  '{{ route('api.snapshots.refresh', [1, 'api_token' => $token]) }}' \
  -H 'Accept: application/json'
</code>
</pre>
<h6>Response example</h6>
<pre><code>HTTP status code 202 (accepted) on success.
</code>
</pre>

<h5 id="stop-snapshot"><a href="#stop-snapshot">Stop a snapshot</a></h5>
<p>
    Stop an ongoing snapshot.
</p>
<h6>Request example</h6>
<pre><code>curl -X POST \
'{{ route('api.snapshots.stop', [1, 'api_token' => $token]) }}' \
-H 'Accept: application/json'
</code>
</pre>

<h6>Response example</h6>
<pre><code>HTTP status code 202 (accepted) on success.
</code>
</pre>

<h5 class="endpoint" id="retry-snapshot"><a href="#retry-snapshot">Retry a snapshot</a></h5>
<p>
    Discards the last page and retries to download it again.
</p>

<h6>Request example</h6>
<pre><code>curl -X POST \
'{{ route('api.snapshots.retry', [1, 'api_token' => $token]) }}' \
-H 'Accept: application/json'
</code>
</pre>

<h6>Response example</h6>
<pre><code>HTTP status code 202 (accepted) on success.
</code>
</pre>


<h5 class="endpoint" id="index-variables"><a href="#index-variables">List snapshot variables</a></h5>
<p>
    Show all snapshot variables.
</p>
<h6>Request example</h6>
<pre><code>curl -X GET \
'{{ route('api.variables.index', [1, 'api_token' => $token]) }}' \
-H 'Accept: application/json'
</code>
</pre>

<h6>Response example</h6>
<pre><code>[
    {
        "id": 1,
        "snapshot_id": 1,
        "name": "price",
        "selector": ".price",
        "type": "numeric",
        "current_page": 0,
        "created_at": "{{ $now }}",
        "updated_at": "{{ $now }}"
    }
]
</code>
</pre>

<h5 class="endpoint" id="show-variable"><a href="#show-variable">Show a variable</a></h5>
<p>
    Show specific snapshot variable.
</p>
<h6>Request example</h6>
<pre><code>curl -X GET \
'{{ route('api.variables.show', [1, 'api_token' => $token]) }}' \
-H 'Accept: application/json'
</code>
</pre>

<h6>Response example</h6>
<pre><code>{
    "id": 1,
    "snapshot_id": 1,
    "name": "price",
    "selector": ".price",
    "type": "numeric",
    "current_page": 0,
    "created_at": "{{ $now }}",
    "updated_at": "{{ $now }}"
}
</code>
</pre>

<h5 class="endpoint" id="create-variable"><a href="#create-variable">Create a variable</a></h5>
<p>
    Creates a new variable to be used in queries. It might take some time until the results are available in the query.
</p>
<h6>Request example</h6>
<pre><code>curl -X POST \
'{{ route('api.variables.store', [1, 'api_token' => $token]) }}' \
-H 'Accept: application/json' \
-H 'Content-Type: application/json' \
-d '{
"name": "score",
"selector": ".score"
}'
</code>
</pre>

<h6>Response example</h6>
<pre><code>{
    "id": 1,
    "snapshot_id": 1,
    "name": "score",
    "selector": ".score",
    "type": "numeric",
    "current_page": 0,
    "created_at": "{{ $now }}",
    "updated_at": "{{ $now }}"
}
</code>
</pre>

<h5 class="endpoint" id="update-variable"><a href="#update-variable">Update a variable</a></h5>
<p>
    Updates a variable to be used in queries. It might take some time until the results are available in the query.
</p>
<h6>Request example</h6>
<pre><code>curl -X PUT \
'{{ route('api.variables.update', [1, 'api_token' => $token]) }}' \
-H 'Accept: application/json' \
-H 'Content-Type: application/json' \
-d '{
"name": "updated",
"selector": ".price"
}'
</code>
</pre>

<h6>Response example</h6>
<pre><code>{
    "id": 1,
    "snapshot_id": 1,
    "name": "updated",
    "selector": ".price",
    "type": "numeric",
    "current_page": 0,
    "created_at": "{{ $now }}",
    "updated_at": "{{ $now }}"
}
</code>
</pre>

<h5 class="endpoint" id="delete-variable"><a href="#delete-variable">Delete a variable</a></h5>
<p>
    Deletes a variable.
</p>
<h6>Request example</h6>
<pre><code>curl -X DELETE \
  '{{ route('api.variables.destroy', [1, 'api_token' => $token]) }}' \
-H 'Accept: application/json'
</code>
</pre>

<h6>Response example</h6>
<pre><code>HTTP status code 204 (no content) on success.
</code>
</pre>

<h5 class="endpoint" id="query-snapshot"><a href="#query-snapshot">Run a SQL query</a></h5>
<p>
    You can run a SQL query against your snapshot data. You must create variables before that though.
</p>
<h6>Request example</h6>
<pre><code>curl -X POST \
'{{ route('api.query', [1, 'api_token' => $token]) }}' \
-H 'Accept: application/json' \
-H 'Content-Type: application/json' \
-d '{
"query": "SELECT * FROM dataset"
}'
</code>
</pre>
<h6>Response example</h6>
<pre><code>{
    "meta": {
        "count": 2
    },
    "data": [
        {
            "score": "90 points"
        },
        {
            "score": "81 points"
        }
    ]
}
</code>
</pre>


<!-- generate index list -->
<script>

    var html = '<ul>';

    var endpoints = document.querySelectorAll('.endpoint');

    var e;
    for (var i = 0; i < endpoints.length; i++) {
        e = endpoints[i];
        html += '<li><a href="#' + e.id + '">' + e.innerText + '</a></li>';
    }

    html += '</ul>';

    document.querySelector('#index').innerHTML = html;
</script>

@endsection
