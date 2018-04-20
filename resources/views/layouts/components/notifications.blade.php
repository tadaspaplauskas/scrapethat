@auth
    @foreach (auth()->user()->unreadNotifications as $notification)
        @switch ($notification->type)
            @case (App\Notifications\DownloadPageProblem::class)
                <?php
                    $snapshot = App\Models\Snapshot::find($notification->data['snapshot_id']);
                ?>

                <div class="p1 italic shade round-corners dimgrey">
                    We got a
                    <strong>
                        {{ $notification->data['status_code'] }} {{ $notification->data['reason_phrase'] }}
                    </strong>

                    response from

                    <a href="{{ $notification->data['url'] }}" target="blank">{{ $notification->data['url'] }}</a>
                    
                    in <strong>
                        <a href="{{ route('snapshots.show', $snapshot->id) }}">{{ $snapshot->name }}</a>
                    </strong>

                    <div>
                        <form class="inline" method="POST" action="{{ route('snapshots.retry', $snapshot->id) }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="notification_id" value="{{ $notification->id }}">

                            <button>Retry</button>
                        </form>

                        <form class="inline" method="GET" action="{{ route('snapshots.edit', $snapshot->id) }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="notification_id" value="{{ $notification->id }}">

                            <button>Edit and retry</button>
                        </form>

                        <form class="inline" method="POST" action="{{ route('snapshots.stop', $snapshot->id) }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="notification_id" value="{{ $notification->id }}">

                            <button>Stop</button>
                        </form>

                    </div>
                </div>
            @break
        @endswitch
    @endforeach
@endauth