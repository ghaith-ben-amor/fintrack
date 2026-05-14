#!/bin/bash
# Script de vérification pré-déploiement Railway
# Usage: bash check-deployment.sh

echo "🔍 Vérification pré-déploiement Railway..."
echo ""

# Couleurs
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

ERRORS=0

# 1. Vérifier .gitignore
echo "1️⃣ Vérification .gitignore..."
if grep -q "\.env\.local" .gitignore && grep -q "\.env\.production\.local" .gitignore; then
    echo -e "${GREEN}✓ .env files correctement ignorés${NC}"
else
    echo -e "${RED}✗ .env files ne sont PAS ignorés (DANGER!)${NC}"
    ERRORS=$((ERRORS+1))
fi

# 2. Vérifier composer.json
echo "2️⃣ Vérification composer.json..."
if [ -f composer.json ]; then
    echo -e "${GREEN}✓ composer.json trouvé${NC}"
else
    echo -e "${RED}✗ composer.json manquant${NC}"
    ERRORS=$((ERRORS+1))
fi

# 3. Vérifier Procfile
echo "3️⃣ Vérification Procfile..."
if [ -f Procfile ]; then
    echo -e "${GREEN}✓ Procfile trouvé${NC}"
    echo "   Contenu:"
    cat Procfile | sed 's/^/   /'
else
    echo -e "${RED}✗ Procfile manquant${NC}"
    ERRORS=$((ERRORS+1))
fi

# 4. Vérifier bin/console
echo "4️⃣ Vérification bin/console..."
if [ -f bin/console ]; then
    echo -e "${GREEN}✓ bin/console trouvé${NC}"
else
    echo -e "${RED}✗ bin/console manquant${NC}"
    ERRORS=$((ERRORS+1))
fi

# 5. Vérifier public/ existe
echo "5️⃣ Vérification public/..."
if [ -d public ]; then
    echo -e "${GREEN}✓ public/ trouvé${NC}"
else
    echo -e "${RED}✗ public/ manquant${NC}"
    ERRORS=$((ERRORS+1))
fi

# 6. Vérifier migrations
echo "6️⃣ Vérification migrations..."
if [ -d migrations ]; then
    COUNT=$(ls migrations/*.php 2>/dev/null | wc -l)
    echo -e "${GREEN}✓ $COUNT migrations trouvées${NC}"
else
    echo -e "${YELLOW}⚠ Dossier migrations manquant${NC}"
fi

# 7. Vérifier .env.production
echo "7️⃣ Vérification .env.production..."
if [ -f .env.production ]; then
    echo -e "${GREEN}✓ .env.production trouvé${NC}"
    if grep -q "DATABASE_URL" .env.production; then
        echo -e "${GREEN}  ✓ DATABASE_URL configuré${NC}"
    else
        echo -e "${RED}  ✗ DATABASE_URL manquant${NC}"
        ERRORS=$((ERRORS+1))
    fi
    if grep -q "APP_ENV=prod" .env.production; then
        echo -e "${GREEN}  ✓ APP_ENV=prod${NC}"
    else
        echo -e "${RED}  ✗ APP_ENV pas défini en prod${NC}"
        ERRORS=$((ERRORS+1))
    fi
else
    echo -e "${YELLOW}⚠ .env.production manquant (créez-le avant Railway)${NC}"
fi

# 8. Vérifier dependances cruciales
echo "8️⃣ Vérification dépendances Symfony..."
REQUIRED=("symfony/framework-bundle" "doctrine/orm" "symfony/http-kernel")
MISSING=0
for pkg in "${REQUIRED[@]}"; do
    if grep -q "\"$pkg\"" composer.json; then
        echo -e "${GREEN}✓ $pkg${NC}"
    else
        echo -e "${YELLOW}⚠ $pkg manquant${NC}"
        MISSING=$((MISSING+1))
    fi
done

# 9. Vérifier variables sensibles dans Git
echo "9️⃣ Vérification variables sensibles..."
if git log --all -p | grep -q "BREVO_API_KEY\|TWILIO_AUTH_TOKEN\|APP_SECRET"; then
    echo -e "${RED}✗ Variables sensibles trouvées dans Git!${NC}"
    echo "   Commande: git filter-branch --force --tree-filter 'rm -rf .env.local' -- --all"
    ERRORS=$((ERRORS+1))
else
    echo -e "${GREEN}✓ Pas de variables sensibles dans Git${NC}"
fi

# 10. Vérifier configuration HTTPS
echo "🔟 Vérification HTTPS..."
echo -e "${GREEN}ℹ Railway fournit HTTPS gratuitement${NC}"
echo "   Domaine par défaut: xxx.railway.app (HTTPS automatique)"

echo ""
echo "────────────────────────────────────"

if [ $ERRORS -eq 0 ]; then
    echo -e "${GREEN}✅ Prêt pour Railway!${NC}"
    echo ""
    echo "📋 Prochaines étapes:"
    echo "  1. Créez un compte sur railway.app"
    echo "  2. Connectez votre GitHub"
    echo "  3. Sélectionnez ce repository"
    echo "  4. Railway détectera Symfony automatiquement"
    echo "  5. Ajoutez les variables d'environnement"
    echo "  6. Déployez!"
else
    echo -e "${RED}❌ $ERRORS erreur(s) détectée(s)${NC}"
    echo "   Corrigez-les avant de déployer sur Railway"
fi

exit $ERRORS
