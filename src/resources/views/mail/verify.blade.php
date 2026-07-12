<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifier votre adresse email</title>
</head>
<body style="margin:0; padding:0; background:#050606; font-family:Arial, Helvetica, sans-serif; color:#ffffff;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#050606; margin:0; padding:40px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:620px; border:1px solid #2d3431; border-radius:24px; background:#0b0d0c; overflow:hidden;">
                    <tr>
                        <td style="padding:36px 32px 20px 32px; text-align:center;">
                            <svg width="42" height="50" viewBox="0 0 32 38" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:block; margin:0 auto 24px auto;">
                                <path d="M0 38L4.40149 0H20.9368C24.0297 0 29.6208 1.63691 29.6208 8.76923C29.6208 11.6925 28.5502 16.4861 20.9368 16.4861H18.5576L19.0335 10.9908L6.54275 17.8892L17.6059 24.32L18.0818 19.1754H25.5762C27.8364 19.1754 32 22.0985 32 28.0615C32 31.3354 29.6208 38 21.8885 38H0Z" fill="#85A795"/>
                            </svg>

                            <p style="margin:0 0 10px 0; color:#85A795; font-size:13px; font-weight:700; letter-spacing:.08em; text-transform:uppercase;">Verification</p>
                            <h1 style="margin:0; color:#ffffff; font-size:30px; line-height:1.2; font-weight:800;">Confirmez votre email</h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:8px 32px 0 32px;">
                            <p style="margin:0 0 18px 0; color:#d7dfdb; font-size:16px; line-height:1.7;">Bonjour {{ $user->firstname }},</p>
                            <p style="margin:0 0 18px 0; color:#d7dfdb; font-size:16px; line-height:1.7;">Merci de votre inscription sur Budgie. Pour activer votre compte et acceder a votre espace, confirmez votre adresse email avec le bouton ci-dessous.</p>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding:14px 32px 28px 32px;">
                            <a href="{{ $verificationUrl }}" style="display:inline-block; padding:15px 28px; border-radius:999px; background:#b9c9cb; color:#050606; font-size:14px; font-weight:800; text-decoration:none;">Verifier mon adresse email</a>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0 32px 28px 32px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-top:1px solid #2d3431;">
                                <tr>
                                    <td style="padding-top:22px;">
                                        <p style="margin:0 0 12px 0; color:#99a39f; font-size:14px; line-height:1.6;">Si le bouton ne fonctionne pas, copiez ce lien dans votre navigateur :</p>
                                        <p style="margin:0; color:#85A795; font-size:13px; line-height:1.6; word-break:break-all;">{{ $verificationUrl }}</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0 32px 36px 32px;">
                            <p style="margin:0; color:#7f8985; font-size:13px; line-height:1.6;">Si vous n'etes pas a l'origine de cette inscription, vous pouvez ignorer cet email.</p>
                        </td>
                    </tr>
                </table>

                <p style="margin:22px 0 0 0; color:#66706c; font-size:12px;">Budgie - Gestion de finance personnelle</p>
            </td>
        </tr>
    </table>
</body>
</html>