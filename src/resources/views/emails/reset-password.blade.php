@component('mail::message')
<h1>We have received your request to reset your account password</h1>
<p>You can use the following code to recover your account:</p>

@component('mail::button', ['url' => $link])
  Reset password
@endcomponent

<p>The allowed duration of the code is one hour from the time the message was sent</p>

@slot('subcopy')
If you're having trouble clicking the button, copy and paste the URL below into your web browser:
<span class="break-all">[{{ $link }}]({{ $link }})</span>
@endslot

@endcomponent
