<div style="width: 100%; display:block;">
    <h2>OTP for Email Verification</h2>
    <p>
        Please use the verification code below on our mobile application for email verification.
        <strong>
            {{ $userData->email_verified }}
        </strong>
        <br><br>
        <strong>{{ trans('labels.Sincerely') }},</strong><br>
        {{ trans('labels.ecommerceAppTeam') }}
    </p>
</div>
