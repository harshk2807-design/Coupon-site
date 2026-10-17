# WorthDeals – Coupon Website (Laravel 13 + MySQL)

A coupon & deals website like worthepenny.com:

- **Store pages on subdomains** – `temu.yourdomain.com/coupon` (or `yourdomain.com/store/temu` on temporary domains)
- **Show Code** like worthepenny – new tab shows the code popup, current tab goes to the store (affiliate link)
- **Categories** – main categories + subcategories, a *Categories* mega menu, category pages with
  breadcrumb, sidebar and an **A–Z store list**
- **Admin panel** (Filament) at `/admin` – add/edit/delete stores (with logo upload + categories), coupons, categories
- Homepage, all-stores A–Z page, store search, mobile friendly, SEO titles/descriptions
- Expired coupons hide automatically (still visible on their expiry day)

---

## 1. Run it on your Mac

### Install once
| Tool | What for | Where |
|---|---|---|
| **Laravel Herd** | PHP 8.3+, Composer, serves `*.test` sites | herd.laravel.com |
| **DBngin** | MySQL database | dbngin.com → `+` → MySQL → Create → **Start** |
| **TablePlus** (optional) | look inside the database | tableplus.com |

### Set up the project
1. Unzip this folder into **`~/Herd`** so you have `~/Herd/coupon-site`
   (the folder name becomes the address: `coupon-site.test`).
2. In TablePlus (or `mysql -u root`) create an empty database named **`coupon_site`**.
3. Open the folder in VS Code → Terminal (**Control + `**) and run, one at a time:

```bash
composer install                 # downloads Laravel + Filament into /vendor
cp .env.example .env             # your settings file
php artisan key:generate         # creates the app secret
```

4. Open **`.env`** and check / change:

```env
APP_URL=http://coupon-site.test
APP_DOMAIN=coupon-site.test
USE_SUBDOMAINS=false            # start with false, see section 4
ADMIN_EMAIL=you@example.com     # your admin login
ADMIN_PASSWORD=a-strong-password
DB_DATABASE=coupon_site
DB_USERNAME=root
DB_PASSWORD=
```

5. Create the tables + demo data + admin user, and make uploaded logos visible:

```bash
php artisan migrate --seed
php artisan storage:link
```

6. Open:
   - **http://coupon-site.test** – the website
   - **http://coupon-site.test/admin** – admin panel (login = `ADMIN_EMAIL` / `ADMIN_PASSWORD`)

### Try subdomains on your Mac
Set `USE_SUBDOMAINS=true` in `.env`. Herd serves subdomains automatically, so
**http://temu.coupon-site.test/coupon** works. (Set it back to `false` any time.)

---

## 2. Save to GitHub

```bash
git init
git branch -M main
git remote add origin https://github.com/YOUR-USERNAME/YOUR-REPO.git
git add .
git commit -m "Coupon website"
git push -u origin main
```

`.env`, `/vendor` and uploaded files are **not** uploaded (see `.gitignore`) – your passwords stay private.

---

## 3. Put it live on Hostinger (shared / Business hosting)

1. **hPanel → Advanced → PHP Configuration** → choose **PHP 8.3** (or newer).
2. **hPanel → Databases → MySQL** → create a database + user. Note the name, user, password.
3. **hPanel → Advanced → SSH Access** → enable it, then connect from your Mac terminal:
   `ssh -p 65002 u123456789@your-server-ip` (details are shown in hPanel).
4. Get the code into the website folder:

```bash
cd ~/domains/yourdomain.com
rm -rf public_html                      # removes the default empty site (make a backup if it had files!)
git clone https://github.com/YOUR-USERNAME/YOUR-REPO.git public_html
cd public_html
composer install --no-dev --optimize-autoloader
cp .env.example .env
nano .env                                # fill in the values below, Ctrl+O Enter to save, Ctrl+X to exit
php artisan key:generate
php artisan migrate --seed --force
php artisan storage:link
php artisan optimize
```

Live `.env` values:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
APP_DOMAIN=yourdomain.com
USE_SUBDOMAINS=false               # true after step 4 below
SESSION_DOMAIN=.yourdomain.com
DB_DATABASE=u123456789_coupons     # from step 2
DB_USERNAME=u123456789_admin
DB_PASSWORD=your-db-password
ADMIN_EMAIL=you@example.com
ADMIN_PASSWORD=a-strong-password
```

The `.htaccess` file in the project root sends every visitor to the `public` folder, so it works
directly inside `public_html`.

**Updating later:** push to GitHub from your Mac, then on the server:
`cd ~/domains/yourdomain.com/public_html && git pull && composer install --no-dev && php artisan migrate --force && php artisan optimize`

---

## 4. Turn on store subdomains (temu.yourdomain.com)

1. **DNS:** add an `A` record **`*`** → your hosting IP (Hostinger DNS zone, or Cloudflare).
2. **Hostinger → Domains → Subdomains:** create subdomain **`*`** and set its folder to the
   **same `public_html`** as the main site (ask Hostinger chat if `*` is not accepted).
3. **SSL** for `*.yourdomain.com` (Cloudflare proxy, or ask Hostinger for a wildcard certificate).
4. In `.env`: `USE_SUBDOMAINS=true`, then run `php artisan optimize`.

Every store now opens at `https://STORE-SLUG.yourdomain.com/coupon` – new stores work automatically.

---

## 5. Which file does what (read in this order to learn Laravel)

| File | What it does |
|---|---|
| `database/migrations/2026_*` | Database tables: `categories`, `stores`, `category_store`, `coupons` |
| `app/Models/Store.php`, `Coupon.php`, `Category.php` | One class per table + relationships (`$store->coupons`, `$category->stores`) |
| `config/coupons.php` | Reads `APP_DOMAIN` and `USE_SUBDOMAINS` from `.env` |
| `app/Support/Site.php` | Builds links: `Site::storeUrl('temu')` → subdomain or `/store/temu` |
| `routes/web.php` | **All URLs.** One route for all stores, one for all categories (dynamic routing) |
| `app/Http/Controllers/*` | Load data for each page (Home, Store, Category, Search) |
| `resources/views/layouts/app.blade.php` | Header (search, Categories menu, Admin), footer – shared by all pages |
| `resources/views/*.blade.php` | Pages: `home`, `store`, `category`, `categories`, `stores`, `search` |
| `resources/views/partials/*` | Reusable pieces: coupon card, store card, A–Z list, code popup |
| `public/css/site.css` | All website styling (change `--brand` for your colour) |
| `public/js/site.js` | Show Code popup, coupon filter, menu on phones |
| `app/Filament/Resources/*` | Admin panel: `Schemas/*Form.php` = forms, `Tables/*Table.php` = lists |
| `app/Providers/AppServiceProvider.php` | Loads the Categories menu (cached 10 min, auto-cleared on change) |
| `database/seeders/*` | Admin user, default categories, demo stores |

### Useful commands
```bash
php artisan migrate                          # create new tables after adding a migration
php artisan db:seed --class=CategorySeeder   # add default categories again (keeps your data)
php artisan route:list                       # see every URL of the site
php artisan make:filament-user               # create another admin login
php artisan optimize:clear                   # clear caches if a change doesn't show
```

### Notes
- A store can be in several categories. A main category page shows the stores of all its subcategories.
- Deleting a main category turns its subcategories into main categories (nothing is lost).
- Logos: upload in the admin panel (stored in `storage/app/public/logos`).
