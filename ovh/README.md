# École de l'Assomption — site PHP + SQLite (OVH)

The public site and its admin area, built for OVH shared web hosting (PHP 8.1+,
no Node, no build step on the server). It replaces the Next.js + Sanity
version that lives in the rest of this repository: the URLs and the design stay
the same.

```
ovh/
├── .ovhconfig          PHP version for OVH (8.3)
├── app/                application code (NOT web-accessible)
│   ├── config.sample.php → copy to config.php
│   ├── content/schema.php   ← every editable page and field is declared here
│   ├── content/seed.php     initial content (the previous site's text)
│   ├── site.php / admin.php routers
│   └── views/site, views/admin
├── data/               SQLite database + sessions (NOT web-accessible)
├── tools/              Tailwind build of the public CSS (dev machine only)
└── www/                web root
    ├── index.php       front controller
    ├── assets/         css, js, fonts (self-hosted)
    └── uploads/        images & documents (PHP execution disabled)
```

## Run it locally

```bash
cp ovh/app/config.sample.php ovh/app/config.php   # set env=development, secure_cookies=false, setup_key
php -S localhost:8000 -t ovh/www ovh/www/index.php
```

Open http://localhost:8000/admin. The first visit offers to create the
administrator account (you need the `setup_key` from config.php). The
database is created and filled with the current site content automatically.

## Deploy on OVH (shared hosting)

1. **Upload** over FTP/SFTP into the home directory of the hosting, so that
   you get `~/app`, `~/data`, `~/www` and `~/.ovhconfig` side by side.
   OVH serves `~/www` as the web root by default (check *Multisite* in the
   control panel if your domain points elsewhere).
   If you really cannot place folders outside `www/`, put `app/` and `data/`
   inside `www/`: `index.php` finds them, and the `.htaccess` files block
   direct access, but outside is safer.
2. **Configure**: copy `app/config.sample.php` to `app/config.php` and fill in
   `base_url` and a long random `setup_key`
   (`php -r "echo bin2hex(random_bytes(16));"`).
3. **Permissions**: `data/` and `www/uploads/` must be writable by PHP
   (the default 755 on OVH works).
4. **HTTPS**: enable the free SSL certificate in the OVH control panel
   (*Hébergements → Certificat SSL*). `.htaccess` then redirects HTTP to HTTPS.
5. Visit `https://your-domain/admin/setup`, create the administrator account,
   then add the other people from **Comptes**.
6. **Upload limit**: OVH's default `upload_max_filesize` may be lower than the
   20 MB the app allows; the admin automatically shows the real limit.

Updating the code later: upload `app/` and `www/assets/` again. Never overwrite
`data/`, `www/uploads/` or `app/config.php`.

## Backups

* **Admin → Sauvegarde** downloads a consistent copy of the database (texts,
  accounts, history).
* `www/uploads/` holds the files; OVH also keeps automatic snapshots of the
  hosting (*FTP → Restaurer une sauvegarde*).

## Lost admin password

Another administrator can create a temporary password from **Comptes**.
If nobody can log in, with SSH access (OVH Pro plans and above):

```bash
php app/cli.php reset-password someone@example.org
```

## Changing the public design

The public pages use Tailwind classes, compiled once into `www/assets/site.css`.
After editing classes in `app/views/site` (with this repo's node_modules installed):

```bash
node ovh/tools/build-css.mjs
```

The admin stylesheet (`www/assets/admin.css`) is plain CSS.

## Adding an editable field

Add it to the page's `sections` in `app/content/schema.php` (type, name,
label, help text), then use it in `app/views/site/pages/<page>.php`
(`$c->t('name')`, `$c->html('name')`, `$c->img('name')`…). The admin form,
validation, history and preview pick it up automatically.

## Security notes

* Accounts with bcrypt passwords, one per person, 4 roles (admin / école / APEL / OGEC).
* Login throttling (5 attempts per account per 15 min), session regeneration,
  idle timeout of 4 h, sessions invalidated when the password changes.
* CSRF token on every form and API call; strict Content-Security-Policy.
* Rich text is sanitized on save with an allow-list; plain text is escaped on output.
* Uploads are checked by content (not file name), images are re-encoded
  (strips EXIF/GPS), stored with random names, and `uploads/` cannot run scripts.
* Public pages set no cookies; fonts are self-hosted; the map only loads
  after a click (GDPR).
