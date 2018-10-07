@extends('layouts.app', ['title' => 'API documentation'])

@section('content')

<h4>API authentication</h4>

@if ($token)
<p>
    Your API token is <code>{{ $token }}</code>
</p>
@endif

<p>
    API url is <code>{{ $url }}</code>
</p>

<p>
    You must specify an API token when using any of the following endpoints. You can specify API token as part of query string or in the payload itself. Please keep it secret, do not share it or store in public code repositories.
</p>

<h4>API endpoints</h4>

<h5 id="list-snapshots"><a href="#list-snapshots">List snapshots</a></h5>

<h6>Request</h6>
<pre><code>curl -X GET \
'{{ $url }}/snapshots?api_token={{ $token }}' \
-H 'Accept: application/json'
</code></pre>

<h6>Response</h6>
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

<h5 id="show-snapshot"><a href="#show-snapshot">Show snapshot</a></h5>

<h6>Request</h6>
<pre><code>curl -X GET \
'{{ $url }}/snapshots/1?api_token={{ $token }}' \
-H 'Accept: application/json'
</code>
</pre>

<h6>Response</h6>
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

<h5 id="create-snapshot"><a href="#create-snapshot">Create a snapshot</a></h5>

<h6>Request</h6>
<pre><code>curl -X POST \
'{{ $url }}/snapshots?api_token={{ $token }}' \
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

<h6>Response</h6>
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

<h5 id="update-snapshot"><a href="#update-snapshot">Update a snapshot</a></h5>

<h6>Request</h6>
<pre><code>curl -X PUT \
'{{ $url }}/snapshots/1?api_token={{ $token }}' \
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

<h6>Response</h6>
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

<h5 id="delete-snapshot"><a href="#delete-snapshot">Delete a snapshot</a></h5>

<h6>Request</h6>
<pre><code>curl -X DELETE \
'{{ $url }}/snapshots/6?api_token={{ $token }}' \
-H 'Accept: application/json'
</code>
</pre>

<h6>Response</h6>
<pre><code>HTTP status code 204 on success.
</code>
</pre>
@endsection
