# LocalBiz SaaS — Multi-Tenant Platform for Local Businesses

A production-ready Laravel 12 Multi-Tenant SaaS starter that lets any local business (boutique, furniture shop, gift store, service provider, etc.) get their own website + order + enquiry system under one subscription.

- **Architecture:** Single-database multi-tenancy via `tenant_id` column + global scope
- **Roles:** Super Admin (platform owner) & Client (tenant/business owner)
- **Stack:** Laravel 12, Bootstrap 5 (existing admin theme), Blade, MySQL, session cart
- **Themes:** 3 pluggable storefront themes — `boutique`, `furniture`, `service` (each tenant picks one)

---

## 1. Setup (Windows / XAMPP)

```bash
# 1. Make sure your .env points to the new DB
# DB_DATABASE=localbiz_saas   (already set)

# 2. Install deps (already done in your workspace)
composer install

# 3. Generate app key if missing
php artisan key:generate

# 4. Run migrations + seed demo data
php artisan migrate:fresh --seed

# 5. Link storage (for product image uploads)
php artisan storage:link

# 6. Start the dev server
php artisan serve
# → http://127.0.0.1:8000
```

> If you ever see `Unknown database 'localbiz_saas'`, create it once in phpMyAdmin or run:
> `mysql -u root -e "CREATE DATABASE localbiz_saas"`.

---

## 2. Demo Credentials

| Role | URL | Email | Password |
|---|---|---|---|
| Super Admin | `/login` → `/admin` | `admin@localbiz.test` | `password` |
| Client (Boutique) | `/login` → `/client` | `owner@saanviboutique.test` | `password` |
| Client (Furniture) | `/login` → `/client` | `owner@royalwood.test` | `password` |

Public tenant websites (no login needed):

- Boutique theme → http://127.0.0.1:8000/saanvi-boutique
- Furniture theme → http://127.0.0.1:8000/royal-wood-crafts

---

## 3. Project Map

### Routes (`routes/web.php`)
| Prefix | Middleware | Purpose |
|---|---|---|
| `/` | — | Public landing page with plans + featured tenants |
| `/login`, `/register` | guest | Auth (register creates a new tenant + client user) |
| `/admin/*` | `auth`, `saas.admin` | Super Admin panel |
| `/client/*` | `auth`, `saas.client` (plan-expiry aware) | Tenant dashboard |
| `/{slug}`, `/{slug}/products`, `/{slug}/cart`, `/{slug}/checkout` … | `saas.tenant` | Dynamic tenant storefront |

Reserved slugs (`admin`, `client`, `login`, `logout`, `register`, `landing`, `dashboard`, `up`, `storage`) cannot be used as tenant slugs.

### Key code
```
app/
├── Http/
│   ├── Controllers/Saas/
│   │   ├── Auth/ (Login, Register)
│   │   ├── Admin/ (Dashboard, Tenant, Plan, Order, Enquiry)
│   │   ├── Client/ (Dashboard, Product, Order, Enquiry, Page, Settings)
│   │   ├── Frontend/ (Website, Cart, Checkout, Enquiry)
│   │   └── LandingController.php
│   └── Middleware/
│       ├── EnsureUserIsAdmin.php
│       ├── EnsureUserIsClient.php      ← checks tenant active + plan not expired
│       └── ResolveTenantBySlug.php     ← injects {slug} tenant into request
├── Models/ (User, Tenant, Plan, Product, Order, OrderItem, Enquiry, Page)
└── Traits/BelongsToTenant.php          ← global scope + auto-fills tenant_id

resources/views/saas/
├── layouts/ (admin.blade.php, client.blade.php)
├── partials/ (topbar, admin-sidebar, client-sidebar)
├── auth/ (login, register)
├── admin/ (dashboard, tenants, plans, orders, enquiries)
├── client/ (dashboard, products, orders, enquiries, pages, settings)
├── themes/
│   ├── _shared/ (layout, product_card, enquiry_section)
│   ├── boutique/ (home, products, product, about, contact, cart, checkout, success)
│   ├── furniture/ (home — falls back to boutique for other pages)
│   └── service/ (home — falls back to boutique for other pages)
└── landing.blade.php

database/
├── migrations/         (8 SaaS migrations: 2026_04_23_1000xx_*)
└── seeders/SaasSeeder.php
```

