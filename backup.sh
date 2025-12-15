#!/bin/bash
DB_NAME='accounting_db'
DB_USER='root'
DB_PASS=''
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
mkdir -p backups
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME > backups/${DB_NAME}_$TIMESTAMP.sql
echo "Backup saved to backups/${DB_NAME}_$TIMESTAMP.sql"
