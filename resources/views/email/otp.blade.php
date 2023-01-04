<div style="font-family: Helvetica,Arial,sans-serif;min-width:1000px;overflow:auto;line-height:2">
    <div style="margin:50px auto;width:70%;padding:20px 0">
        <div style="border-bottom:1px solid #eee">
            <a href=""
                style="font-size:1.4em;color: #00466a;text-decoration:none;font-weight:600">{{ config('app.name') }}</a>
        </div>
        <p style="font-size:1.1em">Hi, {{ $details['name'] }}</p>
        <p>Thank you for choosing {{ config('app.name') }}. Use the following OTP to reset your password. OTP is valid
            for 5 minutes</p>
        <h2
            style="background: #00466a;margin: 0 auto;width: max-content;padding: 0 10px;color: #fff;border-radius: 4px;">
            {{ $details['otp'] }}</h2>
        <p style="font-size:0.9em;">Regards,<br />{{ config('app.name') }}</p>
        <hr style="border:none;border-top:1px solid #eee" />
        <div style="float:right;padding:8px 0;color:#aaa;font-size:0.8em;line-height:1;font-weight:300">
            <p>{{ config('app.name') }}</p>
            <p>{{ config('app.tagline') }}</p>
            <p>{{ config('app.address') }}</p>
        </div>
    </div>
</div>
