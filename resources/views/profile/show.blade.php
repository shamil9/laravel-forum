@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-header">
            <h1>
                <img src="{{ $user->avatar }}" alt="{{ $user->name }}" height="30">
                <a href="{{ route('profile.show', $user) }}">
                    {{ $user->name }}
                </a>
                <small>Joined {{ $user->created_at->diffForHumans() }}</small>
            </h1>
            @can('update', $user)
                <form action="{{ route('api.avatar.store', auth()->user()) }}"
                      enctype="multipart/form-data"
                      method="post"
                >
                    {{ csrf_field() }}
                    <input type="file" name="avatar">
                    <button type="submit">Save</button>
                </form>
            @endcan
        </div>
        @foreach ($activities as $date => $activity)
            <h3 class="page-header">{{ $date }}</h3>
            @foreach ($activity as $record)
                @include('activity/' . $record->type, ['activity' => $record])
            @endforeach
        @endforeach
    </div>
@endsection