@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="/css/vendor/jquery.atwho.min.css">
@endsection

@section('content')
    <thread-view :attributes="{{ $thread }}" inline-template>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="level">
                                <span class="flex">{{ $thread->title }}</span>
                                @can('destroy', $thread)
                                    <span>
                                        <form action="{{ route('threads.destroy', [
                                                'channel' => $thread->channel_id,
                                                'thread' => $thread
                                            ]) }}"
                                              method="post">
                                              {{ csrf_field() }}
                                              {{ method_field('delete') }}
                                            <button class="btn btn-link">Delete</button>
                                        </form>
                                    </span>
                                @endcan
                            </div>
                        </div>
                        <div class="panel-body">
                            <p>{{ $thread->body }}</p>
                        </div>
                    </div>

                    @each('threads/partials/reply', $thread->replies, 'reply')

                    {{ $replies->links() }}

                    @if(auth()->check())
                        <form method="post" class="form-group"
                              action="{{ route('replies.store', $thread) }}">
                            {{ csrf_field() }}
                            <textarea name="body"
                                      class="form-control"
                                      id="reply" cols="30" rows="5"
                            >
                            </textarea><br>
                            <button type="submit" class="btn btn-primary">
                                Add
                            </button>
                        </form>
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <p>This thread was published {{ $thread->created_at->diffForHumans() }} by
                                <a href="{{ route('profile.show', $thread->owner) }}">
                                    {{ $thread->owner->name }}
                                </a>
                                and currently has @{{ repliesCount }}
                                {{ str_plural('comment', $thread->replies_count) }}.
                            </p>

                            @if(Auth::check())
                                <subscribe-button
                                    route="{{ route('subscription.store', [$thread]) }}"
                                    :active="{{ json_encode($thread->isSubscribed) }}">
                                </subscribe-button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>
@stop