@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
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
        </div>
    </div>
@endsection