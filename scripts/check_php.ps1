php -v 2>&1 | Out-File -Encoding utf8 .\php-version.log
php -r "echo PHP_SAPI;" 2>&1 | Out-File -Encoding utf8 -Append .\php-version.log
if (Test-Path .\php-version.log) { Get-Content .\php-version.log -Raw } else { Write-Error 'php-version.log not created' }
