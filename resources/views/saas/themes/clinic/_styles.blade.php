<style>
.clinic-theme{font-family:'Outfit',system-ui,sans-serif;}
.clinic-theme .display-round{font-weight:700;letter-spacing:-.03em;line-height:1.1;}

.clinic-hero{position:relative;padding:100px 0 80px;background:linear-gradient(165deg,#f0fdfa 0%,#ecfeff 35%,#fff 70%);overflow:hidden;}
.clinic-hero::after{content:'';position:absolute;width:400px;height:400px;border-radius:50%;background:radial-gradient(circle,rgba(20,184,166,.15),transparent 70%);top:-100px;right:-80px;pointer-events:none;}
.clinic-hero::before{content:'';position:absolute;width:300px;height:300px;border-radius:50%;background:radial-gradient(circle,rgba(59,130,246,.08),transparent 70%);bottom:0;left:-60px;}
.clinic-hero h1{font-size:clamp(2.4rem,5vw,3.6rem);}
.clinic-hero-lead{color:#475569;font-size:1.1rem;max-width:460px;line-height:1.75;}
.clinic-pill-row{display:flex;flex-wrap:wrap;gap:10px;margin-top:2rem;}
.clinic-pill{padding:10px 18px;border-radius:999px;background:#fff;border:2px solid #e2e8f0;font-weight:600;font-size:.88rem;color:#0f172a;text-decoration:none;transition:.2s;}
.clinic-pill:hover{border-color:var(--brand);color:var(--brand);box-shadow:0 8px 24px -8px rgba(20,184,166,.25);}
.clinic-pill i{margin-right:8px;color:var(--brand);}

.clinic-appt-card{background:#fff;border-radius:24px;padding:28px;box-shadow:0 32px 64px -24px rgba(15,23,42,.12);border:1px solid #e2e8f0;position:relative;z-index:2;}
.clinic-appt-card .head{display:flex;align-items:center;gap:12px;margin-bottom:20px;padding-bottom:16px;border-bottom:1px dashed #e2e8f0;}
.clinic-appt-card .avatar{width:52px;height:52px;border-radius:16px;background:linear-gradient(135deg,var(--brand),#0d9488);color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.4rem;}
.clinic-slot{display:flex;justify-content:space-between;align-items:center;padding:12px 14px;border-radius:12px;background:#f0fdfa;margin-bottom:8px;font-size:.9rem;}
.clinic-slot strong{color:var(--brand);}
.clinic-trust{display:flex;gap:24px;margin-top:2.5rem;flex-wrap:wrap;}
.clinic-trust div{display:flex;align-items:center;gap:10px;font-size:.9rem;font-weight:600;color:#334155;}
.clinic-trust i{width:36px;height:36px;border-radius:10px;background:#ccfbf1;color:#0d9488;display:flex;align-items:center;justify-content:center;}

.clinic-section{padding:80px 0;}
.clinic-section-alt{background:linear-gradient(180deg,#fff,#f8fafc);}
.clinic-section-title{font-size:clamp(1.8rem,3.5vw,2.4rem);font-weight:700;margin-bottom:.25rem;}
.clinic-specialty-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:16px;}
.clinic-spec-tile{text-decoration:none;color:inherit;background:#fff;border-radius:20px;padding:24px 20px;border:1px solid #e2e8f0;text-align:center;transition:.25s;}
.clinic-spec-tile:hover{transform:translateY(-4px);box-shadow:0 20px 40px -16px rgba(20,184,166,.2);border-color:#99f6e4;}
.clinic-spec-tile .ico{width:56px;height:56px;margin:0 auto 14px;border-radius:18px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;}
.clinic-spec-tile .ico.g{background:#ecfdf5;color:#059669;}
.clinic-spec-tile .ico.d{background:#eff6ff;color:#2563eb;}
.clinic-spec-tile .ico.p{background:#fdf4ff;color:#a855f7;}
.clinic-spec-tile .ico.c{background:#fff7ed;color:#ea580c;}
.clinic-spec-tile strong{font-size:.95rem;}

.clinic-steps{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:24px;margin-top:2rem;}
.clinic-step{position:relative;padding:24px;background:#fff;border-radius:16px;border:1px solid #e2e8f0;}
.clinic-step-num{width:32px;height:32px;border-radius:10px;background:var(--brand);color:#fff;font-weight:800;font-size:.85rem;display:flex;align-items:center;justify-content:center;margin-bottom:14px;}

/* Service cards */
.clinic-card{border-radius:20px;background:#fff;border:1px solid #e2e8f0;overflow:hidden;display:flex;flex-direction:column;height:100%;transition:.3s;border-left:4px solid var(--brand);}
.clinic-card:hover{box-shadow:0 24px 48px -20px rgba(15,23,42,.12);transform:translateY(-4px);}
.clinic-card-top{padding:22px 22px 0;flex-grow:1;}
.clinic-card-badge{display:inline-flex;padding:5px 12px;border-radius:8px;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em;background:#ecfdf5;color:#0d9488;margin-bottom:12px;}
.clinic-card-badge.online{background:#eff6ff;color:#2563eb;}
.clinic-card-title{font-size:1.15rem;font-weight:700;margin:0 0 8px;line-height:1.35;}
.clinic-card-title a{color:#0f172a;text-decoration:none;}
.clinic-card-meta{font-size:.85rem;color:#64748b;display:flex;flex-wrap:wrap;gap:12px;margin-bottom:12px;}
.clinic-card-meta span{display:inline-flex;align-items:center;gap:6px;}
.clinic-card-foot{padding:18px 22px;background:#f8fafc;border-top:1px solid #f1f5f9;display:flex;justify-content:space-between;align-items:center;}
.clinic-card-price{font-size:1.35rem;font-weight:800;color:var(--brand);}
.clinic-card-price small{display:block;font-size:.72rem;color:#94a3b8;font-weight:500;}
.clinic-card-book{border:0;background:var(--brand);color:#fff;width:44px;height:44px;border-radius:12px;font-size:1rem;cursor:pointer;transition:.2s;}
.clinic-card-book:hover{filter:brightness(1.1);transform:scale(1.05);}

.clinic-filter-panel{background:#fff;border-radius:20px;padding:24px;border:1px solid #e2e8f0;box-shadow:0 12px 32px -16px rgba(15,23,42,.06);margin-bottom:2rem;}
.clinic-filter-panel .title{font-weight:700;font-size:1.1rem;margin-bottom:16px;display:flex;align-items:center;gap:10px;}
.clinic-filter-panel .title i{color:var(--brand);}
.clinic-filter-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:12px;align-items:end;}
.clinic-filter-grid label{font-size:.72rem;font-weight:700;text-transform:uppercase;color:#64748b;margin-bottom:4px;display:block;}
.clinic-filter-grid input,.clinic-filter-grid select{width:100%;border-radius:10px;border:1px solid #e2e8f0;padding:10px 12px;}
.clinic-filter-actions{display:flex;gap:8px;margin-top:16px;}

.clinic-page-head{padding:56px 0 40px;background:linear-gradient(135deg,#f0fdfa,#ecfeff);}
.clinic-page-head h1{font-size:clamp(2rem,4vw,2.8rem);}
.clinic-empty{text-align:center;padding:72px 24px;background:#f0fdfa;border-radius:24px;}
.clinic-detail-layout{display:grid;grid-template-columns:340px 1fr;gap:40px;align-items:start;}
@media(max-width:991px){.clinic-detail-layout{grid-template-columns:1fr;}}
.clinic-detail-side{background:linear-gradient(180deg,var(--brand),#0d9488);color:#fff;border-radius:24px;padding:32px;text-align:center;}
.clinic-detail-side .fee{font-size:2.5rem;font-weight:800;margin:16px 0;}
.clinic-detail-facts{list-style:none;padding:0;margin:24px 0 0;}
.clinic-detail-facts li{display:flex;align-items:center;gap:12px;padding:12px 0;border-top:1px solid rgba(255,255,255,.2);font-size:.95rem;}
</style>
