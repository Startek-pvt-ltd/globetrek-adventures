# GlobeTrek Adventures

**Academic PHP/MySQL travel-booking web application** built for the Web Application Development module at ICBT Campus.

> **Status: Original academic demonstration — not production-ready.** The original booking form contains card-number, expiry, and CVV fields for a classroom payment simulation. It does **not** connect to a real payment processor. **Never enter genuine payment-card information.** The booking and database implementation remain unchanged.

## What the code demonstrates

- Package discovery with search, destination, price, duration filters, and pagination
- Package details, itinerary information, accommodation, transportation, and guides
- Customer registration and authentication with PHP sessions
- Customer booking history and booking requests
- Role-separated customer, staff, and administrator dashboards
- Package and staff management, customer queries, and basic reporting

A few staff/report pages remain placeholders; see [Known limitations](#known-limitations).

## Stack

PHP · MySQL · HTML · CSS · JavaScript · Bootstrap · PDO

## Local development

1. Install PHP, MySQL, and a local web server (e.g., MAMP, WAMP, or XAMPP).
2. Clone the repository into the web server's document root using the directory name **`globetrek_adventures`**. The current source uses absolute paths based on that directory.
3. Create a **disposable local database** by importing `database/schema.sql` with phpMyAdmin or the command below:

   ```bash
   mysql -u root -p < database/schema.sql
   ```

   **Warning:** This SQL script contains `DROP TABLE IF EXISTS` and will delete existing GlobeTrek tables and data if rerun. Do not import it into a database containing information you need to keep.

4. Configure the database credentials that your PHP/web-server process uses:

   | Variable | Purpose |
   | --- | --- |
   | `DB_HOST` | Database host (local default `localhost`) |
   | `DB_NAME` | Database name (local default `globetrek_adventures`) |
   | `DB_USER` | Database user (local fallback `root`) |
   | `DB_PASS` | Database password (local fallback `root`) |

   The fallback credentials are for local experimentation only. Use a dedicated, least-privilege MySQL user and non-default secret when configuring a real environment.

5. Run Apache and MySQL and open:

   ```text
   http://localhost/globetrek_adventures/
   ```

The schema includes demonstration accounts and sample Sri Lankan travel packages. Do not reuse seeded credentials in a public deployment.

## Booking and payment behavior

The original booking form asks for simulated card number, expiry, and CVV entries. It checks input formats and marks a newly created demo booking as `payment_status = paid` and `booking_status = pending`, inserting a **Card Simulation** payment record. These labels are demonstration behavior **only**, not evidence of a real card transaction or settled funds. No payment processor is integrated. **Do not enter actual card credentials or deploy the payment simulation as a real checkout.** This behavior is preserved as originally implemented for coursework.

## Security-related implementation notes

The source uses PDO prepared statements for database queries, `password_hash` / `password_verify` for passwords, output escaping, CSRF tokens for forms, and role checks for private dashboards.

These safeguards are useful foundations, **not** evidence that the app is hardened for public deployment. Review [docs/SECURITY_REVIEW.md](docs/SECURITY_REVIEW.md) before publishing the app beyond a local demonstration.

## Quality checks

After installing PHP locally, syntax-check all PHP files:

```bash
find . -type f -name '*.php' -not -path './vendor/*' -print0 | xargs -0 -n 1 php -l
```

A proposed GitHub Actions workflow attempts PHP syntax lint on pushes and pull requests. Its initial runs reported failure, and the reason has not yet been verified. No successful automated check or application runtime test is claimed.

## Known limitations

- **Academic-only:** do not present the application as a commercial booking or payment platform.
- **Unfinished pages:** `admin/reports.php`, `staff/confirm-bookings.php`, `staff/respond-queries.php`, and `staff/update-packages.php` contain placeholders (related functionality appears in the dashboards).
- **Local path assumption:** routes and assets assume the URL prefix `/globetrek_adventures/`.
- **Demo seed accounts:** SQL imports seeded local users; remove or secure them before any external deployment.
- **Classroom payment simulation:** the booking form requests card-like entries and records a simulated 'paid' status; never submit real card details or treat the result as a genuine payment.
- **Testing:** PHP syntax lint is not a substitute for authentication, authorization, booking, and end-to-end tests.
- **Database initialization:** schema import is destructive; migrations and backup procedures are not yet implemented.

## Portfolio and attribution

This repository demonstrates an academic project. Its presence under Startek does not by itself establish sole individual authorship. Before transferring repository ownership or claiming exclusive credit, confirm the original coursework/team attribution and review all project assets.

**Further engineering work proposed:** real automated tests, seed isolation, secret-management improvements, environment-independent paths, screenshot documentation, accessibility checks, and safer deployment configuration.
