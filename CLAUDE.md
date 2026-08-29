# CLAUDE.md

Notes durables pour les futures sessions Claude Code sur ce projet.

## Règles de style (impératif)

### Jamais de tirets

Ne **jamais** utiliser de tirets dans le texte : ni tiret cadratin `—`, ni tiret demi-cadratin `–`, ni tiret d'union `-` employé comme séparateur de phrase.

S'applique aux réponses en chat, aux commits, aux messages de PR, aux commentaires de code, à la documentation, et aux fichiers markdown de ce repo (y compris ce CLAUDE.md).

À la place :
* utiliser des virgules, des points, des deux-points
* ou reformuler la phrase
* ou utiliser des listes à puces

Le tiret d'union reste autorisé dans les noms composés propres (ex : `Dina Kenitra`, `dinakenitrafc.ma`, noms de branches Git comme `feat/phase-2-auth-admin`).

## Règles de sécurité (impératif)

### Liens de gestion candidature ne doivent jamais être indexés

Les URLs de type `/candidature/{token}` donnent accès personnel du candidat à ses données. Le token est un secret de 48 caractères aléatoires qui donne accès à tout le dossier de candidature (identité, coordonnées, CV, statut).

Règles à ne jamais enfreindre :

* la page `CandidatureManage.tsx` doit toujours passer `noindex` au composant `<SEO>`
* la page `CandidatureDeletionRequest.tsx` (`/candidature/supprimer`) doit toujours passer `noindex` au composant `<SEO>`
* le `SitemapController` ne doit jamais lister ces routes
* le token doit rester aléatoire et non prévisible, au minimum `Str::random(48)`
* la route Laravel doit contraindre le paramètre par regex `[A-Za-z0-9]{48}` pour bloquer toute tentative de fuzzing
* la réponse de `POST /candidature/supprimer` doit toujours renvoyer le même message générique, quelle que soit l'existence de l'email en base, afin d'empêcher l'énumération d'adresses

Un utilisateur ne peut accéder qu'à SES propres données via son lien personnel reçu par email lors de la soumission du formulaire de candidature.

## Chantiers planifiés (à ne pas oublier)

### i18n FR / EN / AR, issue [#1](https://github.com/n4wf3l/FutsalProject/issues/1)

Le multilinguisme existait côté Blade (`__()` + `resources/lang/{en,ar}/messages.php` + cookie `locale` + switcher JS dans la vieille navbar) mais n'a pas été porté lors de la migration Inertia + React. Toutes les strings du frontend React sont hardcodées en FR, et il n'y a plus de sélecteur de langue.

**Ne pas reconstruire par petits bouts au fil des PRs.** C'est un chantier dédié qui touche presque toutes les `.tsx`. Attendre que l'utilisateur demande explicitement de le faire, puis suivre le scope détaillé dans l'issue #1 (install `i18next` + `react-i18next`, extraction complète des strings, `LanguageSwitcher`, persistance via Inertia shared props, support RTL pour l'arabe).
