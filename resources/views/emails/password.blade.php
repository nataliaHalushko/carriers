@component('mail::message')
# Introduction

Your password reset code: {{$code}}.



Thanks,<br>
{{ config('app.name') }}
@endcomponent
