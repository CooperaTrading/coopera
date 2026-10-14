@component('mail::message')
# New contact form submission

You have received a new message from the **{{ config('app.name') }}** website contact form.

@component('mail::table')
| | |
|:--|:--|
| **Name** | {{ $data['name'] }} |
@if (!empty($data['company']))
| **Company** | {{ $data['company'] }} |
@endif
| **Email** | [{{ $data['email'] }}](mailto:{{ $data['email'] }}) |
@if (!empty($data['phone']))
| **Phone** | {{ $data['phone'] }} |
@endif
@endcomponent

## Message

{{ $data['message'] }}

@component('mail::subcopy')
Reply directly to this email to respond to {{ $data['name'] }}.
@endcomponent

@endcomponent
