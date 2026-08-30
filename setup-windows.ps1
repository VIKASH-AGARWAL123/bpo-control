$ErrorActionPreference = "Stop"
Write-Host "Creating Laravel 13 backend..."
if (-not (Get-Command composer -ErrorAction SilentlyContinue)) { throw "Composer is required. Install Composer and reopen PowerShell." }
if (-not (Get-Command npm -ErrorAction SilentlyContinue)) { throw "Node.js/npm is required." }
$root = Split-Path -Parent $MyInvocation.MyCommand.Path
$temp = Join-Path $root "_laravel_build"
if (Test-Path $temp) { Remove-Item $temp -Recurse -Force }
composer create-project laravel/laravel $temp "^13.0"
composer require --working-dir=$temp tymon/jwt-auth:^2.3
$custom = Join-Path $root "backend"
Copy-Item (Join-Path $custom "app") (Join-Path $temp "app") -Recurse -Force
Copy-Item (Join-Path $custom "bootstrap/app.php") (Join-Path $temp "bootstrap/app.php") -Force
Copy-Item (Join-Path $custom "config/auth.php") (Join-Path $temp "config/auth.php") -Force
Copy-Item (Join-Path $custom "config/cors.php") (Join-Path $temp "config/cors.php") -Force
Copy-Item (Join-Path $custom "config/jwt.php") (Join-Path $temp "config/jwt.php") -Force
Copy-Item (Join-Path $custom "database") (Join-Path $temp "database") -Recurse -Force
Copy-Item (Join-Path $custom "routes") (Join-Path $temp "routes") -Recurse -Force
Copy-Item (Join-Path $custom ".env.example") (Join-Path $temp ".env.example") -Force
Set-Location $temp
php artisan key:generate
php artisan jwt:secret
Write-Host "Laravel backend created at $temp"
Write-Host "Configure .env PostgreSQL values, then run: php artisan migrate --seed; php artisan serve"
