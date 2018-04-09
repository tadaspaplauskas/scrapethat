@auth
    @foreach (auth()->user()->unreadNotifications as $notification)
        @switch ($notification->type)
            @case (App\Notifications\DownloadPageProblem::class)
                <?php
                    $snapshot = App\Snapshot::find($notification->data['snapshot_id'])->first();
                ?>

                <div class="p1 italic bg-mintcream round-corners dimgrey">
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
                        <form class="inline" method="GET" action="{{ route('snapshots.edit', $snapshot->id) }}">
                            <button>Continue anyway</button>
                        </form>
                        
                        <form class="inline" method="GET" action="{{ route('snapshots.edit', $snapshot->id) }}">
                            <button>Edit and continue</button>
                        </form>

                        <form class="inline" method="DELETE" action="{{ route('snapshots.destroy', $snapshot->id) }}">
                            {{ csrf_field() }}
                            
                            <button>Cancel</button>
                        </form>
                    </div>
                </div>
            @break
        @endswitch
    @endforeach
@endauth