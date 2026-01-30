# Ingress Intel - Installation Script for Windows
# Execute this script with PowerShell

Write-Host "Ingress Intel - Windows Installation" -ForegroundColor Green
Write-Host "=====================================" -ForegroundColor Green
Write-Host ""

# Check if Docker is installed
$dockerInstalled = Get-Command docker -ErrorAction SilentlyContinue
if (-not $dockerInstalled) {
    Write-Host "ERROR: Docker is not installed." -ForegroundColor Red
    Write-Host "Please install Docker Desktop first:" -ForegroundColor Yellow
    Write-Host "  winget install Docker.DockerDesktop" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "Then restart your computer and run this script again." -ForegroundColor Yellow
    exit 1
}

Write-Host "[OK] Docker detected" -ForegroundColor Green
Write-Host ""

# Check if Docker is running
$dockerRunning = docker info 2>$null
if (-not $dockerRunning) {
    Write-Host "ERROR: Docker Desktop is not running." -ForegroundColor Red
    Write-Host "Please open Docker Desktop and wait until it says 'running'" -ForegroundColor Yellow
    Write-Host "Then run this script again." -ForegroundColor Yellow
    exit 1
}

Write-Host "[OK] Docker is running" -ForegroundColor Green
Write-Host ""

# Build Docker images
Write-Host "Step 1/10: Building Docker images..." -ForegroundColor Cyan
docker-compose build --no-cache
if ($LASTEXITCODE -ne 0) {
    Write-Host "ERROR: Failed to build Docker images" -ForegroundColor Red
    exit 1
}

# Start PostgreSQL and Redis
Write-Host ""
Write-Host "Step 2/10: Starting database services..." -ForegroundColor Cyan
docker-compose up -d postgres redis
if ($LASTEXITCODE -ne 0) {
    Write-Host "ERROR: Failed to start database services" -ForegroundColor Red
    exit 1
}

# Wait for PostgreSQL to be ready
Write-Host ""
Write-Host "Step 3/10: Waiting for PostgreSQL to be ready..." -ForegroundColor Cyan
Start-Sleep -Seconds 10

# Check if laravel directory exists
if (Test-Path "laravel") {
    Write-Host ""
    Write-Host "WARNING: 'laravel' directory already exists. Removing..." -ForegroundColor Yellow
    Remove-Item -Recurse -Force laravel
}

# Create Laravel project
Write-Host ""
Write-Host "Step 4/10: Creating Laravel project..." -ForegroundColor Cyan
Write-Host "(This may take several minutes)" -ForegroundColor Yellow
docker-compose run --rm app composer create-project laravel/laravel . --prefer-dist
if ($LASTEXITCODE -ne 0) {
    Write-Host "ERROR: Failed to create Laravel project" -ForegroundColor Red
    exit 1
}

# Configure .env file
Write-Host ""
Write-Host "Step 5/10: Configuring environment variables..." -ForegroundColor Cyan
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

# Generate application key
Write-Host ""
Write-Host "Step 6/10: Generating application key..." -ForegroundColor Cyan
docker-compose run --rm app php artisan key:generate

# Install Filament
Write-Host ""
Write-Host "Step 7/10: Installing Filament Admin Panel..." -ForegroundColor Cyan
Write-Host "(This may take several minutes)" -ForegroundColor Yellow
docker-compose run --rm app composer require filament/filament:"^3.2" -W
if ($LASTEXITCODE -ne 0) {
    Write-Host "ERROR: Failed to install Filament" -ForegroundColor Red
    exit 1
}

# Install Filament Panel
Write-Host "Installing Filament Panel..." -ForegroundColor Cyan
docker-compose run --rm app php artisan filament:install --panels

# Copy migrations
Write-Host ""
Write-Host "Step 8/10: Copying database migrations..." -ForegroundColor Cyan
Copy-Item -Path "database\migrations\*" -Destination "laravel\database\migrations\" -Force

# Copy models
Write-Host "Copying models..." -ForegroundColor Cyan
Copy-Item -Path "app\Models\*" -Destination "laravel\app\Models\" -Force

# Verify models were copied
$modelCount = (Get-ChildItem "laravel\app\Models\*.php").Count
Write-Host "  -> Copied $modelCount model files" -ForegroundColor Gray

# Copy seeders
Write-Host "Copying seeders..." -ForegroundColor Cyan
Copy-Item -Path "database\seeders\*" -Destination "laravel\database\seeders\" -Force

# Verify seeders were copied
$seederCount = (Get-ChildItem "laravel\database\seeders\*.php").Count
Write-Host "  -> Copied $seederCount seeder files" -ForegroundColor Gray

# Copy Filament resources
Write-Host "Copying Filament resources..." -ForegroundColor Cyan
if (-not (Test-Path "laravel\app\Filament\Resources")) {
    New-Item -ItemType Directory -Path "laravel\app\Filament\Resources" -Force | Out-Null
}
Copy-Item -Path "app\Filament\Resources\*" -Destination "laravel\app\Filament\Resources\" -Recurse -Force

# Run migrations
Write-Host ""
Write-Host "Step 9/10: Running database migrations..." -ForegroundColor Cyan
docker-compose run --rm app php artisan migrate --force

# Run seeders
Write-Host "Running database seeders..." -ForegroundColor Cyan
docker-compose run --rm app php artisan db:seed --force

# Create admin user
Write-Host ""
Write-Host "Step 10/10: Creating admin user..." -ForegroundColor Cyan
Write-Host ""
Write-Host "Please enter admin user details:" -ForegroundColor Yellow
docker-compose run --rm app php artisan make:filament-user

# Start all services
Write-Host ""
Write-Host "Starting all services..." -ForegroundColor Cyan
docker-compose up -d

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "Installation completed successfully!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "Access your application:" -ForegroundColor Yellow
Write-Host "  Web App: http://localhost:8080" -ForegroundColor White
Write-Host "  Admin Panel: http://localhost:8080/admin" -ForegroundColor White
Write-Host ""
Write-Host "Useful commands:" -ForegroundColor Yellow
Write-Host "  Stop: docker-compose down" -ForegroundColor White
Write-Host "  Start: docker-compose up -d" -ForegroundColor White
Write-Host "  Logs: docker-compose logs -f" -ForegroundColor White
Write-Host ""
