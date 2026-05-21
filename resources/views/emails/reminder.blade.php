<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - CareerTrack Reminder</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f6f8; font-family: 'Inter', 'Segoe UI', Arial, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f6f8; padding: 40px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="560" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                    <tr>
                        <td style="background-color: #0891b2; padding: 24px 32px; text-align: center;">
                            <h1 style="color: #ffffff; font-size: 22px; font-weight: 700; margin: 0; letter-spacing: -0.02em;">CareerTrack</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 32px;">
                            <p style="font-size: 16px; color: #1A1A2E; margin: 0 0 6px 0; font-weight: 600;">Hey {{ $name }},</p>
                            <p style="font-size: 20px; color: #0891b2; margin: 0 0 20px 0; font-weight: 700;">Don't miss this! 🎯</p>

                            <p style="font-size: 14px; color: #546E7A; margin: 0 0 16px 0;">Here's a quick reminder for you:</p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #F0F4F8; border-radius: 6px; margin-bottom: 20px;">
                                <tr>
                                    <td style="padding: 16px 20px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding: 4px 0;">
                                                    <span style="font-size: 13px; color: #546E7A; font-weight: 500;">📌 &nbsp;</span>
                                                    <span style="font-size: 14px; color: #1A1A2E; font-weight: 600;">{{ $title }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 4px 0;">
                                                    <span style="font-size: 13px; color: #546E7A; font-weight: 500;">🕐 &nbsp;</span>
                                                    <span style="font-size: 14px; color: #1A1A2E;">{{ $date }} at {{ $time }}</span>
                                                </td>
                                            </tr>
                                            @if ($description)
                                            <tr>
                                                <td style="padding: 4px 0;">
                                                    <span style="font-size: 13px; color: #546E7A; font-weight: 500;">📝 &nbsp;</span>
                                                    <span style="font-size: 14px; color: #1A1A2E;">{{ $description }}</span>
                                                </td>
                                            </tr>
                                            @endif
                                            @if ($relatedInfo)
                                            <tr>
                                                <td style="padding: 4px 0;">
                                                    <span style="font-size: 13px; color: #546E7A; font-weight: 500;">🏢 &nbsp;</span>
                                                    <span style="font-size: 14px; color: #1A1A2E;">{{ $relatedInfo }}</span>
                                                </td>
                                            </tr>
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 14px; color: #546E7A; margin: 0 0 20px 0; font-style: italic;">Stay focused, you're on the right track! 🚀</p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $url }}" style="display: inline-block; background-color: #0891b2; color: #ffffff; font-size: 15px; font-weight: 600; text-decoration: none; padding: 12px 32px; border-radius: 6px;">View on Calendar</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #fafafa; padding: 20px 32px; text-align: center; border-top: 1px solid #e4e4e7;">
                            <p style="font-size: 13px; color: #94A3B8; margin: 0 0 4px 0;">Keep pushing forward,</p>
                            <p style="font-size: 14px; color: #0891b2; margin: 0 0 12px 0; font-weight: 600;">CareerTrack Team</p>
                            <p style="font-size: 11px; color: #94A3B8; margin: 0;">This is an automated reminder from CareerTrack.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
