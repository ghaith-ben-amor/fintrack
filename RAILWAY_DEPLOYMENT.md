# 🚀 Guide de Déploiement FinTrack sur Railway.app

## 📋 Prérequis
- Compte GitHub avec votre code Symfony
- Compte Railway.app (gratuit)
- Domaine personnalisé (optionnel)

## 🔗 Étape 1 : Connecter votre GitHub à Railway

1. Allez sur [railway.app](https://railway.app)
2. Cliquez sur **"New Project"**
3. Sélectionnez **"Deploy from GitHub"**
4. Autorisez Railway à accéder à votre compte GitHub
5. Sélectionnez votre repository

## 📦 Étape 2 : Configuration de la Base de Données

Railway crée automatiquement PostgreSQL. Configuration:

```bash
# Variable d'environnement générée par Railway:
DATABASE_URL = postgres://...
```

La base de données est créée automatiquement. Vous devez exécuter les migrations :

```bash
# Utilisez le terminal Railway ou déclenchez-le manuellement
php bin/console doctrine:migrations:migrate --no-interaction
```

## 🔑 Étape 3 : Variables d'Environnement

Dans Railway, allez dans **Variables** et définissez:

| Variable | Valeur |
|----------|--------|
| `APP_ENV` | `prod` |
| `APP_SECRET` | Générez une clé aléatoire de 32+ caractères |
| `SYMFONY_BASE_URL` | `https://votre-app.railway.app` |
| `BREVO_API_KEY` | Votre clé Brevo |
| `BREVO_SMS_API_KEY` | Votre clé Brevo SMS |
| `TWILIO_ACCOUNT_SID` | Votre SID Twilio |
| `TWILIO_AUTH_TOKEN` | Votre token Twilio |
| `TWILIO_FROM_NUMBER` | Votre numéro Twilio |
| `GEMINI_API_KEY` | Votre clé Gemini |
| `GROQ_API_KEY` | Votre clé Groq |
| `OAUTH_GOOGLE_CLIENT_ID` | Votre Google OAuth ID |
| (etc.) | (toutes les autres clés API) |

> **⚠️ IMPORTANT** : Ne committez JAMAIS les clés secrètes dans Git. Utilisez uniquement les variables Railway.

## 🚢 Étape 4 : Configuration du Déploiement

Railway détecte automatiquement Symfony. Vérifiez :

1. **Build Command** : `composer install && npm install && npm run build`
2. **Start Command** : `php bin/console server:run 0.0.0.0:8000` 

> Si personnalisé, éditer dans Railway → Settings → Deploy

## 💾 Étape 5 : Migrations et Seed Data

Après le premier déploiement :

```bash
# Via Railway CLI ou Shell
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load --no-interaction
```

## 📧 Étape 6 : Configuration Email

Vos variables de déploiement doivent inclure :

```
MAILER_DSN=smtp://ghaithbenaomr@gmail.com:APP_PASSWORD@smtp.gmail.com:587?encryption=tls
MAIL_FROM=noreply@fintrack.app
BREVO_SENDER_EMAIL=noreply@fintrack.app
```

> **Note** : Si vous utilisez Gmail, générez une [App Password](https://myaccount.google.com/apppasswords) au lieu du mot de passe normal.

## 🎯 Étape 7 : Domaine Personnalisé

1. Dans Railway → Settings → Domains
2. Cliquez **"Add Custom Domain"**
3. Entrez votre domaine (ex: `fintrack.com`)
4. Configurez les DNS records chez votre registrar

## ✅ Vérifications Post-Déploiement

```bash
# Accès au shell Railway
# Exécutez:
php bin/console debug:router                    # Liste les routes
php bin/console cache:clear                     # Clear cache
php bin/console doctrine:migrations:status      # État des migrations
```

## 🔐 Sécurité en Production

- [ ] `APP_SECRET` généré (32+ caractères aléatoires)
- [ ] `APP_ENV=prod`
- [ ] CORS correctement configuré
- [ ] HTTPS activé (automatique sur Railway)
- [ ] Variables sensibles en Railway, pas en Git
- [ ] `.env.local` dans `.gitignore`
- [ ] `public/` set comme document root

## 📊 Monitoring

Railway fourni automatiquement:
- Logs en temps réel
- Statistiques CPU/RAM
- Alertes de déploiement

Accédez via Railway Dashboard → Logs

## 🆘 Troubleshooting

### App ne démarre pas
```bash
# Vérifiez les logs
railway logs

# Vérifiez les migrations
php bin/console doctrine:migrations:status
```

### Base de données vide
```bash
# Réexécutez les migrations
php bin/console doctrine:migrations:migrate
```

### Erreur 500
```bash
# Vérifiez APP_SECRET et DATABASE_URL
# Vérifiez les logs complets
```

## 📞 Support Railway

- Docs: [railway.app/docs](https://railway.app/docs)
- Discord: [Railway Community](https://discord.gg/railway)
- CLI: `railway help`

---

**Total gratuit/mois** : ~$5 de crédit Railway (suffisant pour un petit projet)

Bonne chance ! 🚀
