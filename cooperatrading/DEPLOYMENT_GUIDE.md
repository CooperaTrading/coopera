# Zero-Terminal Production Deployment Guide for Namecheap cPanel

This deployment package is **100% self-contained and zero-terminal**. You do **NOT** need SSH terminal access, and you do **NOT** need to run any artisan, composer, or npm commands on your Namecheap server.

---

## 📦 Package Summary
- **File:** `coopera-deployment-latest.zip`
- **Location:** `/home/zuse/APPs/coopera/cooperatrading/coopera-deployment-latest.zip`
- **Target Domain:** `https://cooperatrading.com`

### What is Pre-Built Inside:
1. **Pre-Generated `.env`**: Pre-configured for `https://cooperatrading.com` with secure `APP_KEY` and production security settings.info@cooperatrading.com
2. **Pre-Populated Database**: `database/database.sqlite` is 100% pre-seeded with all categories, products, partners, quote templates, and the active administrator account (`tsedey.yayeh@cooperatrading.com`).
3. **Optional MySQL Dump**: `database/mysql_coopera_dump.sql` is ready for 1-click import in Namecheap phpMyAdmin if MySQL is preferred over SQLite.
4. **Root `.htaccess` + `public/.htaccess`**: Automatically routes all web traffic safely to the `public/` directory and protects configuration files, even if the Document Root cannot be altered in Namecheap.
5. **Pre-Compiled Frontend**: Production minified CSS/JS in `public/build/` with golden amber branding.
6. **Complete Vendor Autoloader**: All PHP packages and dependencies in `vendor/`.
7. **Storage Auto-Link**: Handled automatically on application boot.

---

## 🚀 3-Step Zero-Terminal Upload (Namecheap cPanel)

### Step 1: Upload via Namecheap cPanel File Manager
1. Log in to your **Namecheap cPanel** (e.g. `https://your-server.namecheap.com:2083` or via your Namecheap Dashboard > Hosting List > cPanel).
2. Open **File Manager**.
3. Navigate to your website root folder:
   - For primary domain (`cooperatrading.com`): `public_html/`
   - For an addon domain: your designated addon domain folder (e.g., `cooperatrading.com/` or `public_html/cooperatrading/`)
4. Click **Upload** in the top toolbar and select `coopera-deployment-latest.zip`.

---

### Step 2: Extract the Archive
1. In File Manager, right-click `coopera-deployment-latest.zip`.
2. Select **Extract** (Extract to the current folder).
3. Ensure hidden files are visible in cPanel File Manager (**Settings** gear top right > check **Show Hidden Files (dotfiles)**).
4. Verify that `.env` and `.htaccess` are present in the folder.
5. Delete `coopera-deployment-latest.zip` after extraction to save hosting disk space.

---

### Step 3: Document Root & .htaccess
- If Namecheap allows setting the document root for your domain, point it to the **`public/`** folder:
  ```text
  Document Root: /home/username/public_html/public
  ```
- **Note:** If you are deploying into standard `public_html/` and cannot change Document Root, the included **root `.htaccess`** will automatically rewrite all requests to `public/` transparently with no extra configuration needed!

---

## 🗄️ Database Options

### Option 1: SQLite (Zero Setup — Recommended for Instant Launch)
- Works immediately out of the box with zero configuration!
- No database creation, user permissions, or passwords needed.
- Contains all seeded categories, products, partners, and the admin account.

### Option 2: MySQL on Namecheap (1-Click phpMyAdmin Import)
If you prefer running MySQL on Namecheap:
1. In cPanel, go to **MySQL Databases** and create a new database (e.g., `user_coopera`) and database user with full privileges.
2. Open **phpMyAdmin**, click your new database, navigate to **Import**, and upload `database/mysql_coopera_dump.sql`.
3. In File Manager, edit `.env` and update your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_DATABASE=your_cpanel_dbname
   DB_USERNAME=your_cpanel_dbuser
   DB_PASSWORD=your_cpanel_dbpassword
   ```

---info@cooperatrading.com

## 🔐 Admin Login Credentials
- **Login URL:** `https://cooperatrading.com/login` (or `https://cooperatrading.com/admin`)
- **Email:** `tsedey.yayeh@cooperatrading.com`
- **Password:** `P@ssw0rdcoop2026`

### 🔑 Forgot Password
- The password reset system is strictly tied to **`tsedey.yayeh@cooperatrading.com`**.
- Any reset link requests for other email addresses are blocked for security.

---

## 🛠️ Post-Deploy Maintenance (Optional)

If you ever notice stale styles, broken navigation, or after a future update that adds a new migration, **visit these web URLs once** in your browser — they require zero terminal access:

| URL | Purpose |
|---|---|
| `/clear-cache.php` | Clears all Laravel caches, ensures `public/storage` symlink, runs any pending migrations, and clears compiled views. Safe to run anytime — it's fully idempotent. |
| `/debug.php` | Diagnostic page showing PHP version, loaded extensions, storage paths, and DB connectivity. |
| `/login` | Admin login. |

> **Tip:** Bookmark `/clear-cache.php` in your browser. It's your one-stop post-deploy reset button — no SSH required.
