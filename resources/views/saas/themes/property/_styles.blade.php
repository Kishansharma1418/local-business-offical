<style>
.prop-theme{font-family:'Plus Jakarta Sans',system-ui,sans-serif;}
.prop-theme .display-serif{font-family:'DM Serif Display',serif;font-weight:400;}
.prop-page-hero{background:#0c1222;color:#fff;padding:56px 0 48px;}
.prop-page-hero h1{font-size:clamp(2rem,4vw,3rem);margin:.5rem 0;}
.prop-page-lead{color:#94a3b8;margin:0;}
.prop-breadcrumb{font-size:.85rem;margin-bottom:1rem;}
.prop-breadcrumb a{color:#6ee7b7;text-decoration:none;}
.prop-breadcrumb span{color:#64748b;margin:0 8px;}
.prop-results-count{color:#64748b;margin-bottom:1.5rem;font-size:.95rem;}
.prop-empty{text-align:center;padding:80px 24px;background:#f8fafc;border-radius:24px;}
.prop-empty-icon{width:80px;height:80px;margin:0 auto 20px;border-radius:50%;background:#ecfdf5;color:var(--brand);display:flex;align-items:center;justify-content:center;font-size:2rem;}
.prop-btn-primary{background:linear-gradient(135deg,#059669,#10b981);color:#fff;border:0;padding:14px 28px;border-radius:12px;font-weight:700;text-decoration:none;display:inline-flex;}
.prop-card{border-radius:18px;overflow:hidden;background:#fff;border:1px solid #e2e8f0;transition:transform .3s,box-shadow .3s;}
.prop-card:hover{transform:translateY(-8px);box-shadow:0 28px 56px -20px rgba(15,23,42,.18);}
.prop-card-media{display:block;position:relative;aspect-ratio:4/3;overflow:hidden;text-decoration:none;}
.prop-card-media img{width:100%;height:100%;object-fit:cover;transition:transform .5s;}
.prop-card:hover .prop-card-media img{transform:scale(1.06);}
.prop-card-placeholder{width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#ecfdf5,#d1fae5);font-size:3.5rem;color:var(--brand);opacity:.4;}
.prop-card-overlay{position:absolute;inset:0;background:linear-gradient(180deg,transparent 40%,rgba(15,23,42,.75) 100%);}
.prop-tag{position:absolute;top:14px;left:14px;padding:6px 12px;border-radius:8px;font-size:.72rem;font-weight:800;text-transform:uppercase;letter-spacing:.06em;}
.prop-tag-sale{background:#059669;color:#fff;}
.prop-tag-rent{background:#2563eb;color:#fff;}
.prop-tag-featured{left:auto;right:14px;background:#f59e0b;color:#fff;}
.prop-card-price{position:absolute;bottom:14px;left:14px;color:#fff;}
.prop-card-price .amount{font-size:1.35rem;font-weight:800;}
.prop-card-price .per{font-size:.75rem;opacity:.9;}
.prop-card-body{padding:20px 22px 22px;}
.prop-card-title{font-family:'DM Serif Display',serif;font-size:1.2rem;margin:0 0 12px;}
.prop-card-title a{color:#0f172a;text-decoration:none;}
.prop-card-meta{display:flex;flex-wrap:wrap;gap:8px 16px;font-size:.82rem;color:#64748b;}
.prop-card-meta span{display:inline-flex;align-items:center;gap:5px;}
.prop-card-meta i{color:var(--brand);font-size:.75rem;}
.prop-card-action{margin-top:16px;}
.prop-card-action button{width:100%;border:0;background:#0f172a;color:#fff;padding:12px;border-radius:10px;font-weight:600;}
.prop-card-action button:hover{background:var(--brand);}
.prop-filter-panel{background:#fff;border-radius:20px;border:1px solid #e2e8f0;padding:24px 28px;box-shadow:0 16px 40px -20px rgba(15,23,42,.08);}
.prop-filter-head{display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:16px;margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid #f1f5f9;}
.prop-filter-label{font-family:'DM Serif Display',serif;font-size:1.35rem;}
.prop-filter-sub{font-size:.85rem;color:#64748b;}
.prop-filter-submit{background:#0f172a;color:#fff;border:0;padding:10px 22px;border-radius:10px;font-weight:700;}
.prop-filter-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:14px;}
.prop-filter-field label{display:block;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#64748b;margin-bottom:6px;}
.prop-filter-field input,.prop-filter-field select{width:100%;border:1px solid #e2e8f0;border-radius:10px;padding:10px 12px;font-size:.9rem;}
.prop-filter-field-wide{grid-column:1/-1;}
@media(min-width:768px){.prop-filter-field-wide{grid-column:span 2;}}
.prop-filter-clear{display:inline-block;margin-top:14px;font-size:.85rem;color:var(--brand);font-weight:600;}
.prop-detail-hero{background:#0c1222;color:#fff;padding:32px 0;}
.prop-detail-grid{display:grid;grid-template-columns:1fr 1fr;gap:40px;align-items:start;}
@media(max-width:991px){.prop-detail-grid{grid-template-columns:1fr;}}
.prop-detail-gallery{border-radius:20px;overflow:hidden;aspect-ratio:4/3;background:#1e293b;}
.prop-detail-gallery img{width:100%;height:100%;object-fit:cover;}
.prop-detail-specs{display:grid;grid-template-columns:repeat(2,1fr);gap:12px;margin:24px 0;}
.prop-spec{background:#f8fafc;border-radius:12px;padding:16px;border:1px solid #e2e8f0;}
.prop-spec .k{font-size:.72rem;text-transform:uppercase;color:#64748b;font-weight:700;}
.prop-spec .v{font-size:1.1rem;font-weight:700;color:#0f172a;margin-top:4px;}
.prop-detail-price{font-family:'DM Serif Display',serif;font-size:2.5rem;color:var(--brand);}
</style>
