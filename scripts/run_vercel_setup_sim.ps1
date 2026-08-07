$env:VERCEL='1'
$env:VERCEL_URL='venajon-microfinance-801vwild3-kelvoo-s-projects.vercel.app'
php .\scripts\vercel-setup.php 2>&1 | Out-File -Encoding utf8 .\vercel-setup.log
if (Test-Path .\vercel-setup.log) { Get-Content .\vercel-setup.log -Raw }
else { Write-Error 'log not created' }
