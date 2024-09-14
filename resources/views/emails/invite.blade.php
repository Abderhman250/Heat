@component('mail::message')
# Invitation to Group

You have been invited to join a group. Please click the button below to accept the invitation.

@component('mail::button', ['url' => route('invitation.accept', ['group_id' => $group_id])])
Accept Invitation
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
