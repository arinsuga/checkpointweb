@echo off
for /f "tokens=2 delims==" %%a in ('wmic OS Get localdatetime /value') do set "dt=%%a"
set "YYYY=%dt:~0,4%"
set "MM=%dt:~4,2%"
set "DD=%dt:~6,2%"
set "HH=%dt:~8,2%"
set "Min=%dt:~10,2%"
set "Sec=%dt:~12,2%"
set "timestamp=%YYYY%-%MM%-%DD%_%HH%-%Min%-%Sec%"

set "dbhost=your_dbhost"
set "dbuser=your_dbuser"
set "dbpass=your_dbpass"
set "dbname=your_dbname"

set "filename=%dbname%_%YYYY%%MM%%DD%_%HH%%Min%%Sec%"
echo File Name: %filename%

mysqldump --column-statistics=0 -u %dbuser% -h %dbhost% -p"%dbpass%" %dbname% > %filename%.sql
7z.exe a %filename%.zip %filename%.sql
del %filename%.sql
