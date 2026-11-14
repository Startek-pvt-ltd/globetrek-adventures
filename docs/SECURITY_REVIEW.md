# GlobeTrek Adventures — Security and Readiness Review

**Scope:** source inspection of the public GitHub repository. This is an academic demonstration, not a production certification. No deployed environment, real browser runtime, database instance, or full automated test suite was exercised in this review.

## Scope of documentation work

**The original academic application behavior is preserved.** The `booking.php` and `database/schema.sql` files were restored exactly to their `main` versions in this review branch. The remaining proposals are documentation, local-file hygiene, and optional PHP syntax-lint automation.

1. **README:** documents coursework scope, setup, destructive schema initialization, and the original simulated payment behavior.
2. **Local file hygiene:** `.gitignore` excludes future environment files and macOS metadata. Previously tracked `.DS_Store` files remain tracked.
3. **Basic continuous integration:** a PHP syntax-lint workflow was proposed. Initial GitHub Actions runs **failed**; root cause and execution results remain unresolved.

### Preserved original payment implementation

The original `booking.php` asks for a simulated card number, expiry, and CVV, validates the formats, and creates a booking marked `payment_status='paid'` / `booking_status='pending'`. It inserts a `Card Simulation` payment row. This is **not a real financial transaction** and **must never receive real card credentials**. The database schema's original payment-status default remains `paid`. These decisions are documented here, not silently changed.

## Positive patterns found in source

- PDO prepared statements are used on inspected database queries.
- `password_hash` and `password_verify` protect registered account passwords.
- `session_regenerate_id(true)` runs at login.
- `verify_csrf()` is used by inspected POST handlers.
- `require_login(role)` guards inspected administrative, staff, and customer pages.
- `e()` uses `htmlspecialchars` when rendering many user-controlled values.

These are implementation observations, **not a full exploitability assessment**.

## Outstanding issues before public deployment

### High priority
- **Demo identities in SQL:** `database/schema.sql` seeds admin, staff, and customer accounts with password hashes. Never deploy seeded accounts unchanged; isolate demo data and establish a secure bootstrap process.
- **Weak default local DB configuration:** `includes/db.php` falls back to `root` / `root` if environment variables are absent. Change this to fail closed for non-local deployments and use a least-privilege DB account.
- **Payment behavior:** The academic booking simulation accepts card-like values and records simulated paid statuses without an actual charge. Do not submit real card credentials or treat demo payment records as legitimate receipts. No verified gateway, webhooks, reconciliation, or compliant card-processing implementation exists.
- **Destructive initialization:** importing `database/schema.sql` drops application tables. Use versioned migrations, backups, and a nondestructive production migration procedure.
- **Runtime validation:** test role authorization and tampered booking requests against a test database. Static inspection does not establish production security.

### Medium priority
- **Session hardening:** review session cookie `Secure`, `HttpOnly`, `SameSite`, expiry, and TLS requirements for any hosted environment.
- **Authentication defenses:** login rate limiting, password reset, lockout policies, and monitoring are not present in the inspected login code.
- **Input constraints:** validate package prices, group sizes, dates, identifiers, and length limits consistently on server-side write operations.
- **File and image paths:** staff/admin package forms accept user-supplied image paths; constrain these to application-owned assets.
- **Hardcoded URL prefix:** many links assume `/globetrek_adventures/`; configure a base URL for portability.
- **Placeholder files:** several staff/report endpoints remain empty placeholders.
- **Metadata:** tracked `.DS_Store` files should be removed from Git history going forward (without rewriting existing shared history casually).
- **Tests:** add authentication, CSRF, authorization, demo-booking, and query tests plus a browser smoke test.

## Suggested manual regression checks

1. On a local throwaway database, import `database/schema.sql` and verify seeded demo pages load.
2. Confirm anonymous visitors cannot access customer, staff, or administrator dashboards.
3. Confirm customers cannot call staff or admin POST actions.
4. Confirm the original demo booking form **does** show card-number, expiry, and CVV fields; only use fictional test values. Do not enter actual payment credentials.
5. Attempt invalid dates, package identifiers, missing CSRF tokens, negative/zero traveler counts, and groups exceeding package limits. Verify requests are rejected without a database write.
6. Make a valid demo booking using fictional values; verify the original behavior: `booking_status = pending`, `payment_status = paid`, and one `Card Simulation` payment record. This is a simulation only.
7. Verify the administrative revenue widget counts simulated paid bookings as currently implemented; label the figures as academic demonstration data, not actual revenue.
8. Verify package pages, account login, contact requests, and staff dashboard features still work.

## Release gate

**Do not deploy as a real travel-booking or payment system** until the outstanding controls have been implemented, tested, and independently reviewed.

**Attribution:** Validate academic/team ownership before transferring code to a personal account or describing it as a solo project.
