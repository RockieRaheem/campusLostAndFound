# Prepare Final Submission Zipping Script
Write-Host "Cleaning up the project for final submission..." -ForegroundColor Green

# 1. Clear Caches
Write-Host "Clearing Laravel application caches..." -ForegroundColor Cyan
php artisan optimize:clear
php artisan view:clear
php artisan route:clear
php artisan config:clear

# 2. Clear Logs
Write-Host "Removing log files..." -ForegroundColor Cyan
if (Test-Path "storage/logs/*.log") {
    Remove-Item "storage/logs/*.log" -Force
}

# 3. Ask to remove vendor / node_modules
$removeDeps = Read-Host "Do you want to strip /vendor and /node_modules to save space? (y/n)"
if ($removeDeps -eq 'y') {
    Write-Host "Removing vendor..." -ForegroundColor Blue
    if (Test-Path "vendor") { Remove-Item -Recurse -Force "vendor" }
    
    Write-Host "Removing node_modules..." -ForegroundColor Blue
    if (Test-Path "node_modules") { Remove-Item -Recurse -Force "node_modules" }
}

Write-Host "Cleanup complete! The folder is now ready to be zipped and sent to the lecturer." -ForegroundColor Green
