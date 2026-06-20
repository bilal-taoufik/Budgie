<h1>Bonjour {{ $user->firstname }},</h1>

<p>Merci de votre inscription sur Budgie.</p>

<p>Pour activer votre compte et accéder à l'application, veuillez confirmer votre adresse e-mail en cliquant sur le lien ci-dessous :</p>

<a href="{{ $verificationUrl }}">Vérifier mon adresse e-mail</a>

<p>Si vous n'êtes pas à l'origine de cette inscription, vous pouvez ignorer cet e-mail.</p>

<p>À bientôt,</p>

<p>L'équipe Budgie</p>