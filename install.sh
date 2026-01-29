#!/bin/bash

set -e

echo "🎮 Ingress Intel - Instal·lació Completa"
echo "=========================================="
echo ""

# Colors per la terminal
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Verificar si Docker està instal·lat
if ! command -v docker &> /dev/null; then
    echo -e "${RED}❌ Docker no està instal·lat. Si us plau, instal·la Docker primer.${NC}"
    exit 1
fi

if ! command -v docker-compose &> /dev/null; then
    echo -e "${RED}❌ Docker Compose no està instal·lat. Si us plau, instal·la Docker Compose primer.${NC}"
    exit 1
fi

echo -e "${GREEN}✅ Docker i Docker Compose detectats${NC}"
echo ""

# Construir les imatges Docker
echo "📦 Construint imatges Docker..."
docker-compose build --no-cache

# Iniciar PostgreSQL i Redis
echo ""
echo "🚀 Iniciant serveis de base de dades..."
docker-compose up -d postgres redis

# Esperar que PostgreSQL estigui llest
echo "⏳ Esperant que PostgreSQL estigui llest..."
sleep 10

# Verificar si ja existeix el directori laravel
if [ -d "laravel" ]; then
    echo -e "${YELLOW}⚠️  El directori 'laravel' ja existeix. Eliminant...${NC}"
    rm -rf laravel
fi

# Crear el projecte Laravel
echo ""
echo "📝 Creant projecte Laravel nou..."
docker-compose run --rm app composer create-project laravel/laravel . --prefer-dist

# Configurar permisos
echo "🔧 Configurant permisos..."
sudo chown -R $USER:$USER laravel
chmod -R 755 laravel/storage laravel/bootstrap/cache

# Configurar .env
echo "⚙️  Configurant variables d'entorn..."
cat > laravel/.env << EOF
APP_NAME="Ingress Intel"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8080

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=ingress_intel
DB_USERNAME=ingress_user
DB_PASSWORD=ingress_pass_2024

BROADCAST_DRIVER=log
CACHE_DRIVER=redis
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=redis
SESSION_LIFETIME=120

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="\${APP_NAME}"
EOF

# Generar clau de l'aplicació
echo "🔑 Generant clau de l'aplicació..."
docker-compose run --rm app php artisan key:generate

# Instal·lar Filament
echo ""
echo "💎 Instal·lant Filament Admin Panel..."
docker-compose run --rm app composer require filament/filament:"^3.2" -W

# Copiar migracions
echo "📋 Copiant migracions..."
cp -r database/migrations/* laravel/database/migrations/

# Copiar models
echo "📋 Copiant models..."
cp -r app/Models/* laravel/app/Models/

# Copiar seeders
echo "📋 Copiant seeders..."
cp -r database/seeders/* laravel/database/seeders/

# Copiar recursos Filament
echo "📋 Copiant recursos Filament..."
mkdir -p laravel/app/Filament/Resources
cp -r app/Filament/Resources/* laravel/app/Filament/Resources/

# Executar migracions
echo ""
echo "🗄️  Executant migracions de base de dades..."
docker-compose run --rm app php artisan migrate --force

# Executar seeders
echo "🌱 Executant seeders..."
docker-compose run --rm app php artisan db:seed --force

# Crear usuari admin per Filament
echo ""
echo "👤 Creant usuari administrador..."
echo ""
echo -e "${YELLOW}Introdueix les dades per l'usuari administrador:${NC}"
docker-compose run --rm app php artisan make:filament-user

# Iniciar tots els serveis
echo ""
echo "🚀 Iniciant tots els serveis..."
docker-compose up -d

echo ""
echo -e "${GREEN}✅ ✅ ✅ Instal·lació completada amb èxit! ✅ ✅ ✅${NC}"
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "📱 Accedeix a l'aplicació:"
echo "   🌐 Aplicació: http://localhost:8080"
echo "   🔐 Admin Panel: http://localhost:8080/admin"
echo ""
echo "🛠️  Comandes útils:"
echo "   Aturar: docker-compose down"
echo "   Iniciar: docker-compose up -d"
echo "   Logs: docker-compose logs -f"
echo "   Shell Laravel: docker-compose exec app sh"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
