{{-- Shared UI tokens for admin + client dashboards ($variant: 'admin' | 'client') --}}
@php
    $variant = $variant ?? 'admin';
    if ($variant === 'client') {
        $accent = '#1e88e5';
        $accent2 = '#42a5f5';
        $accentSoft = 'rgba(30, 136, 229, 0.12)';
        $accentGlow = 'rgba(30, 136, 229, 0.22)';
        $activeGrad = 'linear-gradient(135deg, #42a5f5, #1565c0)';
    } else {
        $accent = '#6366f1';
        $accent2 = '#8b5cf6';
        $accentSoft = 'rgba(99, 102, 241, 0.12)';
        $accentGlow = 'rgba(99, 102, 241, 0.22)';
        $activeGrad = 'linear-gradient(135deg, #6366f1, #7c3aed)';
    }
@endphp
<style>
    :root {
        --dash-accent: {{ $accent }};
        --dash-accent-2: {{ $accent2 }};
        --dash-accent-soft: {{ $accentSoft }};
        --dash-accent-glow: {{ $accentGlow }};
        --dash-active-grad: {{ $activeGrad }};
        --dash-ink: #0f172a;
        --dash-muted: #64748b;
        --dash-bg: #f1f5f9;
        --dash-surface: #ffffff;
        --dash-border: rgba(15, 23, 42, 0.08);
        --dash-border-strong: rgba(15, 23, 42, 0.12);
        /* Layered, subtle elevation (no heavy “glow blob”) */
        --dash-shadow-xs: 0 1px 2px rgba(15, 23, 42, 0.04);
        --dash-shadow-sm: 0 1px 3px rgba(15, 23, 42, 0.05), 0 6px 16px -8px rgba(15, 23, 42, 0.1);
        --dash-shadow-md: 0 2px 4px rgba(15, 23, 42, 0.04), 0 12px 28px -10px rgba(15, 23, 42, 0.14);
        --dash-radius: 12px;
        --dash-radius-lg: 14px;
        --dash-focus: 0 0 0 3px var(--dash-accent-soft);
    }

    body,
    .sidebar-area,
    .main-content,
    .main-content-container,
    .header-area,
    .card,
    .table,
    .dropdown-menu,
    .btn {
        font-family: 'Plus Jakarta Sans', system-ui, sans-serif !important;
    }

    body {
        background: var(--dash-bg) !important;
        color: var(--dash-ink);
        -webkit-font-smoothing: antialiased;
    }

    .main-content-container {
        padding: 0 18px 32px;
    }

    /* Sidebar — crisp edge, no muddy outer glow */
    .sidebar-area {
        background: #0f172a !important;
        border-right: 1px solid rgba(255, 255, 255, 0.06) !important;
        box-shadow: none !important;
    }
    .sidebar-brand {
        padding: 20px 18px !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    }
    .sidebar-brand .fw-bold {
        color: #fff !important;
        font-weight: 700 !important;
        font-size: 1.02rem;
        letter-spacing: -0.02em;
    }
    .sidebar-brand small {
        color: #94a3b8 !important;
        font-size: 0.8rem;
    }
    .sidebar-brand .logo-circle {
        width: 40px;
        height: 40px;
        border-radius: 11px;
        background: var(--dash-active-grad);
        box-shadow: 0 4px 14px -4px var(--dash-accent-glow);
    }
    .sidebar-menu-list {
        padding: 12px 10px !important;
    }
    .sidebar-menu-list-item {
        margin: 1px 0;
    }
    .sidebar-menu-link {
        color: #94a3b8 !important;
        border-radius: var(--dash-radius) !important;
        padding: 10px 14px !important;
        font-weight: 500 !important;
        font-size: 0.925rem;
        transition: background 0.15s ease, color 0.15s ease;
    }
    .sidebar-menu-link i {
        font-size: 1.12rem;
        color: #64748b;
        opacity: 0.95;
    }
    .sidebar-menu-link:hover {
        background: rgba(255, 255, 255, 0.06) !important;
        color: #f8fafc !important;
    }
    .sidebar-menu-link:hover i {
        color: var(--dash-accent-2);
    }
    .sidebar-menu-list-item.active .sidebar-menu-link {
        background: var(--dash-active-grad) !important;
        color: #fff !important;
        box-shadow: 0 4px 14px -6px var(--dash-accent-glow) !important;
    }
    .sidebar-menu-list-item.active .sidebar-menu-link i {
        color: #fff !important;
    }

    /* Top header bar */
    .header-area {
        border-radius: var(--dash-radius-lg) !important;
        border: 1px solid var(--dash-border) !important;
        background: var(--dash-surface) !important;
        box-shadow: var(--dash-shadow-xs) !important;
        padding: 10px 20px !important;
        margin-bottom: 20px !important;
    }
    .header-area h5 {
        font-weight: 600;
        letter-spacing: -0.02em;
        color: var(--dash-ink);
        font-size: 1.05rem;
    }
    .sidebar-menu-toggle-mobile {
        border: 1px solid var(--dash-border) !important;
        background: #fff !important;
        color: var(--dash-ink) !important;
    }

    /* Cards — light border + tiny lift; readable on long pages */
    .card {
        border: 1px solid var(--dash-border) !important;
        border-radius: var(--dash-radius-lg) !important;
        box-shadow: var(--dash-shadow-xs) !important;
        background: var(--dash-surface) !important;
    }
    .card-header {
        background: transparent !important;
        padding: 18px 22px 0 !important;
        border-bottom: 0 !important;
    }
    .card-body {
        padding: 20px 22px !important;
    }
    .card h5,
    .card h6 {
        font-weight: 700;
        letter-spacing: -0.01em;
        color: var(--dash-ink);
    }

    /* Stat cards */
    .stat-card {
        border: 1px solid var(--dash-border) !important;
        border-radius: var(--dash-radius-lg) !important;
        box-shadow: var(--dash-shadow-xs) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--dash-shadow-sm) !important;
    }
    .stat-card .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: #fff;
        box-shadow: 0 4px 12px -4px currentColor;
    }

    .bg-grad-pink { background: linear-gradient(135deg, #ec4899, #db2777); }
    .bg-grad-blue { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
    .bg-grad-green { background: linear-gradient(135deg, #10b981, #047857); }
    .bg-grad-orange { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .bg-grad-purple { background: linear-gradient(135deg, #8b5cf6, #6d28d9); }
    .bg-grad-red { background: linear-gradient(135deg, #ef4444, #b91c1c); }

    /* Badges */
    .badge-soft-success { background: #d1fae5 !important; color: #047857 !important; font-weight: 600; border-radius: 8px; padding: 5px 10px; }
    .badge-soft-danger { background: #fee2e2 !important; color: #b91c1c !important; font-weight: 600; border-radius: 8px; padding: 5px 10px; }
    .badge-soft-warning { background: #fef3c7 !important; color: #b45309 !important; font-weight: 600; border-radius: 8px; padding: 5px 10px; }
    .badge-soft-info { background: #dbeafe !important; color: #1d4ed8 !important; font-weight: 600; border-radius: 8px; padding: 5px 10px; }

    /* Buttons */
    .btn {
        border-radius: var(--dash-radius) !important;
        font-weight: 600;
        padding: 0.5rem 1rem;
        transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
    }
    .btn-primary {
        background: var(--dash-active-grad) !important;
        border: none !important;
        color: #fff !important;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06), 0 4px 14px -4px var(--dash-accent-glow) !important;
    }
    .btn-primary:hover,
    .btn-primary:focus {
        color: #fff !important;
        filter: brightness(1.03);
        box-shadow: 0 2px 4px rgba(15, 23, 42, 0.06), 0 8px 20px -6px var(--dash-accent-glow) !important;
    }
    .btn-primary:active {
        transform: translateY(0);
    }
    .btn-outline-primary {
        border: 1.5px solid var(--dash-accent) !important;
        color: var(--dash-accent) !important;
        background: transparent !important;
        box-shadow: none !important;
    }
    .btn-outline-primary:hover {
        background: var(--dash-accent) !important;
        color: #fff !important;
    }
    .btn-light {
        background: #fff !important;
        border: 1px solid var(--dash-border) !important;
        color: var(--dash-ink) !important;
        box-shadow: var(--dash-shadow-xs) !important;
    }
    .btn-sm {
        padding: 0.35rem 0.75rem !important;
        font-size: 0.875rem;
    }

    /* Tables */
    .table {
        --bs-table-hover-bg: #f8fafc;
    }
    .table thead th {
        font-weight: 600;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--dash-muted);
        border-bottom: 1px solid var(--dash-border-strong) !important;
        padding: 12px 16px !important;
        background: #f8fafc !important;
    }
    .table tbody td {
        padding: 14px 16px !important;
        vertical-align: middle;
        border-color: var(--dash-border) !important;
        font-size: 0.925rem;
    }
    .table-responsive {
        border-radius: var(--dash-radius);
    }

    /* Forms — calmer borders, clear focus, no harsh shadow */
    .form-label {
        font-weight: 600;
        font-size: 0.875rem;
        margin-bottom: 6px;
        color: #334155;
    }
    .form-control,
    .form-select {
        border: 1px solid var(--dash-border-strong) !important;
        border-radius: var(--dash-radius) !important;
        padding: 0.55rem 0.875rem !important;
        font-size: 0.925rem;
        background-color: #fff !important;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .form-control::placeholder {
        color: #94a3b8;
        opacity: 1;
    }
    textarea.form-control {
        min-height: 110px;
        resize: vertical;
    }
    .form-control:hover,
    .form-select:hover {
        border-color: rgba(15, 23, 42, 0.18) !important;
    }
    .form-control:focus,
    .form-select:focus {
        border-color: var(--dash-accent) !important;
        box-shadow: var(--dash-focus) !important;
        outline: none !important;
    }
    .form-control.is-invalid,
    .form-select.is-invalid {
        border-color: #dc2626 !important;
    }
    .form-control.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.12) !important;
    }
    .invalid-feedback,
    .valid-feedback {
        font-size: 0.8125rem;
        margin-top: 4px;
    }
    .form-text {
        color: var(--dash-muted);
        font-size: 0.8125rem;
    }
    .input-group-text {
        border: 1px solid var(--dash-border-strong) !important;
        background: #f8fafc !important;
        color: var(--dash-muted);
        border-radius: var(--dash-radius) !important;
        font-size: 0.875rem;
    }
    .input-group > .form-control:not(:first-child),
    .input-group > .form-select:not(:first-child) {
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
    }
    .input-group > .form-control:not(:last-child),
    .input-group > .form-select:not(:last-child) {
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
    }
    .form-check-input {
        width: 1.05em;
        height: 1.05em;
        border: 1px solid var(--dash-border-strong);
    }
    .form-check-input:focus {
        box-shadow: var(--dash-focus);
        border-color: var(--dash-accent);
    }
    .form-check-input:checked {
        background-color: var(--dash-accent);
        border-color: var(--dash-accent);
    }

    /* Alerts */
    .alert {
        border: 1px solid transparent !important;
        border-radius: var(--dash-radius) !important;
        padding: 0.875rem 1rem !important;
        font-size: 0.925rem;
    }
    .alert-success {
        background: #ecfdf5 !important;
        color: #047857 !important;
        border-color: #a7f3d0 !important;
    }
    .alert-danger {
        background: #fef2f2 !important;
        color: #b91c1c !important;
        border-color: #fecaca !important;
    }
    .alert-warning {
        background: #fffbeb !important;
        color: #b45309 !important;
        border-color: #fde68a !important;
    }
    .alert-info {
        background: #eff6ff !important;
        color: #1d4ed8 !important;
        border-color: #bfdbfe !important;
    }

    /* Dropdown */
    .dropdown-menu {
        border: 1px solid var(--dash-border) !important;
        border-radius: var(--dash-radius) !important;
        box-shadow: var(--dash-shadow-md) !important;
        padding: 6px;
        margin-top: 8px !important;
    }
    .dropdown-item {
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 0.9rem;
        font-weight: 500;
    }
    .dropdown-item:hover {
        background: #f1f5f9;
    }
    .dropdown-item:active {
        background: var(--dash-accent-soft);
        color: var(--dash-ink);
    }

    /* Page chrome */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 22px;
        flex-wrap: wrap;
        gap: 12px;
    }
    .page-header h2 {
        font-weight: 800;
        letter-spacing: -0.02em;
        font-size: 1.5rem;
        margin: 0;
        color: var(--dash-ink);
        line-height: 1.2;
    }
    .page-header .sub {
        color: var(--dash-muted);
        font-size: 0.9rem;
        margin-top: 4px;
        max-width: 42rem;
    }

    /* Pagination */
    .page-link {
        border: 1px solid var(--dash-border) !important;
        border-radius: 8px !important;
        margin: 0 2px;
        color: #475569;
        font-weight: 600;
        padding: 0.4rem 0.75rem;
        font-size: 0.875rem;
    }
    .page-item.active .page-link {
        background: var(--dash-accent) !important;
        border-color: var(--dash-accent) !important;
        color: #fff !important;
    }

    /* User chip in header — match theme */
    .header-area .rounded-circle.bg-primary {
        background: var(--dash-active-grad) !important;
    }

    /* Softer than Bootstrap default shadow utilities on inner cards */
    .main-content .shadow-sm {
        box-shadow: var(--dash-shadow-xs) !important;
    }

    .table-light {
        --bs-table-bg: #f8fafc;
    }
</style>
