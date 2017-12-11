@component('activity.activity')
    @slot('heading')
        <strong>{{ $user->name }}</strong> replied to :
        <strong>
            <a href="{{ route('threads.show', [
                    'thread' => $activity->subject->thread->id,
                    'channel' => $activity->subject->thread->channel_id
                ]) }}">
                {{ $activity->subject->thread->title }}
            </a>
        </strong>
        <span>
            {{ $activity->subject->created_at->diffForHumans() }}
        </span>
    @endslot

    @slot('body')
        {!! $activity->subject->body !!}
    @endslot
@endcomponent