---

## 4. Features Checklist

### Super Admin (`/admin`)
- [x] Dashboard with revenue, tenant, order & enquiry KPIs
- [x] Full CRUD for Tenants (create/edit/delete, activate/deactivate, extend expiry)
- [x] Full CRUD for Subscription Plans
- [x] View every order across every tenant
- [x] View every enquiry across every tenant

### Client Panel (`/client`)
- [x] KPI dashboard (products, orders today/total, enquiries, revenue)
- [x] Product CRUD with image upload + stock + featured flag
- [x] View & update order status
- [x] View, reply (status) and delete enquiries
- [x] Edit Home / About / Contact page content (WYSIWYG-ready rich text area)
- [x] Business settings (logo, colors, WhatsApp, theme switcher)
- [x] Plan-expiry middleware — auto-locks dashboard when `expiry_date < today`

### Public Tenant Website (`/{slug}`)
- [x] Home, Products, Product detail, About, Contact
- [x] 3 theme variants (boutique / furniture / service), auto-fallback to boutique
- [x] Tenant primary-color applied via CSS variable (`--tenant-primary`)
- [x] Floating WhatsApp button (pre-fills tenant number + greeting)
- [x] Enquiry form → saves to `enquiries` table
- [x] Session-based cart (add / update qty / remove / clear)
- [x] Checkout with COD + Razorpay placeholder (payment_method = `online` saved as `pending`)
- [x] Order success page + auto-generated WhatsApp order notification link for the owner

### Subscription System
- [x] `plans` table with price, duration_days, max_products, JSON features
- [x] `tenants.expiry_date` — middleware blocks client dashboard & returns 503 on public site when expired
- [x] Super admin can extend expiry with one click

---

## 5. How the Multi-Tenancy Works

1. **Every tenant-scoped model** (`Product`, `Order`, `Enquiry`, `Page`) uses `App\Traits\BelongsToTenant`.
2. The trait:
   - Adds a **global scope** so clients only ever see their own rows.
   - **Auto-fills** `tenant_id` on create for logged-in clients.
   - Is **bypassed for admins** — super admin sees all.
3. Public storefronts use `saas.tenant` middleware which resolves the tenant by slug and injects it into the request, blade views, and controllers.

---

## 6. Adding a New Theme

1. Create `resources/views/saas/themes/my-theme/home.blade.php` (and optionally `products.blade.php`, `product.blade.php`, etc.)
2. Add `my-theme` as an option in `resources/views/saas/client/settings/edit.blade.php` theme dropdown.
3. A tenant picks it in **Client → Settings → Theme**.
4. The `WebsiteController` automatically resolves `saas.themes.{theme}.{view}` and falls back to `saas.themes.boutique.{view}` if your theme doesn't override that page.

---

## 7. Notifications (WhatsApp)

On order placement, the success page renders a pre-built `https://wa.me/<owner-number>?text=...` link containing the order summary. Click once → WhatsApp opens with the message ready to send to the business owner. No paid API required for MVP. Later you can swap this for Twilio / Meta Cloud API by editing `CheckoutController::place()`.

---

## 8. Useful Artisan Commands

```bash
# Reset + reseed demo data (destroys all data)
php artisan migrate:fresh --seed

# Clear caches after editing routes/views/config
php artisan optimize:clear

# List all routes
php artisan route:list
```

---

## 9. Roadmap / Nice-to-haves

- [ ] Custom domain mapping per tenant
- [ ] Stripe / Razorpay webhook handlers for real online payments
- [ ] Email + SMS (MSG91) on new order
- [ ] Tenant-scoped media library
- [ ] Per-tenant SEO meta & sitemap.xml
- [ ] Admin-side impersonate client button
- [ ] 2FA for super admin

---

Built with ❤️ in Jaipur. Ready to onboard your first 10 local businesses this weekend.
