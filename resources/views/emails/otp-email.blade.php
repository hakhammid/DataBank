<x-mail>
    <div style="text-align: center;">
        <h1 style="color: #1f2937; font-size: 24px; margin-bottom: 20px;">Password Reset OTP</h1>
        
        <p style="color: #4b5563; margin-bottom: 30px;">
            Your One-Time Password (OTP) for password reset is:
        </p>

        <div style="background-color: #f3f4f6; border-radius: 8px; padding: 20px; margin: 20px 0;">
            <h2 style="font-size: 32px; color: #1f2937; margin: 0;">{{ $otp }}</h2>
        </div>

        <p style="color: #4b5563; margin-top: 30px;">
            This OTP will expire in 10 minutes.<br>
            If you did not request a password reset, please ignore this email.
        </p>

        <p style="color: #6b7280; margin-top: 40px; font-size: 14px;">
            Thanks,<br>
            {{ config('app.name') }}
        </p>
    </div>
</x-mail> 