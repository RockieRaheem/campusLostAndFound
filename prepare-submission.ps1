$regNo = Read-Host "Enter your Registration Number (e.g., JAN23BSE2177U)"
$outDir = "..\$regNo"

Write-Host "Creating submission folder structure at $outDir..." -ForegroundColor Cyan
New-Item -ItemType Directory -Force -Path $outDir | Out-Null
New-Item -ItemType Directory -Force -Path "$outDir\Screenshots" | Out-Null

Write-Host "Copying source code to $outDir\Source_Code..." -ForegroundColor Cyan
# Copy everything except node_modules, vendor, .git, and temporary files
Copy-Item -Path . -Destination "$outDir\Source_Code" -Recurse -Exclude "node_modules", "vendor", ".git", ".vscode", "tests", "*.log", ".phpunit.cache", "artisan_test*", "test_results_*.txt", "prepare-submission.ps1", "strip_bom.ps1" -Force

Write-Host "Running Laravel cleanup commands on the copied source code..." -ForegroundColor Cyan
Push-Location "$outDir\Source_Code"
php artisan optimize:clear
php artisan view:clear
php artisan route:clear
php artisan config:clear
Pop-Location

Write-Host "Submission package prepared at $outDir!" -ForegroundColor Green
Write-Host "NEXT STEPS:" -ForegroundColor Yellow
Write-Host "1. Complete your Final_Project_Report.pdf and place it in $outDir"
Write-Host "2. Complete your Project_Presentation.pptx and place it in $outDir"
Write-Host "3. Add at least 10 screenshots to $outDir\Screenshots"
Write-Host "4. Zip the $outDir folder as $regNo.zip"
