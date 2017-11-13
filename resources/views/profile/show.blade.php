@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-header">
            <h1>
                <a href="{{ route('profile.show', $user) }}">
                    {{ $user->name }}
                </a>
                <small>Joined {{ $user->created_at->diffForHumans() }}</small>
            </h1>
        </div>
        @foreach ($activities as $date => $activity)
            <h3 class="page-header">{{ $date }}</h3>
            @foreach ($activity as $record)
                @include('activity/' . $record->type, ['activity' => $record])
            @endforeach
        @endforeach
    </div>
@endsection