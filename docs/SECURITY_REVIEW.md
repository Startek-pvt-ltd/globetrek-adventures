# GlobeTrek Adventures — Security and Readiness Review

**Scope:** source inspection of the public GitHub repository. This is an academic demonstration, not a production certification. No deployed environment, real browser runtime, database instance, or full automated test suite was exercised in this review.

## Changes proposed in this branch

1. **Demo booking flow:** `booking.php` no longer asks for card numbers, expiry dates, or CVVs. No payment is charged, and a new booking remains `payment_status = pending`. No fictitious successful payment row is recorded.
2. **Safer SQL default:** `database/schema.sql` defaults new bookings to pending payment status. Note this affects **newly imported databases only**; existing databases require an explicit reviewed migration.
3. **Documentation:** README explains the academic scope, setup steps, destructive schema import, and known limitations.
4. **Basic continuous integration:** PHP syntax lint is added to catch parse errors, not application behavior or security issues.
5. **Local file hygiene:** `.gitignore` excludes future environment files and macOS metadata. Already-tracked `.DS_Store` files remain tracked until removed separately.

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
- **Payment behavior:** No external processor, verified webhook handling, reconciliation, refund workflow, or PCI-compliant processing exists. Do not present bookings as real paid orders.
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
4. Confirm booking pages show **no card number, expiry, or CVV input**.
5. Attempt invalid dates, package identifiers, missing CSRF tokens, negative/zero traveler counts, and groups exceeding package limits. Verify requests are rejected without a database write.
6. Make a valid demo booking; verify `booking_status = pending`, `payment_status = pending`, and **no fabricated payment record** is created.
7. Verify the administrative revenue widget does not count demo bookings as paid revenue.
8. Verify package pages, account login, contact requests, and staff dashboard features still work.

## Release gate

**Do not deploy as a real travel-booking or payment system** until the outstanding controls have been implemented, tested, and independently reviewed.

**Attribution:** Validate academic/team ownership before transferring code to a personal account or describing it as a solo project.
