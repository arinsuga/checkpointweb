DEPLOYDIR="./docs/deployments/deploy_20250617"
DBSCRIPT_NAME="dbscript/20250617_patch_data_attends.sql"
CP_SOURCE1="$DEPLOYDIR/source/arins"
CP_SOURCE2="$DEPLOYDIR/source/config"
CP_SOURCE3="$DEPLOYDIR/source/database"
CP_SOURCE4="$DEPLOYDIR/source/resources"

unzip ./docs.zip &&
cp "$DEPLOYDIR/source/database/migrations/2025_06_17_092315_add_columns_attends_table.php" "./database/migrations/"  &&
php artisan optimize:clear  &&
php artisan cache:clear  &&
php artisan config:clear  &&
php artisan view:clear  &&
php artisan route:clear  &&
php artisan migrate  &&
mysql -h localhost -u $1 -p"$2" $3 < "$DEPLOYDIR/$DBSCRIPT_NAME"  &&
cp -r "$CP_SOURCE1" .  &&
cp -r "$CP_SOURCE2" .  &&
cp -r "$CP_SOURCE3" .  &&
cp -r "$CP_SOURCE4" .  &&
php artisan optimize:clear  &&
php artisan cache:clear  &&
php artisan config:clear  &&
php artisan view:clear  &&
php artisan route:clear