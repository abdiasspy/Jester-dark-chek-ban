JESTER DARK BAN CHECKER v2

- Côte d'Ivoire (+225) par défaut.
- Sélecteur avec pays internationaux + recherche.
- Le site appelle check.php, qui appelle l'API fournie.
- Les résultats ambigus sont refusés : aucun statut aléatoire.
- admin.php permet de modifier les deux textes après authentification.
- Le mot de passe est stocké uniquement sous forme de hash dans admin.php.

IMPORTANT :
L'API fournie doit réellement accepter les numéros internationaux et retourner un format exploitable.
Le détecteur accepte notamment :
  banned / ban / is_banned / isBanned : booléen
  status : banned, ban, blocked, not_banned, unbanned, active, alive, ok

Si l'API retourne un autre format, adapte la section de détection de check.php.
