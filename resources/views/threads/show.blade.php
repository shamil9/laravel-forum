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

                    @if($thread->bestReply()->exists())
                        <h3>Best Reply</h3>

                        @include(
                            'threads.partials.reply',
                            ['reply' => $thread->bestReply->reply, 'best' => true]
                        )

                        <hr>
                    @endif

                    @foreach($thread->replies as $reply)
                        @include(
                             'threads.partials.reply',
                             ['reply' => $reply, 'best' => false]
                        )
                    @endforeach

                    {{ $replies->links() }}

                    @if(auth()->check() && ! $thread->locked)
                        <form v-if="! locked" method="post" class="form-group"
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
                    @else
                        <h3 v-else>This thread has been locked.</h3>
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

                            @if(auth()->check())
                                <subscribe-button
                                    route="{{ route('subscription.store', [$thread]) }}"
                                    :active="{{ json_encode($thread->isSubscribed) }}">
                                </subscribe-button>
                            @endif

                            @if (auth()->check() && auth()->user()->is_admin)
                                <lock-button locked="{{ $thread->locked }}"></lock-button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>
@stop
