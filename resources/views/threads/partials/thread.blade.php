<div class="panel panel-default">
    <div class="panel-heading">
        <div class="level">
            <div class="flex">
                <h4>
                    <a href="{{ route(
                                'threads.show',
                                ['channel' => $thread->channel_id, 'thread' => $thread]
                            )
                        }}"
                    >
                        @if ($thread->hasUpdates())
                            <strong>{{ $thread->title }}</strong>
                        @else
                            {{ $thread->title }}
                        @endif
                    </a>
                </h4>
                <h5>Posted by:
                    <a href="{{ route('profile.show', $thread->owner) }}">
                        {{ $thread->owner->name }}
                    </a>
                </h5>
            </div>
            <strong>
                <a href="{{ route(
                                'threads.show',
                                ['channel' => $thread->channel_id, 'thread' => $thread]
                            )
                        }}"
                >
                    {{ $thread->replies_count }} {{ str_plural('reply', $thread->replies_count) }}
                </a>
            </strong>
        </div>
    </div>
    <div class="panel-body">{{ $thread->body }}</div>
</div>
