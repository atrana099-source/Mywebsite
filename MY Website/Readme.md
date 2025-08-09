# Simple Food Ordering (PHP + MySQL)

## Requirements
- Apache with PHP (>=7.2)
- MySQL / MariaDB
- mod_rewrite not required

## Setup
1. Put project folder in Apache's web root (e.g. `/var/www/html/foodapp`).
2. Create a database and import `db.sql`.
   - `mysql -u root -p foodapp < db.sql`
3. Edit `config.php` with DB credentials.
4. Open site: `http://your-server/foodapp/`
5. Admin: go to `http://your-server/foodapp/admin.php`
   - Default admin: user `admin` and password `admin123` (change immediately).

## Notes
- Payment not implemented — Cash On Delivery only.
- For production: secure admin, use HTTPS, sanitize inputs more strictly.
