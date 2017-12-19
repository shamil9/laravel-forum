@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="page-header">
                        @if($channel)
                            <div class="pull-right">
                                <a href="{{ route('threads.create', $channel) }}">New Thread</a>
                            </div>
                        @endif
                    <h3> Forum Threads </h3>
                </div>
                @each ('threads.partials.thread', $threads, 'thread')
                {{ $threads->links() }}
            </div>
            <div class="col-md-4">
                <div class="page-header">
                    <h3> Trending Threads </h3>
                </div>

                <div class="panel panel-default">
                    <div class="panel-body">
                        <ul>
                            @foreach($trending as $thread)
                                <li>
                                    <a href="{{ url($thread->path) }}">{{ $thread->title }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection