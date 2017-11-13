@component('activity.activity')
    @slot('heading')
        <strong>{{ $user->name }}</strong> started :
        <strong>
            <a href="{{ route('threads.show', [
                    'thread' => $activity->subject->id,
                    'channel' => $activity->subject->channel_id
                ]) }}">
                {{ $activity->subject->title }}
            </a>
        </strong>
        <span>
            {{ $activity->subject->created_at->diffForHumans() }}
        </span>

    @endslot

    @slot('body')
        {{ $activity->subject->body }}
    @endslot
@endcomponent
