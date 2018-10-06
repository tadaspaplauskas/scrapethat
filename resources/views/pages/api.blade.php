@extends('layouts.app', ['title' => 'API documentation'])

@section('content')

<h2>API authentication</h2>

<p>
    Your API token is:
    <code>{{ $token }}</code>
</p>
<p>
    API url is:
    <code>{{ $url }}</code>
</p>

<p>
    You must specify an API token when using any of the following endpoints. You can specify API token as part of query string or in the payload itself. Please keep it secret, do not share it or store in public code repositories.
</p>

<h2>API endpoints</h2>

<h3 id="list-snapshots"><a href="#list-snapshots">List snapshots</a></h3>

<h4>Request</h4>
<pre><code>curl -X GET \
'{{ $url }}/snapshots?api_token={{ $token }}' \
-H 'Accept: application/json'
</code></pre>

<h4>Response</h4>
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
        "created_at": "2018-09-19 17:54:05",
        "updated_at": "2018-10-06 13:11:37"
    }
]
</code>
</pre>

<h3 id="get-snapshot"><a href="#get-snapshot">Get specific snapshot</a></h3>

<h4>Request</h4>
<pre><code>curl -X GET \
'{{ $url }}/snapshots/1?api_token={{ $token }}' \
-H 'Accept: application/json'
</code>
</pre>

<h4>Response</h4>
<pre><code>{
    "id": 1,
    "status": "completed",
    "refresh_daily": true,
    "name": "Hacker News",
    "url": "https://news.ycombinator.com/news?p=*",
    "from": 1,
    "to": 2,
    "current": 2,
    "created_at": "2018-09-19 17:54:05",
    "updated_at": "2018-10-06 13:11:37"
}
</code>
</pre>
@endsection
