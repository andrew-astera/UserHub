# PHP Website - Login / Register / Profile / File Upload

## Project structure
```
mysite/
├── index.php          # Home page
├── login.php          # User login
├── register.php        # User registration
├── profile.php         # Profile page + avatar upload
├── upload.php          # Generic file upload (images/PDF/DOCX)
├── logout.php          # Logout
├── config/db.php       # Database connection settings
├── sql/schema.sql       # Database and table creation script
├── css/style.css        # Styling
├── js/script.js         # Form validation + image preview
└── uploads/             # Uploaded files storage (protected by .htaccess)
```

## Setup instructions

1. **Install a local environment**: use XAMPP, WAMP, or Laragon (includes PHP + MySQL + Apache).

2. **Create the database**:
   - Open phpMyAdmin
   - Run the contents of `sql/schema.sql` (creates the `mysite_db` database and the `users` table)

3. **Configure the connection**:
   - Open `config/db.php`
   - Update `$DB_USER` and `$DB_PASS` to match your MySQL setup (default is `root` with no password on XAMPP)

4. **Copy the files**:
   - Copy the whole `mysite` folder into `htdocs` (XAMPP) or `www` (WAMP)

5. **Run it**:
   - Start Apache and MySQL from the XAMPP/WAMP control panel
   - Open your browser at: `http://localhost/mysite/index.php`

## Features
- User registration with password hashing (`password_hash`)
- Secure login with `password_verify` and PHP sessions
- Protected profile page (inaccessible without logging in)
- Avatar upload with type and size validation
- Generic file upload (images, PDF, DOCX) via `upload.php`
- Protection against SQL injection using PDO prepared statements
- `uploads/` folder protected against executing PHP files

## Security notes before deploying
- Change the database credentials in `config/db.php`
- Enable HTTPS in production
- Add deeper validation of uploaded file content (e.g. virus scanning) if the site is public
