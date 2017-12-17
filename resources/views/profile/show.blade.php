@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-header">
            <h1>
                <img id="avatar" src="{{ $user->avatar }}" alt="{{ $user->name }}" height="30">
                    {{ $user->name }}
                <small>Joined {{ $user->created_at->diffForHumans() }}</small>
            </h1>

            @can('update', $user)
                <file-input name="avatar"></file-input>
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

@section('scripts')
    <script>
        window.events.$on('fileRead', (data => {
            var form = new FormData();
            form.append('avatar', data.file);

            window.axios.post("{{ route('api.avatar.store', auth()->user()) }}", form)
                .then(res => {
                    window.flash('Avatar updated');
                    document.querySelector('#avatar').src = data.url;
                })
        }));
    </script>
@endsection