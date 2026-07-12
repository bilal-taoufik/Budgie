<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue sur Budgie</title>
</head>
<body style="margin:0; padding:0; background:#050606; font-family:Arial, Helvetica, sans-serif; color:#ffffff;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#050606; margin:0; padding:40px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:620px; border:1px solid #2d3431; border-radius:24px; background:#0b0d0c; overflow:hidden;">
                    <tr>
                        <td style="padding:38px 32px 18px 32px; text-align:center;">
                            <svg width="42" height="50" viewBox="0 0 32 38" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:block; margin:0 auto 24px auto;">
                                <path d="M0 38L4.40149 0H20.9368C24.0297 0 29.6208 1.63691 29.6208 8.76923C29.6208 11.6925 28.5502 16.4861 20.9368 16.4861H18.5576L19.0335 10.9908L6.54275 17.8892L17.6059 24.32L18.0818 19.1754H25.5762C27.8364 19.1754 32 22.0985 32 28.0615C32 31.3354 29.6208 38 21.8885 38H0Z" fill="#85A795"/>
                            </svg>

                            <p style="margin:0 0 10px 0; color:#85A795; font-size:13px; font-weight:700; letter-spacing:.08em; text-transform:uppercase;">Bienvenue</p>
                            <h1 style="margin:0; color:#ffffff; font-size:32px; line-height:1.2; font-weight:800;">Votre espace Budgie est pret</h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:8px 32px 10px 32px;">
                            <p style="margin:0 0 18px 0; color:#d7dfdb; font-size:16px; line-height:1.7;">Bonjour {{ $user->firstname }},</p>
                            <p style="margin:0 0 18px 0; color:#d7dfdb; font-size:16px; line-height:1.7;">Merci d'avoir rejoint Budgie. Vous pouvez maintenant organiser vos comptes, suivre vos revenus, vos depenses et garder une vision claire de votre budget.</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:8px 32px 34px 32px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #2d3431; border-radius:18px; background:#080a09;">
                                <tr>
                                    <td style="padding:20px;">
                                        <p style="margin:0 0 8px 0; color:#ffffff; font-size:16px; font-weight:800;">Premiere etape</p>
                                        <p style="margin:0; color:#99a39f; font-size:14px; line-height:1.6;">Confirmez votre adresse email avec le message de verification recu juste apres celui-ci.</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <p style="margin:22px 0 0 0; color:#66706c; font-size:12px;">Budgie - Gestion de finance personnelle</p>
            </td>
        </tr>
    </table>
</body>
</html>