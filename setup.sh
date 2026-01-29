#!/bin/bash

echo "🎮 Ingress Intel - Instal·lació inicial"
echo "========================================"
echo ""

# Construir les imatges Docker
echo "📦 Construint imatges Docker..."
docker-compose build

# Iniciar els contenidors
echo "🚀 Iniciant contenidors..."
docker-compose up -d postgres redis

# Esperar que PostgreSQL estigui llest
echo "⏳ Esperant que PostgreSQL estigui llest..."
sleep 5

# Crear el projecte Laravel
echo "📝 Creant projecte Laravel..."
docker-compose run --rm app composer create-project laravel/laravel . --prefer-dist

# Copiar .env.example a .env si no existeix
echo "⚙️  Configurant variables d'entorn..."
if [ ! -f laravel/.env ]; then
    cp laravel/.env.example laravel/.env
fi

# Actualitzar configuració de base de dades
echo "🔧 Configurant connexió a PostgreSQL..."
docker-compose run --rm app sed -i 's/DB_CONNECTION=sqlite/DB_CONNECTION=pgsql/' .env
docker-compose run --rm app sed -i 's/DB_HOST=127.0.0.1/DB_HOST=postgres/' .env
docker-compose run --rm app sed -i 's/DB_DATABASE=laravel/DB_DATABASE=ingress_intel/' .env
docker-compose run --rm app sed -i 's/DB_USERNAME=root/DB_USERNAME=ingress_user/' .env
docker-compose run --rm app sed -i 's/DB_PASSWORD=/DB_PASSWORD=ingress_pass_2024/' .env

# Generar clau de l'aplicació
echo "🔑 Generant clau de l'aplicació..."
docker-compose run --rm app php artisan key:generate

# Instal·lar Filament
echo "💎 Instal·lant Filament Admin Panel..."
docker-compose run --rm app composer require filament/filament:"^3.2" -W

# Executar migracions
echo "🗄️  Executant migracions de base de dades..."
docker-compose run --rm app php artisan migrate

echo ""
echo "✅ Instal·lació completada!"
echo ""
echo "Per iniciar l'aplicació:"
echo "  docker-compose up -d"
echo ""
echo "L'aplicació estarà disponible a: http://localhost:8080"
echo ""
