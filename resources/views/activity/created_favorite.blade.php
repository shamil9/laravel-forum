@component('activity.activity')
    @slot('heading')
        <strong>
            <a href="{{ $activity->subject->favorited->path() }}#reply-{{ $activity->subject->favorited->id }}">
                {{ $user->name }} favorited a reply
            </a>
        </strong>
        <span>
            {{ $activity->subject->created_at->diffForHumans() }}
        </span>

    @endslot

    @slot('body')
        {{ $activity->subject->favorited->body }}
    @endslot
@endcomponent
