# Ingress Intel - Script d'Instal·lació per Windows
# Executa aquest script amb PowerShell

Write-Host "🎮 Ingress Intel - Instal·lació per Windows" -ForegroundColor Green
Write-Host "============================================" -ForegroundColor Green
Write-Host ""

# Verificar si Docker està instal·lat
$dockerInstalled = Get-Command docker -ErrorAction SilentlyContinue
if (-not $dockerInstalled) {
    Write-Host "❌ Docker no està instal·lat." -ForegroundColor Red
    Write-Host "Si us plau, instal·la Docker Desktop primer:" -ForegroundColor Yellow
    Write-Host "  winget install Docker.DockerDesktop" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "Després reinicia l'ordinador i torna a executar aquest script." -ForegroundColor Yellow
    exit 1
}

Write-Host "✅ Docker detectat" -ForegroundColor Green
Write-Host ""

# Verificar si Docker està executant-se
$dockerRunning = docker info 2>$null
if (-not $dockerRunning) {
    Write-Host "❌ Docker Desktop no està executant-se." -ForegroundColor Red
    Write-Host "Si us plau, obre Docker Desktop i espera que estigui 'running'" -ForegroundColor Yellow
    Write-Host "Després torna a executar aquest script." -ForegroundColor Yellow
    exit 1
}

Write-Host "✅ Docker està executant-se" -ForegroundColor Green
Write-Host ""

# Construir les imatges Docker
Write-Host "📦 Construint imatges Docker..." -ForegroundColor Cyan
docker-compose build --no-cache

# Iniciar PostgreSQL i Redis
Write-Host ""
Write-Host "🚀 Iniciant serveis de base de dades..." -ForegroundColor Cyan
docker-compose up -d postgres redis

# Esperar que PostgreSQL estigui llest
Write-Host "⏳ Esperant que PostgreSQL estigui llest..." -ForegroundColor Cyan
Start-Sleep -Seconds 10

# Verificar si ja existeix el directori laravel
if (Test-Path "laravel") {
    Write-Host "⚠️  El directori 'laravel' ja existeix. Eliminant..." -ForegroundColor Yellow
    Remove-Item -Recurse -Force laravel
}

# Crear el projecte Laravel
Write-Host ""
Write-Host "📝 Creant projecte Laravel nou..." -ForegroundColor Cyan
docker-compose run --rm app composer create-project laravel/laravel . --prefer-dist

# Configurar .env
Write-Host "⚙️  Configurant variables d'entorn..." -ForegroundColor Cyan
$envContent = @"
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
MAIL_FROM_NAME="`${APP_NAME}"
"@

Set-Content -Path "laravel\.env" -Value $envContent

# Generar clau de l'aplicació
Write-Host "🔑 Generant clau de l'aplicació..." -ForegroundColor Cyan
docker-compose run --rm app php artisan key:generate

# Instal·lar Filament
Write-Host ""
Write-Host "💎 Instal·lant Filament Admin Panel..." -ForegroundColor Cyan
docker-compose run --rm app composer require filament/filament:"^3.2" -W

# Copiar migracions
Write-Host "📋 Copiant migracions..." -ForegroundColor Cyan
Copy-Item -Path "database\migrations\*" -Destination "laravel\database\migrations\" -Force

# Copiar models
Write-Host "📋 Copiant models..." -ForegroundColor Cyan
if (-not (Test-Path "laravel\app\Models")) {
    New-Item -ItemType Directory -Path "laravel\app\Models" -Force | Out-Null
}
Copy-Item -Path "app\Models\*" -Destination "laravel\app\Models\" -Force

# Copiar seeders
Write-Host "📋 Copiant seeders..." -ForegroundColor Cyan
Copy-Item -Path "database\seeders\*" -Destination "laravel\database\seeders\" -Force

# Copiar recursos Filament
Write-Host "📋 Copiant recursos Filament..." -ForegroundColor Cyan
if (-not (Test-Path "laravel\app\Filament\Resources")) {
    New-Item -ItemType Directory -Path "laravel\app\Filament\Resources" -Force | Out-Null
}
Copy-Item -Path "app\Filament\Resources\*" -Destination "laravel\app\Filament\Resources\" -Recurse -Force

# Executar migracions
Write-Host ""
Write-Host "🗄️  Executant migracions de base de dades..." -ForegroundColor Cyan
docker-compose run --rm app php artisan migrate --force

# Executar seeders
Write-Host "🌱 Executant seeders..." -ForegroundColor Cyan
docker-compose run --rm app php artisan db:seed --force

# Crear usuari admin per Filament
Write-Host ""
Write-Host "👤 Creant usuari administrador..." -ForegroundColor Cyan
Write-Host ""
Write-Host "Introdueix les dades per l'usuari administrador:" -ForegroundColor Yellow
docker-compose run --rm app php artisan make:filament-user

# Iniciar tots els serveis
Write-Host ""
Write-Host "🚀 Iniciant tots els serveis..." -ForegroundColor Cyan
docker-compose up -d

Write-Host ""
Write-Host "✅ ✅ ✅ Instal·lació completada amb èxit! ✅ ✅ ✅" -ForegroundColor Green
Write-Host ""
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Cyan
Write-Host "📱 Accedeix a l'aplicació:" -ForegroundColor Yellow
Write-Host "   🌐 Aplicació: http://localhost:8080" -ForegroundColor White
Write-Host "   🔐 Admin Panel: http://localhost:8080/admin" -ForegroundColor White
Write-Host ""
Write-Host "🛠️  Comandes útils:" -ForegroundColor Yellow
Write-Host "   Aturar: docker-compose down" -ForegroundColor White
Write-Host "   Iniciar: docker-compose up -d" -ForegroundColor White
Write-Host "   Logs: docker-compose logs -f" -ForegroundColor White
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" -ForegroundColor Cyan
Write-Host ""
