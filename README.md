# Sumitra Shoes Centre — Footwear Store Prototype

**A responsive storefront showcase with a PHP/MySQL local demo.**

## Two project modes

### 1. Browser-based lookbook

The root `index.html` is an interactive **static catalogue preview** for GitHub Pages. It contains local illustrative footwear art, six sample products, keyword search, category filters, and links to source documentation. **It does not sell products or accept payments.**

To preview locally: `python -m http.server 8000 --bind 127.0.0.1` then open `http://127.0.0.1:8000`.

### 2. PHP/MySQL development store

The PHP prototype supports a catalog, account registration/login, session cart, quantity adjustments, and **unpaid demo order requests**. Its checkout does **not** contact eSewa, Khalti, Fonepay, banks, or credit card networks; it does not verify payment. An order is recorded using an `UNPAID-DEMO-` reference. **Never send funds or enter real transaction IDs for this demonstration.**

Setup (local development only):

1. Install PHP 8.1+ with MySQLi and a local MySQL/MariaDB server (XAMPP is one option).
2. Create schema and seed products with `database.sql`. The database name is **`shoes_store`** (not `sumitra_shoes_db`). It creates a users table, empty admins table, products, and orders.
3. Configure `DB_HOST`, `DB_USER`, `DB_PASSWORD`, and `DB_NAME` in your **server environment**. If unset, `config.php` uses local XAMPP development defaults; do not expose that configuration publicly.
4. Place the repository into the local PHP web root and visit `http://localhost/<folder>/index.php`, or run `php -S 127.0.0.1:8001` from the project directory and open `http://127.0.0.1:8001/index.php`.
5. Register a new local test account; no administrator credentials are seeded and the admin panel is not implemented.

## Implemented safety improvements

- Checkout now creates **unpaid** demo orders, not purported payment verifications.
- Prepared SQL statements are used to read order cart items and insert demo orders.
- Checkout form includes a session CSRF token; quantities and product IDs are checked.
- Database connection failures no longer display raw database error details to the browser.
- The former seeded administrator password and nonexistent admin link are removed.
- Added responsive `css/style.css`, safe local image fallback and an independently browsable lookbook.
- GitHub Actions verifies PHP syntax and static website assets.

## Known limitations / not for public commerce

The `orders.transaction_id` field currently stores an **unpaid demo reference**, not a verified gateway receipt. A production system requires order statuses, transactional inventory handling, independently validated payment webhooks, fraud controls, authentication hardening, CSRF protection on all state-changing operations, secure sessions, user-data privacy policies, database migrations, and end-to-end testing. Do not deploy the PHP demo as an operational payment store.

To keep older local databases working, the existing SQL table structures were not migrated in this update. New deployments should design a proper `payment_status` and separate `merchant_reference` fields before integrating payments.

**Portfolio:** [Royalpuskar](https://github.com/Royalpuskar)
