# Project Notes

## Scope
H-Mart ecommerce site (PHP + MySQL). Tasks completed: AI Shopper, login fix, product add fix, admin orders fix, mobile responsive, seed images fix, INR currency.

---

## All File Changes Made

### 1. AI Shopper — Image Upload
- `frontend/aishopper.php`:
  - `handleFileUpload()` reads images as base64 via FileReader, stores in `attachedImages[]`
  - `sendMessage()` includes `images` array in request body, clears after send
  - Model forced to `meta/llama-3.2-11b-vision-instruct` at send-time when images attached (`finalModel`)
- `frontend/api_chat.php`:
  - Accepts `images` array, builds content array (`text` + `image_url`) for NVIDIA vision API
  - **Retry logic**: if vision model fails with "does not support image input" (200 OK), retries without images using text-only model `meta/llama-3.1-8b-instruct`
  - PHP error handler catches fatals, returns JSON
  - `post_max_size`, `upload_max_filesize`, `memory_limit`, `max_execution_time` increased
  - Frontend error display improved (shows HTTP status + response text)

### 2. Login Fix
- `backend/customer/controller.php`: Changed `TERMS = 0` to `TERMS = 1` (auto-verify)
- `backend/include/customers.php`: Removed `TERMS === 0` unverified block from `cusAuthentication()`
- `frontend/login.php`: Removed `'unverified'` error handler

### 3. Product Add Fix
- `admin/products/controller.php`: Added `INGREDIENTS` property to `doInsert()`/`doEdit()`
- `admin/products/add.php`, `admin/products/edit.php`: Added INGREDIENTS textarea field
- `backend/include/products.php`, `backend/include/promos.php`: Removed debug `echo` in `create()`

### 4. Admin Orders Fix
- `admin/orders/addorder.php`, `addtocart.php`, `billing.php`, `orderdetails.php`, `orderedproduct.php`: Changed `tblproducts` → `tblproduct` (wrong table name)
- `admin/orders/controller.php`: Changed `CLAIMEDADTE` → `CLAIMEDDATE` (typo)

### 5. Mobile Responsive
- **New file**: `css/mobile-responsive.css` — rules for all pages down to 320px width
- `frontend/theme/templates.php`: Added hamburger menu HTML, slide-out panel, overlay, toggle JS
- `admin/theme/templates.php`: Added sidebar overlay for mobile, updated `toggleAdminSidebar()` for off-canvas behavior, removed duplicate function, linked CSS

### 6. Seed Images — Duplicate Fix
- `backend/database/seed_products.php`: Changed from `$combos[$i % count($combos)]` to `mt_srand($product_idx)` with continuous random brightness/contrast values. Each product gets a unique variant — no more collisions.
- `backend/db_migration.py`: Changed from `product_idx % len(combos)` to `hashlib.md5()` hash

### 7. Currency — INR
- `backend/include/function.php`: Changed default from `'PHP'` to `'INR'`, fallback symbol to `₹`
- **27 files**: Replaced all `&#8369;` (PHP Peso) and `&#8377;` (old INR entity) with `₹` character

---

## Pending / To Ask

When you open a new chat, tell the AI:
> "Read backend/NOTES.md and continue where we left off"

Then it will ask you:
1. Did you re-run the migration? Any duplicate images remaining?
2. Does AI Shopper still show the image error?
3. Did you upload all files to InfinityFree?
4. Anything else you want fixed or changed.

---

## Upload Checklist (InfinityFree)

All modified files. If you only want the essentials:

| Priority | File |
|----------|------|
| HIGH | `frontend/aishopper.php` |
| HIGH | `frontend/api_chat.php` |
| HIGH | `css/mobile-responsive.css` |
| HIGH | `frontend/theme/templates.php` |
| HIGH | `admin/theme/templates.php` |
| HIGH | `backend/database/seed_products.php` |
| HIGH | `backend/include/function.php` |
| HIGH | `backend/customer/controller.php` |
| HIGH | `backend/include/customers.php` |
| HIGH | `frontend/login.php` |
| MEDIUM | `admin/products/controller.php` |
| MEDIUM | `admin/products/add.php` |
| MEDIUM | `admin/products/edit.php` |
| MEDIUM | `backend/include/products.php` |
| MEDIUM | `backend/include/promos.php` |
| MEDIUM | `admin/orders/controller.php` |
| MEDIUM | `admin/orders/addorder.php` |
| MEDIUM | `admin/orders/addtocart.php` |
| MEDIUM | `admin/orders/billing.php` |
| MEDIUM | `admin/orders/orderdetails.php` |
| MEDIUM | `admin/orders/orderedproduct.php` |
| LOW | `backend/db_migration.py` |
| LOW | All 27 files with ₹ symbol changes |

---

## Key Context
- NVIDIA API key may not support vision model (`meta/llama-3.2-11b-vision-instruct`) — API returns 200 OK with text error, not HTTP error
- All frontend product listing queries filter `PROQTY > 0`
- `web_root` on frontend pages includes `frontend/` suffix; on admin pages it does not
- Admin sidebar toggle: mobile = off-canvas overlay, desktop = collapse
- Migration URL: `http://localhost/ecommerce-main/ecommerce-main/backend/database/seed_products.php?run=1`
