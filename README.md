# BIH Monitoring

**Bali International Hospital - Internal Monitoring & Data Management System**

A Laravel-based web application for monitoring billing, stock, pricing, insurance tracking, and operational data across departments.

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Framework | Laravel 10.x |
| Frontend | Bootstrap 5.3.3 + custom Shadcn-inspired design system |
| Font | Inter (Google Fonts) |
| Theme | Light / Dark mode toggle |
| Excel Export | SheetJS (`xlsx`) |
| Auth | Session-based with role & permission system |

---

## Modules

### 1. Authentication & User Management
- `/login` - Login page
- `/dashboard` - Main dashboard with module cards
- `/settings` - User management (add user, edit roles, change password, delete user)
- Roles: `ADMIN`, `FINANCE`, `PHARMACY`, `PROCUREMENT`, `PRICE_STRATEGY`, `PRICE_ENTRY`, `PRICE_APPROVER`, `TRACK_INSURANCE`

### 2. Billing Monitoring
- `/billing` - Billing data view & export (format 1)
- `/billing2` - Billing data view & export (format 2)

### 3. Rejected Claims
- `/rejected` - Monitor rejected billing claims

### 4. Stock Monitoring
- `/stock` - Stock data overview & export
- `/stock-management` - Stock adjustment, calculation, comparison, and JSON download
- `/import` - Manual data import with progress tracking
- Permission: `adjustment_stock`

### 5. Accrual
- `/accrual` - Accrual monitoring & export

### 6. List Item & Pricing
- `/arc-itm-mast` - Item master list (CRUD)
- `/arc-item-price-italy` - Italy pricing management
- `/margin` - Margin configuration
- `/details-invoice-tc` - Invoice detail T&C
- Permission: `list_item_pricing`

### 7. Price Submission Approval
- `/price-submissions` - View all price submissions
- `/price-submissions/{id}` - Detail view
- Approve / reject workflow

### 8. Doctors Fee
- `/doctors-fee` - Doctors fee monitoring

### 9. AR Tracking Insurance
- `/track` - Accounts Receivable tracking for insurance claims
- Permission: `track-insurance`

#### Data Model
- `tcmon_ar_billing` is the billing source table.
- `/track` aggregates billing data by `invoiceno`.
- Tracking workflow metadata is stored in `tcmon_ar_tracking`.
- Updates from the tracking page do not write back to `tcmon_ar_billing`.
- Expected billing shape is `1 invoice = 1 row` with `invoiceno` as the business key in the upstream ETL.

#### Status Meaning
- `BATCHING` - Default status before invoice is sent to insurance.
- `SENT` - Invoice has been sent to insurance.
- `RECEIVED` - Insurance has received the invoice from BIH.
- `REVISE` - Invoice has been returned by insurance for revision.
- `PAID` - Insurance has fully paid BIH.

#### Status Flow
```text
BATCHING -> SENT -> RECEIVED -> PAID
                     |
                     -> REVISE -> SENT

CANCEL = visual cancel flag, balance remains visible and is auto-zeroed on the 5th of the following month
```

#### Features
- Status tabs: All / Batching / Sent / Received / Revise / Paid / Cancelled
- Filters: batch date range, invoice printed date range, payer, search
- Tracking save endpoint: `POST /track/update`
- Modal actions: Set Sent, Set Received, Set Revise, Set Paid, Update Remarks, Cancel Invoice
- Compact row action popup on non-sticky table area
- Cancel source indicator: billing source, tracking source, or both
- Cancel popup info includes the scheduled auto-zero date
- Excel export per tab sheet
- Full dark mode support

#### Tracking Persistence
`/track` persists the following fields to `tcmon_ar_tracking`:
- `status`
- `ref_no`
- `courier_via`
- `tracking_no`
- `sent_date`
- `received_date`
- `paid_on`
- `cancelled_date`
- `due_days`
- `remarks`

---

## Permission System

Access is controlled via `config/roles.php` and the `CheckDataMonitoringPermission` middleware.

| Role | Access |
|------|--------|
| ADMIN | All modules |
| FINANCE | Billing, Stock, Rejected, Stock Management, Accrual |
| PHARMACY | Stock, Stock Adjustment, Item Pricing |
| PROCUREMENT | Item Pricing |
| PRICE_STRATEGY | Item Pricing |
| PRICE_ENTRY | Item Pricing |
| PRICE_APPROVER | Item Pricing |
| TRACK_INSURANCE | AR Tracking |

---

## Design System

Custom Shadcn-inspired design in `public/css/shadcn-style.css` with:
- CSS custom properties for colors, spacing, radius, and shadows
- Light / dark theme support
- Buttons, cards, tables, badges, alerts, modals, dropdowns, pagination
- Inter font family

---

## Setup

```bash
# Clone
git clone https://github.com/iqbalk18/Monitoring.git
cd Monitoring

# Install dependencies
composer install
npm install

# Environment
cp .env.example .env
php artisan key:generate

# Run
php artisan serve
```

---

## Project Structure

```text
app/
|-- Http/
|   |-- Controllers/        # All feature controllers
|   `-- Middleware/         # CheckDataMonitoringPermission
|-- Models/
config/
|-- roles.php               # Role & permission definitions
database/
|-- migrations/
public/
|-- css/shadcn-style.css    # Design system
resources/
|-- views/
|   |-- layouts/app.blade.php
|   |-- dashboard.blade.php
|   |-- track/index.blade.php
|   |-- billing.blade.php
|   |-- billing2.blade.php
|   |-- stock.blade.php
|   |-- stock-management.blade.php
|   |-- accrual.blade.php
|   |-- rejected.blade.php
|   |-- settings.blade.php
|   `-- ...
routes/
`-- web.php
```

---

**Developed by IT Department - Bali International Hospital**
