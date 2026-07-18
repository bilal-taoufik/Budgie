# Contribuer à Budgie

Merci de l'intérêt que vous portez à Budgie. Toute contribution utile est la
bienvenue : correction de bug, amélioration, test, documentation ou proposition
d'interface.

En participant au projet, vous acceptez de respecter le
[Code de conduite](CODE_OF_CONDUCT.md).

## Avant de commencer

Avant d'écrire du code :

1. vérifiez qu'une issue similaire n'existe pas déjà ;
2. ouvrez une issue pour décrire le problème ou l'amélioration proposée ;
3. attendez une validation avant d'entreprendre une modification importante.

Pour un correctif simple ou une amélioration de documentation, vous pouvez
directement ouvrir une pull request clairement expliquée.

## Installation locale

Les prérequis sont Git, Docker Desktop et Docker Compose. Les ports `8000`,
`5173`, `5432`, `8025` et `1025` doivent être disponibles.

```bash
git clone https://github.com/bilal-taoufik/Budgie.git
cd Budgie
cp src/.env.example src/.env
docker compose -f docker-compose.local.yml up -d --build
docker compose -f docker-compose.local.yml exec app composer install
docker compose -f docker-compose.local.yml exec app npm install
docker compose -f docker-compose.local.yml exec app php artisan key:generate
docker compose -f docker-compose.local.yml exec app php artisan migrate
```

Renseignez les paramètres PostgreSQL dans `src/.env` avant de lancer les
migrations. L'application est ensuite accessible sur
[http://localhost:8000](http://localhost:8000) et les e-mails de développement
sur [http://localhost:8025](http://localhost:8025).

Pour lancer Vite avec le rechargement automatique :

```bash
docker compose -f docker-compose.local.yml exec app npm run dev -- --host 0.0.0.0
```

## Créer une branche

Créez une branche courte et descriptive depuis la branche principale à jour :

```bash
git checkout main
git pull 
git checkout -b feature/nom-de-la-fonctionnalite
```

Préfixes recommandés :

- `feature/` pour une fonctionnalité ;
- `fix/` pour une correction ;
- `docs/` pour la documentation ;
- `test/` pour les tests ;
- `refactor/` pour une restructuration sans changement fonctionnel.

## Règles de développement

- Respectez les conventions Laravel et PSR-12.
- Utilisez les mécanismes Laravel existants : validation avec des
  `FormRequest`, autorisations et requêtes Eloquent paramétrées.
- Vérifiez systématiquement qu'un utilisateur ne peut accéder qu'à ses propres
  comptes et transactions.
- N'ajoutez aucun secret, mot de passe, token ou fichier `.env` au dépôt.
- Limitez chaque contribution à un objectif précis et évitez les modifications
  sans rapport avec celui-ci.
- Ajoutez ou adaptez les tests pour tout changement de comportement.

Vous pouvez formater le code PHP avec Laravel Pint :

```bash
docker compose -f docker-compose.local.yml exec app ./vendor/bin/pint
```

## Tests et vérifications

Avant d'ouvrir une pull request, exécutez au minimum les tests concernés :

```bash
docker compose -f docker-compose.local.yml exec -T app php artisan test \
  tests/Feature/Customer \
  tests/Feature/Auth/PasswordResetTest.php \
  tests/Unit/TransactionFrequencyTest.php
```

Vérifiez également que les assets de production se compilent :

```bash
docker compose -f docker-compose.local.yml exec app npm run build
```

Les anciens tests générés par Laravel peuvent encore cibler des routes Breeze
qui ne font pas partie de Budgie. Consultez le README avant de lancer toute la
suite sans filtre.

## Commits

Rédigez des commits atomiques avec un message clair, de préférence à
l'impératif. Exemples :

```text
Ajoute le filtre des transactions par description
Corrige le calcul des échéances mensuelles
Documente la configuration de Mailpit
```

## Pull requests

Une pull request doit :

- expliquer le besoin et la solution retenue ;
- référencer l'issue associée, si elle existe ;
- décrire les tests exécutés ;
- inclure des captures d'écran pour toute modification visuelle ;
- signaler les migrations, nouvelles variables d'environnement ou ruptures de
  compatibilité ;
- rester suffisamment petite pour être relue facilement.

Les retours de revue font partie du processus. Répondez-y avec courtoisie et
apportez les corrections dans la même branche.

## Signaler un problème de sécurité

Ne publiez pas publiquement une vulnérabilité contenant des informations
exploitables. Contactez directement les responsables du dépôt afin qu'elle soit
évaluée et corrigée de manière confidentielle.

## Licence

En soumettant une contribution, vous acceptez qu'elle soit distribuée sous la
même licence que le projet.
