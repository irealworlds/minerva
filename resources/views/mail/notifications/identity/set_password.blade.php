<x-mail::message>
# Hello {{ $firstName }},

A new account in your name has been opened at {{ $appName }}.

To be able to use your account, you need to set up a password for it, which you can do by clicking the button below.

<x-mail::button :url="$uri">
Create password
</x-mail::button>

If this was not you, please inform us immediately so that we can take the necessary steps.

Best regards,<br>
{{ config('app.name') }}
</x-mail::message>
