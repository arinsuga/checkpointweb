DEPLOYDIR="./docs/deployments/deploy_20250626"
DBSCRIPT_NAME=""
CP_SOURCE1="$DEPLOYDIR/source/arins"
CP_SOURCE2="$DEPLOYDIR/source/resources"

unzip ./docs.zip &&
php artisan optimize:clear  &&
php artisan cache:clear  &&
php artisan config:clear  &&
php artisan view:clear  &&
php artisan route:clear  &&
php artisan migrate  &&
cp -r "$CP_SOURCE1" .  &&
cp -r "$CP_SOURCE2" .  &&
php artisan optimize:clear  &&
php artisan cache:clear  &&
php artisan config:clear  &&
php artisan view:clear  &&
php artisan route:clear &&
composer dumpautoload