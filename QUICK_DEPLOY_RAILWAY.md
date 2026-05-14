# 🎯 Checklist Déploiement FinTrack - Railway.app

## ✅ Avant de Commencer

- [ ] Compte GitHub avec le code Symfony
- [ ] Compte Railway.app gratuit créé
- [ ] Git initialisé et push sur GitHub
- [ ] `.env.local` **JAMAIS** commité

---

## 🚀 Étapes Rapides (5 minutes)

### 1. Préparer votre Repository GitHub

```bash
# S'assurer que tout est committé
git status

# Push vers GitHub
git push origin main
```

### 2. Se Connecter à Railway

1. Accédez à [railway.app](https://railway.app)
2. Cliquez **Sign Up** (créez un compte gratuit)
3. Autorisez GitHub

### 3. Créer un Nouveau Projet

1. Dashboard Railway → **New Project**
2. **Deploy from GitHub**
3. Sélectionnez votre repo `integration_api`
4. Railway détecte automatiquement **Symfony** ✓

### 4. Ajouter la Base de Données

1. Railway → **Add Service** → **PostgreSQL**
2. Railway crée automatiquement `DATABASE_URL` ✓

### 5. Configurer les Variables d'Environnement

Railway → **Variables** → Cliquez **Edit**

**Copiez/collez ces lignes** (adaptez vos clés):

```
APP_ENV=prod
APP_SECRET=your_random_secret_here_min_32_chars
SYMFONY_BASE_URL=https://your-project-name.railway.app

# Emails
MAILER_DSN=smtp://your-email@gmail.com:YOUR_APP_PASSWORD@smtp.gmail.com:587?encryption=tls
MAIL_FROM=noreply@fintrack.app
BREVO_API_KEY=YOUR_BREVO_API_KEY
BREVO_SENDER_EMAIL=noreply@fintrack.app
BREVO_SENDER_NAME=FinTrack

# SMS
BREVO_SMS_API_KEY=YOUR_BREVO_SMS_API_KEY
BREVO_SMS_SENDER=FinTrack
TWILIO_ACCOUNT_SID=YOUR_TWILIO_SID
TWILIO_AUTH_TOKEN=YOUR_TWILIO_TOKEN
TWILIO_FROM_NUMBER=+15739430306

# APIs
GEMINI_API_KEY=YOUR_GEMINI_KEY
GROQ_API_KEY=YOUR_GROQ_KEY

# OAuth
OAUTH_GOOGLE_CLIENT_ID=YOUR_GOOGLE_CLIENT_ID
OAUTH_GOOGLE_CLIENT_SECRET=YOUR_GOOGLE_CLIENT_SECRET

# Admin
ADMIN_CODE=dev_secret_admin_code_fintrack_2026
```

### 6. Déclencher le Déploiement

1. Railway detecte automatiquement les changements
2. Ou cliquez **Deploy** manuellement
3. Regardez les logs en temps réel ✓

### 7. Exécuter les Migrations

Une fois l'app lancée :

1. Railway → Shell
2. Exécutez:
```bash
php bin/console doctrine:migrations:migrate --no-interaction
```

### 8. Vérifier que ça Marche

1. Allez à `https://your-project-name.railway.app`
2. Testez l'enregistrement
3. Testez la réception du code email

---

## 🔐 Sécurité - À FAIRE

- [ ] Générez un `APP_SECRET` random de 32+ caractères
  - Linux/Mac: `openssl rand -base64 32`
  - Ou créez-en un sur [https://generate-random.org](https://generate-random.org)

- [ ] Changez le `ADMIN_CODE` (optionnel mais recommandé)

- [ ] Changez `BREVO_SENDER_EMAIL` en un vrai email

- [ ] Pour Gmail, générez une [App Password](https://myaccount.google.com/apppasswords)
  - Ne mettez PAS votre mot de passe Gmail en clair!

---

## 📊 Coûts Gratuits

| Service | Gratuit |
|---------|---------|
| **Railway** | $5/mois crédit |
| **PostgreSQL** | Inclus (500MB) |
| **Déploiement** | Illimité |
| **Logs** | Inclus |
| **Domaine** | `*.railway.app` gratuit |
| **SSL/HTTPS** | Gratuit |

**Total**: Complètement GRATUIT avec crédit Railway

---

## 🆘 Troubleshooting Rapide

### App n'a pas démarré
```bash
# Vérifiez les logs Railway
# Erreurs courantes:
# - DATABASE_URL manquant
# - APP_SECRET manquant
# - Migrations non exécutées
```

### Email ne s'envoie pas
```bash
# Vérifiez MAILER_DSN et BREVO_API_KEY
# Testez avec Brevo:
# https://app.brevo.com/transactional/email
```

### Base de données vide
```bash
# Réexécutez les migrations
php bin/console doctrine:migrations:migrate
```

---

## ✨ À Faire Après le Déploiement

- [ ] Ajouter un domaine personnalisé (optionnel)
- [ ] Mettre en place le monitoring (Railway Dashboard)
- [ ] Configurer les alertes
- [ ] Activer les backups PostgreSQL

---

## 📚 Ressources Utiles

- **Railway Docs**: [railway.app/docs](https://railway.app/docs)
- **Symfony Production**: [symfony.com/doc](https://symfony.com/doc/current)
- **Troubleshooting**: Voir `RAILWAY_DEPLOYMENT.md`

---

## ✅ Récapitulatif

```
Nouveau compte Railway? → 2 minutes
Connecter GitHub? → 1 minute
Ajouter variables d'env? → 3 minutes
Déployer? → Automatique
Migrations? → 1 minute
EN LIGNE? → ~7 minutes! 🎉
```

**Bonne chance ! Votre app sera en ligne rapidement! 🚀**

---

*Dernière mise à jour: May 14, 2026*
