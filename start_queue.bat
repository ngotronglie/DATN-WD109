@echo off
echo Starting Laravel Queue Worker...
php artisan queue:work --verbose --tries=3 --timeout=90
pause
