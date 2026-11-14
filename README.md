# GlobeTrek Adventures

**Academic travel-booking web application | PHP · MySQL · JavaScript · HTML/CSS**

Built for the **Web Application Development** module at **ICBT Campus**, GlobeTrek Adventures demonstrates travel-package discovery, customer bookings, staff operations, and administrative management within a Sri Lankan tourism scenario.

> **Academic demonstration — not a live booking or payment service.** The original coursework implementation is preserved. Its booking page simulates card payment; there is no real payment gateway. **Never submit real card information.**

![GlobeTrek Adventures homepage visual](assets/images/hero.png)

## Project overview

| Area | Source-backed functionality |
| --- | --- |
| Travel packages | Package listing, filtering, details, and itineraries |
| Travel information | Destinations, accommodation, transport, and guides |
| Customer accounts | Registration, login, profile/booking pages |
| Booking flow | Customer booking requests with an **academic-only card-payment simulation** |
| Staff dashboard | Booking status updates, package updates, and customer-query responses |
| Admin dashboard | Staff management, package management, bookings, and summary statistics |
| Storage | MySQL via PHP PDO |

The source has role-based customer, staff, and administrator routes. Some separate staff/report pages are unfinished placeholders even though related functions exist in dashboards. The README describes the checked-in code, **not a fully independently tested release**.

## Technology stack

- **Frontend:** HTML5, CSS3, JavaScript
- **Backend:** PHP, session-based authentication, PDO
- **Database:** MySQL
- **Local development:** Apache + MySQL through MAMP, WAMP, XAMPP, or equivalent

## Application structure

```text
globetrek-adventures/
├── admin/              # Package, staff and booking administration
├── assets/             # Homepage, destination and section images
├── css/                # UI styling
├── dashboard/          # Customer, staff and admin dashboards
├── database/
│   └── schema.sql      # Database schema and academic sample data
├── includes/           # Database connection, sessions, auth and shared layout
├── js/                 # Frontend interactions
├── staff/              # Standalone staff pages (some are placeholders)
├── booking.php         # Original coursework booking and simulation flow
├── login.php
├── register.php
└── index.php
```

## Run locally

**Requirements:** a local Apache/PHP environment with MySQL and PDO MySQL enabled.

1. Clone the repository:

   ```bash
   git clone https://github.com/kevin-menuja/globetrek-adventures.git
   ```

2. Place the cloned project inside your web server's document root, using the directory name **`globetrek_adventures`**. Many application links use this exact path. For example:

   ```text
   C:/xampp/htdocs/globetrek_adventures/
   ```

   On macOS, use the equivalent MAMP document root.

3. Start **Apache** and **MySQL**. Import the repository's real SQL file using phpMyAdmin or your MySQL client:

   ```bash
   mysql -u root -p < database/schema.sql
   ```

   **Important:** `schema.sql` creates the `globetrek_adventures` database and **drops existing application tables** before recreating them. Import into a disposable local database only. Never run it against valued data.

4. If needed, configure the environment variables read by `includes/db.php`: `DB_HOST`, `DB_NAME`, `DB_USER`, and `DB_PASS`. The existing source has local fallbacks; use separate local credentials where practical.

5. Open the application in your browser:

   ```text
   http://localhost/globetrek_adventures/
   ```

The SQL script includes demonstration user accounts and sample travel packages. Do not deploy those seed accounts unchanged to a public service.

## Important academic boundaries

- **Original booking simulation:** `booking.php` asks for fictional card-style inputs and stores an academic payment record labeled as simulated paid. It does **not** charge a card.
- **Not production-ready:** No real payment-processing integration, verified settlement, or production security assessment is claimed.
- **Local paths:** The application assumes the `/globetrek_adventures/` URL prefix.
- **SQL import:** Re-running `database/schema.sql` removes existing application data.
- **Testing:** The repository has no verified end-to-end or integration test result presented here.

These limitations are documented to represent the original coursework accurately; **the original application code and database schema have not been modified for this README**.

## Explore the implementation

- [Original booking implementation](booking.php)
- [Authentication and CSRF helpers](includes/auth.php)
- [Database schema](database/schema.sql)
- [Staff dashboard](dashboard/staff-dashboard.php)
- [Administrative dashboard](dashboard/admin-dashboard.php)
- [Personal portfolio case study](https://github.com/kevin-menuja/kevin-menuja/blob/main/projects/globetrek-adventures.md)

## Academic attribution

**Project type:** Academic coursework  
**Institution:** ICBT Campus  
**Repository owner:** [Kevin Menuja](https://github.com/kevin-menuja)  
**Original source:** Preserved through the GitHub ownership transfer from `Startek-pvt-ltd`.

This project is presented as academic work. The Git history remains the primary record of contributions; additional teammate credits should be retained where applicable.
