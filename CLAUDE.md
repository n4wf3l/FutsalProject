# CLAUDE.md

Notes durables pour les futures sessions Claude Code sur ce projet.

## Règles de style (impératif absolu pour toute IA travaillant sur ce repo)

### Zéro tiret long

Ne **jamais jamais** utiliser dans du texte produit par IA :

* le tiret cadratin `—` (em dash, U+2014)
* le tiret demi-cadratin `–` (en dash, U+2013)
* le tiret d'union `-` employé comme séparateur de phrase ou d'incise

Interdit dans : réponses en chat, messages de commit, descriptions de PR, commentaires de code, documentation markdown, contenu écrit dans les fichiers du repo, chaînes UI, JSON i18n, seeders, textes stockés en base via l'admin.

À la place, utiliser :

* la virgule
* le point
* les deux-points
* ou reformuler la phrase
* ou utiliser une liste à puces

Le tiret d'union `-` reste autorisé uniquement dans les noms composés propres, identifiants, slugs, noms de fichiers, noms de branches Git (exemples : `Dina Kenitra`, `dinakenitrafc.ma`, `feat/phase-2-auth-admin`, `applications-cv`).

### Zéro emoji

Ne **jamais jamais** utiliser d'emoji dans quoi que ce soit produit par IA :

* pas d'emoji dans les réponses de chat, sauf si l'utilisateur en met explicitement lui-même dans son message
* pas d'emoji dans les commits, les descriptions de PR, les comments de code, la doc markdown
* pas d'emoji dans les fichiers de traduction (`resources/js/i18n/locales/**`)
* pas d'emoji dans les chaînes UI, les seeders, ou tout contenu qui pourrait finir affiché sur le site
* pas d'emoji comme puce de liste ou marqueur visuel (interdits : `⚡ 🎯 🛡️ 🔥 ✔ 🤝 🏆 💙 📢 📌 🚀 💡 ✅ ❌ ⚠️` etc.)

À la place, utiliser :

* des puces markdown standard `*` ou `-` en début de ligne
* du texte descriptif
* si un pictogramme visuel est vraiment nécessaire dans l'UI, utiliser un composant icône `lucide-react` déjà présent dans le projet (par exemple `<Trophy />`, `<Shield />`, `<Zap />`), jamais un emoji unicode

Raison : les emojis se rendent différemment selon les OS et les fonts, ils cassent la cohérence visuelle du design system, et l'utilisateur les considère peu professionnels sur ce site de club.

## Règles de design (impératif pour l'esthétique du site)

### Éviter les patterns visuels qui trahissent une IA

L'utilisateur repère et rejette immédiatement les designs qui sentent le landing SaaS généré par IA. Les motifs suivants sont à éviter ou à utiliser avec extrême parcimonie :

* **gros blobs de dégradé diffus en background** (`bg-crimson/20 blur-[120px]`, `bg-champagne/10 blur-[100px]`, etc.), surtout centrés ou spectaculaires. C'est le tell numéro un des landings Dribbble et Framer templates
* **glassmorphism agressif** (`glass-strong`, `backdrop-blur-*` sur des grandes surfaces avec bordure blanche translucide), typique des dashboards SaaS
* **dégradés violet/rose/turquoise** (Stripe, Linear, Vercel), même en accent
* **pills flottantes avec live-dot rouge** pour du contenu qui n'est pas live (par exemple sur une card article statique). Le live-dot doit rester réservé au vrai temps réel (match en cours, notification urgente)
* **coins ultra arrondis** (`rounded-3xl` sur tout, `rounded-full` sur tout) qui donnent le look "Notion / Framer starter"
* **shadow neon** (`shadow-*/50 shadow-crimson`) sur les éléments courants
* **animations flottantes** en `translateY` infini sur des éléments non-décoratifs
* **fond noise / grain SVG en overlay** utilisé pour "vieillir" artificiellement une surface

### Ce qu'on fait à la place

Pour rester sur l'esthétique d'un vrai club sportif ambitieux, référentiel Real Madrid, PSG, Bayern, Inter :

* **photos réelles ou vidéos** en fond quand c'est possible (ici on n'en a pas, on compose autrement)
* **typographie éditoriale forte** (display bold + editorial italique) comme colonne vertébrale de l'identité, déjà en place
* **couleurs solides** (crimson plein, champagne plein, obsidian plein) plutôt que des voiles transparents
* **bordures nettes** en `border border-border` ou `border-champagne/20`, sans blur derrière
* **cartes rectangulaires ou légèrement arrondies** (`rounded-2xl` max, `rounded-lg` par défaut)
* **contraste franc** entre les éléments plutôt qu'un dégradé qui adoucit tout
* **texture réservée aux moments forts** (grain sur un hero éditorial une fois, pas partout)

### Comment auto-tester avant de valider un design

Se poser trois questions :

1. Si je retire ce design du contexte du club, est-ce qu'il pourrait servir tel quel pour vendre un SaaS de gestion de temps ? Si oui, c'est mauvais.
2. Est-ce que le focus visuel est sur le contenu (nom du club, actu, joueur, match) ou sur des effets décoratifs ?
3. Un supporter lambda perçoit-il tout de suite le sérieux et l'ambition du club, ou l'impression d'un site de startup ?

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

### Comptes seedés jamais exposés en production

La page `resources/js/Pages/Auth/Login.tsx` affiche une liste de comptes seedés (email + mot de passe en clair) pour faciliter la connexion en développement. Ce bloc n'apparaît que si `app.env !== 'production'`, valeur partagée par `HandleInertiaRequests` sous la clé `app.env`.

Règles à ne jamais enfreindre :

* le bloc `DEV_ACCOUNTS` dans Login.tsx doit toujours être gardé derrière `isDev`
* `HandleInertiaRequests` doit toujours exposer `app.env` (via `app()->environment()`)
* toute nouvelle page qui exposerait des données sensibles pour le confort du dev doit suivre le même pattern (`props.app.env !== 'production'`)
* ne jamais utiliser une simple `import.meta.env.DEV` ou `import.meta.env.PROD` de Vite, car ces valeurs sont figées au build et deviennent faussement `false` si le build est fait sur un poste local puis déployé

## Chantiers planifiés (à ne pas oublier)

### i18n FR / EN / AR, issue [#1](https://github.com/n4wf3l/FutsalProject/issues/1)

Le multilinguisme existait côté Blade (`__()` + `resources/lang/{en,ar}/messages.php` + cookie `locale` + switcher JS dans la vieille navbar) mais n'a pas été porté lors de la migration Inertia + React. Toutes les strings du frontend React sont hardcodées en FR, et il n'y a plus de sélecteur de langue.

**Ne pas reconstruire par petits bouts au fil des PRs.** C'est un chantier dédié qui touche presque toutes les `.tsx`. Attendre que l'utilisateur demande explicitement de le faire, puis suivre le scope détaillé dans l'issue #1 (install `i18next` + `react-i18next`, extraction complète des strings, `LanguageSwitcher`, persistance via Inertia shared props, support RTL pour l'arabe).
