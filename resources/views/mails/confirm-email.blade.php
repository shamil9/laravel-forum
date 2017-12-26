@component('mail::message')
    # Please confirm your email

    Please click 'confirm' button to confirm your email

    @component('mail::button', ['url' => route('account.confirm')])
        Confirm
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
