
<style>
        :root {
            --accent:#2d3a4c;--accent-light:#80beb5;--accent-dark:#1d4ed8;
            --surface:#fff;--surface-2:#f8fafc;--surface-3:#f1f5f9;
            --border:#e2e8f0;--border-strong:#cbd5e1;
            --text-primary:#0f172a;--text-secondary:#475569;--text-muted:#94a3b8;
            --green:#16a34a;--green-light:#dcfce7;
            --red:#dc2626;--red-light:#fee2e2;
            --amber:#d97706;--amber-light:#fef3c7;
            --radius:8px;--radius-lg:12px;
            --shadow-sm:0 1px 3px rgba(0,0,0,.06);
            --shadow-md:0 4px 12px rgba(0,0,0,.08);
            --shadow-lg:0 10px 30px rgba(0,0,0,.12),0 4px 6px rgba(0,0,0,.05);
        }
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'DM Sans',sans-serif;background:var(--surface-2);color:var(--text-primary);-webkit-font-smoothing:antialiased;}

        /* ── BREADCRUMB ── */
        .rp-breadcrumb{background:var(--surface);border-bottom:1px solid var(--border);padding:11px 0;}
        .rp-breadcrumb nav{display:flex;align-items:center;gap:6px;font-size:13px;color:var(--text-secondary);flex-wrap:wrap;}
        .rp-breadcrumb a{color:var(--text-secondary);text-decoration:none;transition:color .15s;}
        .rp-breadcrumb a:hover{color:var(--accent);}
        .rp-breadcrumb .sep{color:var(--text-muted);}
        .rp-breadcrumb .current{color:var(--text-primary);font-weight:500;}

        /* ── SUB-CAT STRIP ── */
        .subcategory-strip{background:var(--surface);border-bottom:1px solid var(--border);}
        .subcat-inner{display:flex;align-items:center;overflow-x:auto;scrollbar-width:none;}
        .subcat-inner::-webkit-scrollbar{display:none;}
        .subcat-tab{flex-shrink:0;padding:13px 18px;font-size:13.5px;font-weight:500;color:var(--text-secondary);text-decoration:none;border-bottom:2px solid transparent;white-space:nowrap;transition:all .2s;}
        .subcat-tab:hover{color:var(--accent);background:var(--accent-light);}
        .subcat-tab.active{color:var(--accent);border-bottom-color:var(--accent);}
        .subcat-scroll-btn{flex-shrink:0;width:32px;height:32px;border:1px solid var(--border);background:var(--surface);border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;color:var(--text-secondary);transition:all .2s;margin:0 6px;}
        .subcat-scroll-btn:hover{background:var(--surface-3);color:var(--accent);}

        /* ── LAYOUT ── */
        .shop-layout{display:grid;grid-template-columns:268px 1fr;gap:22px;padding:22px 0 60px;align-items:start;}

        /* ── SIDEBAR ── */
        .shop-sidebar{position:sticky;top:16px;}
        .sidebar-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-sm);margin-bottom:14px;}

        /* ── CATEGORY SEARCH BOX ── */
        .cat-search-wrap{padding:14px;border-bottom:1px solid var(--border);position:relative;}
        .cat-search-label{font-size:10.5px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.07em;margin-bottom:8px;display:block;}
        .cat-search-input-row{position:relative;}
        .cat-search-input-row i.search-icon{position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:13px;pointer-events:none;z-index:1;}
        .cat-search-input{width:100%;padding:9px 36px 9px 32px;border:1.5px solid var(--border);border-radius:var(--radius);font-size:13px;color:var(--text-primary);font-family:inherit;background:var(--surface-2);transition:border-color .2s,box-shadow .2s;outline:none;}
        .cat-search-input:focus{border-color:var(--accent);background:var(--surface);box-shadow:0 0 0 3px rgba(37,99,235,.08);}
        .cat-search-clear{position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--text-muted);font-size:14px;padding:0;display:none;line-height:1;}
        .cat-search-clear:hover{color:var(--text-primary);}

        /* ── DROPDOWN RESULTS ── */
        .cat-dropdown{position:absolute;left:14px;right:14px;top:calc(100% - 2px);background:var(--surface);border:1.5px solid var(--accent);border-top:none;border-radius:0 0 var(--radius) var(--radius);box-shadow:var(--shadow-lg);z-index:200;max-height:280px;overflow-y:auto;display:none;}
        .cat-dropdown.open{display:block;}
        .cat-dropdown-item{display:flex;align-items:center;gap:10px;padding:10px 14px;cursor:pointer;border-bottom:1px solid var(--border);transition:background .15s;text-decoration:none;}
        .cat-dropdown-item:last-child{border-bottom:none;}
        .cat-dropdown-item:hover,.cat-dropdown-item.focused{background:var(--accent-light);}
        .cat-dropdown-icon{width:32px;height:32px;border-radius:8px;background:var(--surface-3);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:14px;color:var(--accent);}
        .cat-dropdown-text{}
        .cat-dropdown-name{font-size:13.5px;font-weight:600;color:var(--text-primary);line-height:1.2;}
        .cat-dropdown-meta{font-size:11.5px;color:var(--text-muted);margin-top:1px;}
        .cat-dropdown-count{margin-left:auto;flex-shrink:0;font-size:11px;background:var(--surface-3);color:var(--text-muted);padding:2px 7px;border-radius:20px;font-family:'DM Mono',monospace;}
        .cat-dropdown-empty{padding:18px 14px;text-align:center;font-size:13px;color:var(--text-muted);}
        .cat-search-input-row.active .cat-search-input{border-radius:var(--radius) var(--radius) 0 0;border-color:var(--accent);border-bottom-color:transparent;}

        /* ── SELECTED CATEGORY CHIP ── */
        .selected-cat-chip{margin-top:10px;display:none;align-items:center;gap:8px;background:var(--accent-light);border:1px solid #bfdbfe;border-radius:var(--radius);padding:8px 12px;}
        .selected-cat-chip.show{display:flex;}
        .chip-icon{width:28px;height:28px;background:var(--accent);border-radius:6px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;flex-shrink:0;}
        .chip-info{flex:1;min-width:0;}
        .chip-name{font-size:13px;font-weight:600;color:var(--accent);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
        .chip-sub{font-size:11px;color:var(--text-muted);}
        .chip-remove{background:none;border:none;cursor:pointer;color:var(--text-muted);font-size:16px;padding:0;line-height:1;flex-shrink:0;}
        .chip-remove:hover{color:var(--red);}

        /* ── CAT LINKS ── */
        .select-cat-label{padding:8px 14px 2px;font-size:10.5px;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.07em;}
        .clear-all-link{display:block;text-align:right;padding:2px 14px 8px;font-size:12px;color:var(--accent);text-decoration:none;cursor:pointer;background:none;border:none;font-family:inherit;width:100%;}
        .clear-all-link:hover{text-decoration:underline;}
        .sidebar-cat-link{display:flex;align-items:center;justify-content:space-between;padding:9px 14px;font-size:13.5px;color:var(--text-primary);text-decoration:none;border-bottom:1px solid var(--border);transition:all .15s;}
        .sidebar-cat-link:last-child{border-bottom:none;}
        .sidebar-cat-link:hover{background:var(--accent-light);color:var(--accent);}
        .sidebar-cat-link.active{background:var(--accent-light);color:var(--accent);font-weight:600;}
        .cat-count{font-size:11px;background:var(--surface-3);color:var(--text-muted);padding:2px 6px;border-radius:20px;font-family:'DM Mono',monospace;}
        .sidebar-cat-child{display:flex;align-items:center;justify-content:space-between;padding:7px 14px 7px 26px;font-size:13px;color:var(--text-secondary);text-decoration:none;border-bottom:1px solid var(--border);transition:all .15s;}
        .sidebar-cat-child:last-child{border-bottom:none;}
        .sidebar-cat-child:hover{background:var(--accent-light);color:var(--accent);}
        .sidebar-cat-child.active{color:var(--accent);font-weight:500;}

        /* ── FILTER GROUPS ── */
        .filter-group{border-bottom:1px solid var(--border);}
        .filter-group:last-child{border-bottom:none;}
        .filter-group-header{display:flex;align-items:center;justify-content:space-between;padding:11px 14px;cursor:pointer;user-select:none;transition:background .15s;}
        .filter-group-header:hover{background:var(--surface-2);}
        .filter-group-title{font-size:13px;font-weight:600;color:var(--text-primary);}
        .filter-group-body{padding:4px 14px 12px;}
        .filter-group-body.collapsed{display:none;}
        .toggle-icon{color:var(--text-muted);font-size:13px;}

        /* ── PRICE ── */
        .price-row{display:flex;gap:8px;align-items:center;margin-bottom:10px;}
        .price-input{width:50%;flex:1;padding:7px 8px;border:1px solid var(--border);border-radius:var(--radius);font-size:13px;font-family:inherit;background:var(--surface-2);transition:border-color .2s;}
        .price-input:focus{outline:none;border-color:var(--accent);background:var(--surface);}
        .price-sep{color:var(--text-muted);font-size:13px;flex-shrink:0;}
        .btn-apply{width:100%;padding:8px;background:var(--accent);color:#fff;border:none;border-radius:var(--radius);font-size:13px;font-weight:600;font-family:inherit;cursor:pointer;transition:background .2s;}
        .btn-apply:hover{background:var(--accent-dark);}

        /* ── CHECKBOXES ── */
        .filter-option{display:flex;align-items:center;gap:8px;padding:4px 0;}
        .filter-option input[type=checkbox]{width:14px;height:14px;accent-color:var(--accent);cursor:pointer;flex-shrink:0;}
        .filter-option label{font-size:13px;color:var(--text-secondary);cursor:pointer;display:flex;align-items:center;justify-content:space-between;width:100%;}
        .fcount{font-size:11px;color:var(--text-muted);font-family:'DM Mono',monospace;}
        .show-more-btn{background:none;border:none;color:var(--accent);font-size:12px;font-family:inherit;cursor:pointer;padding:4px 0;margin-top:2px;}
        .show-more-btn:hover{text-decoration:underline;}

        /* ── RESULTS BAR ── */
        .results-bar{display:flex;align-items:center;justify-content:space-between;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-lg);padding:11px 16px;margin-bottom:14px;box-shadow:var(--shadow-sm);}
        .results-count{font-size:13.5px;color:var(--text-secondary);}
        .results-count strong{color:var(--text-primary);}
        .sort-sel{padding:6px 10px;border:1px solid var(--border);border-radius:var(--radius);font-size:13px;font-family:inherit;color:var(--text-primary);background:var(--surface-2);cursor:pointer;}
        .sort-sel:focus{outline:none;border-color:var(--accent);}

        /* ── PRODUCT GRID ── */
        .products-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;}
        .product-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-sm);transition:box-shadow .25s,transform .25s;display:flex;flex-direction:column;}
        .product-card:hover{box-shadow:var(--shadow-lg);transform:translateY(-3px);}
        .product-img-wrap{position:relative;background:var(--surface-2);overflow:hidden;}
        .product-img-wrap a{display:block;}
        .product-img-wrap img{width:100%;height:200px;object-fit:contain;padding:14px;transition:transform .3s;}
        .product-card:hover .product-img-wrap img{transform:scale(1.05);}
        .product-body{padding:12px 14px 14px;display:flex;flex-direction:column;flex:1;}
        .product-badge{display:inline-flex;align-items:center;font-size:10.5px;font-weight:700;padding:2px 8px;border-radius:20px;margin-bottom:7px;width:fit-content;text-transform:uppercase;letter-spacing:.04em;}
        .badge-refurb{background:var(--green-light);color:var(--green);}
        .badge-new{background:var(--accent-light);color:var(--accent);}
        .badge-used{background:var(--amber-light);color:var(--amber);}
        .badge-default{background:var(--surface-3);color:var(--text-secondary);}
        .product-name{font-size:13.5px;font-weight:600;color:var(--text-primary);line-height:1.4;margin-bottom:4px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
        .product-specs{display:flex;align-items:center;gap:5px;font-size:12px;color:var(--text-muted);margin-bottom:10px;flex-wrap:wrap;}
        .spec-dot{width:3px;height:3px;background:var(--text-muted);border-radius:50%;flex-shrink:0;}
        .product-price{font-size:21px;font-weight:700;color:var(--text-primary);font-family:'DM Mono',monospace;line-height:1;margin-bottom:3px;}
        .product-finance{font-size:11.5px;color:var(--text-muted);margin-bottom:12px;}
        .fi-tag{display:inline-block;background:var(--red-light);color:var(--red);font-size:10px;font-weight:700;padding:1px 5px;border-radius:3px;margin-left:3px;vertical-align:middle;}
        .btn-view{display:block;width:100%;padding:9px;background:var(--surface);color:var(--accent);border:1.5px solid var(--accent);border-radius:var(--radius);font-size:13px;font-weight:600;font-family:inherit;text-align:center;text-decoration:none;cursor:pointer;transition:all .2s;margin-top:auto;}
        .btn-view:hover{background:var(--accent);color:#fff;}

        /* ── SKELETON ── */
        .skeleton{background:linear-gradient(90deg,#f1f5f9 25%,#e2e8f0 50%,#f1f5f9 75%);background-size:200% 100%;animation:shimmer 1.4s infinite;border-radius:var(--radius);}
        @keyframes shimmer{0%{background-position:200% 0}100%{background-position:-200% 0}}
        .skel-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-lg);overflow:hidden;}
        .skel-img{height:200px;}.skel-body{padding:14px;}
        .skel-line{height:13px;margin-bottom:9px;border-radius:4px;}
        .skel-line.w80{width:80%;}.skel-line.w60{width:60%;}.skel-line.w40{width:40%;}

        /* ── STATES ── */
        .no-products{grid-column:1/-1;text-align:center;padding:80px 20px;color:var(--text-muted);}
        .no-products i{font-size:3rem;display:block;margin-bottom:14px;}
        .spinner{width:34px;height:34px;border:3px solid var(--border);border-top-color:var(--accent);border-radius:50%;animation:spin .7s linear infinite;}
        @keyframes spin{to{transform:rotate(360deg)}}

        /* ── LOADING OVERLAY on products ── */
        .products-loading{position:relative;min-height:200px;}
        .products-loading::after{content:'';position:absolute;inset:0;background:rgba(248,250,252,.7);border-radius:var(--radius-lg);display:flex;align-items:center;justify-content:center;}

        /* ── RESPONSIVE ── */
        @media(max-width:1024px){.shop-layout{grid-template-columns:220px 1fr}.products-grid{grid-template-columns:repeat(2,1fr)}}
        @media(max-width:768px){.shop-layout{grid-template-columns:1fr}.shop-sidebar{position:static}.products-grid{grid-template-columns:repeat(2,1fr)}}
        @media(max-width:480px){.products-grid{grid-template-columns:1fr}}
</style>