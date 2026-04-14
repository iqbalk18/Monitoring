# BIH Monitoring

**Bali International Hospital — Internal Monitoring & Data Management System**

A Laravel-based web application for monitoring billing, stock, pricing, insurance tracking, and operational data across departments.

---

## 🚀 Tech Stack

| Layer | Technology |
|-------|-----------|
| Framework | Laravel 10.x |
| Frontend | Bootstrap 5.3.3 + Custom Shadcn-inspired Design System |
| Font | Inter (Google Fonts) |
| Theme | Light / Dark mode toggle |
| Excel Export | SheetJS (xlsx) |
| Auth | Session-based with role & permission system |

---

## 📦 Modules

### 1. Authentication & User Management
- `/login` — Login page
- `/dashboard` — Main dashboard with module cards
- `/settings` — User management (add, edit roles, change password, delete)
- **Roles**: `ADMIN`, `FINANCE`, `PHARMACY`, `PROCUREMENT`, `PRICE_STRATEGY`, `PRICE_ENTRY`, `PRICE_APPROVER`, `TRACK_INSURANCE`

---

### 2. Billing Monitoring
- `/billing` — Billing data view & export (format 1)
- `/billing2` — Billing data view & export (format 2)
- Export to Excel

---

### 3. Rejected Claims
- `/rejected` — Monitor rejected billing claims

---

### 4. Stock Monitoring
- `/stock` — Stock data overview & export
- `/stock-management` — Stock adjustment, calculation, comparison, and JSON download
- `/import` — Manual data import with progress tracking
- **Permission**: `adjustment_stock`

---

### 5. Accrual
- `/accrual` — Accrual monitoring & export

---

### 6. List Item & Pricing
- `/arc-itm-mast` — Item master list (CRUD)
- `/arc-item-price-italy` — Italy pricing management (create, manage per item)
- `/margin` — Margin configuration
- `/details-invoice-tc` — Invoice detail T&C
- **Permission**: `list_item_pricing`

---

### 7. Price Submission Approval
- `/price-submissions` — View all price submissions
- `/price-submissions/{id}` — Detail view
- Approve / Reject workflow

---

### 8. Doctors Fee
- `/doctors-fee` — Doctors fee monitoring

---

### 9. AR Tracking Insurance ⭐
- `/track` — Accounts Receivable tracking for insurance claims
- **Permission**: `track-insurance`

#### Status Flow
```
BATCHING (default, data masuk dari API/Backend)
    │
    ├──► SENT (biru) ──► RECEIVED (abu) ──► PAID (hijau) ✅
    │         │                │
    │         └── REVISE (merah) ◄──┘
    │                │
    │                └── kirim ulang → SENT
    │
    └──► CANCEL (flag, saldo tetap, auto-zero tgl 5 bulan berikutnya)
```

#### Features
- **Status Tabs**: All / Batching / Sent / Received / Revise / Paid / Cancelled
- **Filters**: Date range (invoice date), Payer, Search (patient/MRN/inv no)
- **Modal Actions**: Set Sent (VIA + Tracking# + Sent Date + Ref No), Set Received (date), Update Remarks, Cancel Invoice
- **Invoice Cancel**: Flag visual 🚫, balance preserved until 5th of next month then auto-zeroed
- **Sticky Columns**: Inv Date, Inv No, MRN, Patient Name, Ref No (fixed width)
- **Export Excel**: Per-tab sheets (SheetJS)
- **Dark Mode**: Full support

---

## 🔐 Permission System

Access is controlled via `config/roles.php` using the `CheckDataMonitoringPermission` middleware.

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

## 🎨 Design System

Custom Shadcn-inspired design (`public/css/shadcn-style.css`) with:
- CSS custom properties for all colors, spacing, shadows
- Full dark mode support via `.dark` class
- Components: buttons, cards, tables, badges, alerts, modals, dropdowns, pagination
- Typography: Inter font family

---

## ⚙️ Setup

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

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/        # All feature controllers
│   └── Middleware/          # CheckDataMonitoringPermission
config/
├── roles.php               # Role & permission definitions
public/
├── css/shadcn-style.css    # Design system
resources/views/
├── layouts/app.blade.php   # Main layout (navbar, dark mode, footer)
├── dashboard.blade.php     # Dashboard
├── track/index.blade.php   # AR Tracking Insurance
├── billing.blade.php       # Billing v1
├── billing2.blade.php      # Billing v2
├── stock.blade.php         # Stock monitoring
├── stock-management.blade.php
├── accrual.blade.php       # Accrual
├── rejected.blade.php      # Rejected claims
├── settings.blade.php      # User management
├── login.blade.php         # Auth
└── ...                     # Other module views
routes/
├── web.php                 # All routes
```

---

**Developed by IT Department — Bali International Hospital**
