# H-Mart E-Commerce System Architecture

This README documents the project directory layout, mapping directories and files to their architectural responsibilities: Frontend, Backend, Admin Panel, and Database layers.

---

## 📂 Directory Layout

```
ecommerce/
├── admin/                    # Admin Panel Operations & Sub-apps
│   ├── dashboard/            # Sales analytics & forecasting dashboard
│   ├── products/             # Inventory listings & product asset uploads
│   ├── vendors/              # Supplier registry & purchase orders
│   ├── theme/                # Layout templates for operations portal
│   └── index.php             # Admin panel gateway route
│
├── api/                      # Public/Private JSON API Endpoints
│   ├── verify_email.php      # Email verification link handler
│   ├── search_api.php        # Real-time search autocompletion
│   └── compare_api.php       # Product specs comparison route
│
├── cart/                     # Shopping Cart Processing
│   ├── controller.php        # Cart additions, removals, & checkouts
│   └── ...
│
├── components/               # UI components shared by the frontend
│   └── recommended_products.php
│
├── css/ & js/                # Assets for public storefront templates
│
├── customer/                 # Customer Portal Details
│   ├── controller.php        # Profile edits & order cancellations
│   └── ...
│
├── database/                 # Schema Expansion & Migrations
│   ├── migrations_expansion.sql  # Multi-currency, logs & translation tables
│   ├── smart_features.sql        # OTP codes & fraud monitoring
│   └── admin_seed.sql            # Testing/Mock seed data
│
├── include/                  # Application Core Kernel (PHP logic)
│   ├── config.php            # Environment setup & path mapping
│   ├── mail_config.local.php # SMTP local configuration
│   ├── database.php          # MySQLi DB transaction wrapper
│   ├── initialize.php        # Loader/Bootstrapper script
│   ├── function.php          # Core utilities (i.e. t(), convert_price())
│   ├── mailer.php            # PHPMailer connection handler
│   └── otp_service.php       # OTP dispatch and generation logic
│
└── theme/                    # Main Storefront Layout Templates
    └── templates.php         # Main public header, footer, & navigation bar
```

---

## 🏛️ Layer Breakdown

### 1. 🖥️ Frontend (Storefront / Client-side View)
Manages the user interface and shopping experience.
* **Root Pages**: Entry points like `index.php`, `home.php`, `about.php`, `contact.php`, `login_page.php`, and `signup_page.php`.
* **Public Layout templates (`theme/`)**: Renders layout structures like the public header, navigation navbar, footer widgets, and auth modals.
* **Shared UI elements (`components/`)**: Specific UI modules, such as recommended product grids.

### 2. ⚙️ Backend (Business Logic & APIs)
Processes server-side calculations, database commands, and third-party integrations.
* **Kernel & Settings (`include/`)**: Configures application defaults (`config.php`), establishes DB connections (`database.php`), and bootstraps components (`initialize.php`).
* **Utility Libraries (`include/function.php`)**: Implements standard translation lookups (`t()`) and dynamic currency converting (`convert_price()`).
* **API Endpoints (`api/`)**: JSON services for async storefront actions.
* **Controllers**: Handles state mutations for shopping carts (`cart/controller.php`) and customer profile updates (`customer/controller.php`).

### 3. 🛡️ Admin Panel (Operations Management)
A dedicated administrative application to manage the platform.
* **Dashboard Control**: Renders vendor management, reorder lists, and churn forecasting under `admin/`.
* **Private Templates (`admin/theme/`)**: Distinct dashboard panel style templates, separate from the storefront.

### 4. 🗄️ Database Setup & Migrations
Holds standard definition queries and table setups.
* **DDL Scripts (`database/`)**: Hosts specific migrations to append tables (e.g. `tbl_otp_codes`, `audit_logs`, `translations_cache`).
* **Seed Scripts (`database/`)**: Populates dummy analytics metrics.
