@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Create a New Thread</div>

                    <div class="panel-body">
                        <form method="POST" action="{{ route('threads.store', $channel) }}">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label for="title">Title:</label>
                                <input type="text" class="form-control" id="title"
                                       name="title" value="{{ old('title') }}" required>
                                @if($errors->has('title'))
                                    <p class="text-danger" role="alert">{{ $errors->first('title') }}</p>
                                @endif
                                <input type="hidden" class="form-control" name="channel_id"
                                       value="{{ $channel->id }}" required>
                            </div>

                            <div class="form-group">
                                <label for="body">Body:</label>
                                <textarea name="body" id="body" class="form-control" rows="8"
                                          required>{{ old('body') }}</textarea>
                                @if($errors->has('body'))
                                    <p class="text-danger" role="alert">{{ $errors->first('body') }}</p>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-primary">Publish</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection