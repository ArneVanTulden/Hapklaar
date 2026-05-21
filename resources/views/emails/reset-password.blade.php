<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wachtwoord resetten</title>
</head>
<body style="margin:0;padding:0;background-color:#f5f5f5;font-family:'Arial Black',Arial,sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f5f5f5;padding:40px 20px;">
        <tr>
            <td align="center">
                <table width="560" cellpadding="0" cellspacing="0" style="max-width:560px;width:100%;">

                    {{-- Header --}}
                    <tr>
                        <td style="background-color:#E5006E;border:3px solid #000000;padding:24px 32px;text-align:center;box-shadow:5px 5px 0px 0px #000000;">
                            <span style="font-size:28px;font-weight:900;color:#ffffff;text-transform:uppercase;font-style:italic;letter-spacing:2px;">HAPKLAAR</span>
                        </td>
                    </tr>

                    {{-- Spacer --}}
                    <tr><td style="height:8px;"></td></tr>

                    {{-- Main card --}}
                    <tr>
                        <td style="background-color:#ffffff;border:3px solid #000000;padding:40px 36px;box-shadow:5px 5px 0px 0px #000000;">

                            {{-- Title --}}
                            <p style="margin:0 0 8px 0;font-size:26px;font-weight:900;text-transform:uppercase;color:#000000;letter-spacing:1px;font-style:italic;line-height:1.1;">WACHTWOORD<br>VERGETEN?</p>

                            {{-- Divider --}}
                            <div style="width:48px;height:4px;background-color:#E5006E;margin:16px 0;"></div>

                            {{-- Body --}}
                            <p style="margin:0 0 8px 0;font-size:14px;font-weight:700;color:#000000;line-height:1.6;">Hoi {{ $username }},</p>
                            <p style="margin:0 0 28px 0;font-size:14px;font-weight:500;color:#333333;line-height:1.7;">
                                We hebben een verzoek ontvangen om het wachtwoord van je account te resetten. Klik op de knop hieronder om een nieuw wachtwoord in te stellen.
                            </p>

                            {{-- CTA Button --}}
                            <table cellpadding="0" cellspacing="0" style="margin:0 0 28px 0;">
                                <tr>
                                    <td style="background-color:#E5006E;border:3px solid #000000;box-shadow:4px 4px 0px 0px #000000;">
                                        <a href="{{ $resetUrl }}"
                                           style="display:inline-block;padding:16px 32px;font-size:13px;font-weight:900;color:#ffffff;text-decoration:none;text-transform:uppercase;letter-spacing:2px;">
                                            WACHTWOORD INSTELLEN &nbsp;&rarr;
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            {{-- Expiry notice --}}
                            <p style="margin:0 0 24px 0;font-size:12px;font-weight:700;color:#666666;text-transform:uppercase;letter-spacing:1px;">
                                Deze link verloopt over {{ $expireMinutes }} minuten.
                            </p>

                            {{-- Divider line --}}
                            <div style="border-top:2px solid #000000;margin:24px 0;"></div>

                            {{-- Fallback URL --}}
                            <p style="margin:0 0 6px 0;font-size:11px;font-weight:900;color:#000000;text-transform:uppercase;letter-spacing:1px;">Knop werkt niet?</p>
                            <p style="margin:0;font-size:11px;font-weight:500;color:#555555;line-height:1.6;word-break:break-all;">
                                Kopieer deze link: <span style="color:#E5006E;">{{ $resetUrl }}</span>
                            </p>

                        </td>
                    </tr>

                    {{-- Spacer --}}
                    <tr><td style="height:8px;"></td></tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background-color:#000000;border:3px solid #000000;padding:16px 32px;text-align:center;">
                            <p style="margin:0 0 4px 0;font-size:11px;font-weight:900;color:#ffffff;text-transform:uppercase;letter-spacing:2px;">HAPKLAAR</p>
                            <p style="margin:0;font-size:10px;font-weight:500;color:#999999;">
                                Heb jij dit niet aangevraagd? Dan hoef je niks te doen.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
