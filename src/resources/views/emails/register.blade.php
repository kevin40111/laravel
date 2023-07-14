@component('mail::message')
<h1>Welcome!</h1>
<p>Please click the button below to verify your email address.</p>

@component('mail::button', ['url' => $link])
  Verify Email Address
@endcomponent

<p>If you did not create an account, no further action is required.</p>

@slot('subcopy')
If you're having trouble clicking the button, copy and paste the URL below into your web browser:
<span class="break-all">[{{ $link }}]({{ $link }})</span>
@endslot

@endcomponent
