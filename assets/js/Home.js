class Home {

    constructor(apiService) {
        this.api = apiService;
        this.data = null;
    }

    load() {
        this.api.get('wp-json/wp/v2/home')
            .then((data) => {
                this.data = data || {};
                this.renderHome();
            })
            .catch((err) => {
                console.error("Home load error:", err);
            });
    }

    renderHome() {
        const data = this.data;
        const slider = $(".home-slider");

        if (slider.hasClass("slick-initialized")) {
            slider.slick("unslick");
        }

        slider.html("");

        (data.slides || []).forEach(s => {
            slider.append(`
                <div>
                    <img src="${s.image}" class="img-fluid w-100"/>
                </div>
            `);
        });

        if ((data.slides || []).length) {
            slider.slick({
                autoplay: true,
                dots: true,
                arrows: false
            });
        }


        $("#pageContent").html(data.page?.content || "");


   
        this.renderProducts(data.featured_products?.featured_group_1, "featuredProducts1", 6000);
        this.renderProducts(data.featured_products?.featured_group_2, "featuredProducts2", 7000);
        this.renderProducts(data.featured_products?.featured_group_3, "featuredProducts3", 9000);
        this.renderProducts(data.featured_products?.featured_group_4, "featuredProducts4", 10000);
        this.renderProducts(data.featured_products?.featured_group_5, "featuredProducts5", 15000);


        this.renderTopRated(data.top_rated_products || {});


        this.renderSmall(data.end_featured_products, "endFeaturedBox");
        this.renderSmall(data.top_selling_products, "topSellingBox");
        this.renderSmall(data.hot_new_releases, "hotNewBox");


        const brands = $("#brands");

        if (brands.hasClass("slick-initialized")) {
            brands.slick("unslick");
        }

        brands.html("");

        (data.brands || []).forEach(b => {
            brands.append(`
                <div class="brand-item text-center px-2">
                    <a href="${BASE_URL}brands/${b.slug}">
                        <img src="${b.thumbnail || ''}" class="img-fluid"/>
                    </a>
                </div>
            `);
        });

        brands.slick({
            slidesToShow: 6,
            autoplay: true,
            arrows: true,
            responsive: [
                { breakpoint: 1024, settings: { slidesToShow: 4 } },
                { breakpoint: 768, settings: { slidesToShow: 3 } },
                { breakpoint: 480, settings: { slidesToShow: 2 } }
            ]
        });


        $("#banner").html(
            data.banner?.image
                ? `<img src="${data.banner.image}" class="img-fluid w-100"/>`
                : ""
        );

        $("#banner2").html(
            data.banner_2?.image
                ? `<section
                    class="py-5 px-5"
                    style="
                      background:
                      linear-gradient(
                        90deg,
                        rgba(26,26,26,0.8) 50%,
                        transparent
                      ),
                      url('${data.banner_2?.image}');
                      background-size: cover;
                      background-position: center;
                      color: white;
                    "
                  >

                    <div class="container">

                      <div class="row align-items-center">

                        <div class="col-md-6">

                          <a
                            href="${data.banner_2?.link}"
                            class="text-white newsletter-link"
                          >

                            <div class="newsletter-content">
                              ${data.banner_2?.content}
                            </div>

                          </a>

                        </div>

                      </div>

                    </div>

                  </section>`
                : ""
        );
    }


    renderProducts(products, id, speed = 4000) {
        const box = $("#" + id);
        if (!box.length) return;

        box.html("");

        const currentWishlist = JSON.parse(localStorage.getItem('user_wishlist')) || [];
        
        (products || []).forEach(p => {
            const productKey = p.slug || p.permalink || '';
            const isExist = currentWishlist.some(item => (item.slug === productKey || item.permalink === productKey));
            
            const heartColor = isExist ? '#13564f' : '#212529';
            const heartIcon = isExist ? 'bi-heart-fill' : 'bi-heart';

            box.append(`
                <div class="product-item-wrap py-1">
                    <div class="card h-100 product-card border-light shadow-sm rounded-4 position-relative p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="text-uppercase text-muted small fw-semibold tracking-wider">${p.category || ''}</span>
                        <div class="d-flex flex-column gap-2 align-items-center">
                 
                        <button class="btn btn-link p-0 text-dark border-0 bg-transparent fs-5 toggle-direction-btn" 
                                aria-label="Toggle Slider Direction"
                                onclick="
                                    (() => {
                                        const $slider = $('#${id}');
                                        const current = $slider.slick('slickCurrentSlide'); // Current active index (0, 1, 2...)
                                        const total = $slider.slick('getSlick').slideCount; // Total slides in this slider
                                        
                                        // Agar user aakhri slide par khada hai, to backward (prev) le jao
                                        if (current === total - 1) {
                                            $slider.slick('slickPrev');
                                        } else {
                                            // Start mein ho ya mid mein ho, to hamesha forward (next) karo
                                            $slider.slick('slickNext');
                                        }
                                    })();
                                ">
                            <i class="bi bi-arrow-left-right"></i>
                        </button>
                        </div>
                    </div>
                    <div class="position-relative text-center my-3">
                        ${p.isNew ? `<span class="badge position-absolute start-0 bottom-0 px-3 py-2 rounded-3 text-uppercase fw-bold text-white" style="background-color: #f26500;">New</span>` : ''}
                        <a class="d-block featured-product-image-link" href="/shop/buy/${p.permalink || '#'}">
                        <img src="${p.image}" class="img-fluid object-fit-contain" alt="${p.name}" style="max-height: 220px; width: 100%;">
                        </a>
                    </div>
                    <div class="card-body p-0 mt-3 d-flex flex-column justify-content-end">
                        <h5 class="card-title fw-bold text-dark mb-3" style="font-size: 1rem;">
                        <a href="/shop/buy/${p.permalink || '#'}" class="text-decoration-none text-dark hover-primary">${p.name || 'Electric Hand Blender, 150 Watts'}</a>
                        </h5>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                        <span class="fw-semibold">${p.currencySymbol || '£'}${p.price}</span>
                                <div class="d-inline-flex align-items-center gap-3">
                                <button class="btn btn-link p-0 border-0 bg-transparent fs-4 lh-1 wishlist-btn d-inline-flex align-items-center" 
                                        style="color: ${heartColor};"
                                        aria-label="Add to Wishlist" 
                                        onclick='toggleWishlist(this, ${JSON.stringify(p)})'>
                                    <i class="bi ${heartIcon}"></i>
                                </button>
                                
                                <a class="btn btn-link p-0 text-dark fs-4 lh-1 d-inline-flex align-items-center" 
                                href="/shop/buy/${p.permalink || '#'}" 
                                aria-label="Add to Cart">
                                    <i class="bi bi-cart3"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            `);
        });

        if ((products || []).length) {
            box.slick({
                slidesToShow: 1, 
                slidesToScroll: 1,
                autoplay: true,          // ◄ Autoplay on rahega
                autoplaySpeed: speed,    // ◄ Har row ki apni speed yahan set hogi
                arrows: false,
                dots: false
            });
        }
    }


    renderTopRated(data) {
        const products = data.products || [];

        $("#leftProducts").html("");
        $("#rightProducts").html("");


        const currentWishlist = JSON.parse(localStorage.getItem('user_wishlist')) || [];


        products.slice(0, 4).forEach(p => {

            const isExist = currentWishlist.some(item => item.slug === p.slug);
            
            
            const heartColor = isExist ? '#13564f' : '#212529';
            const heartIcon = isExist ? 'bi-heart-fill' : 'bi-heart';

            $("#leftProducts").append(`
                <div class="col-md-6 p-2">
                    <div class="card h-100 border border-light-subtle rounded-3 p-3 d-flex flex-column justify-content-between shadow-sm bg-white" style="min-height: 380px;">
                        
                        <div>
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <strong class="category-link text-uppercase text-muted d-block small fw-semibold tracking-wider" style="font-size: 0.75rem; font-family: monospace;">
                                    ${p.category || 'Sell Phone'}
                                </strong>
                                
                           
                            </div>

                            <div class="text-center d-flex align-items-center justify-content-center my-2" style="height: 180px; overflow: hidden;">
                                <a href="/shop/buy/${p.slug || '#'}" class="d-block w-100 h-100">
                                    <img 
                                        src="${p.image}" 
                                        class="img-fluid h-100" 
                                        alt="${p.name}" 
                                        style="object-fit: contain; max-width: 100%;"
                                    >
                                </a>
                            </div>
                        </div>

                        <div class="mt-3">
                            <h6 class="fw-bold text-dark mb-2 text-truncate-2-lines" style="font-size: 0.95rem; min-height: 2.4rem; line-height: 1.2;">
                                <a href="/shop/buy/${p.slug || '#'}" class="text-decoration-none text-dark">
                                    ${p.name}
                                </a>
                            </h6>

                            <div class="d-flex justify-content-between align-items-end pt-1">
                                <strong class="fw-bold fs-5 text-dark">£${p.price}</strong>
                                
                                                     <div>
                               <button class="btn btn-link p-0 border-0 bg-transparent fs-5 lh-1 wishlist-btn" 
                                style="color: ${heartColor};"
                                aria-label="Add to Wishlist" 
                                onclick='toggleWishlist(this, ${JSON.stringify(p)})'>
                            <i class="bi ${heartIcon}"></i>
                        </button>
                        <a class="btn btn-link p-0 text-dark fs-3" href="/shop/buy/${p.url || '#'}" aria-label="Add to Cart"><i class="bi bi-cart3"></i></a>
                        </div>
                            </div>
                        </div>

                    </div>
                </div>
            `);
        });

        products.slice(4, 8).forEach(p => {

            const isExist = currentWishlist.some(item => item.slug === p.slug);
            const heartColor = isExist ? '#13564f' : '#212529';
            const heartIcon = isExist ? 'bi-heart-fill' : 'bi-heart';

            $("#rightProducts").append(`
                <div class="col-md-6 p-2">
                    <div class="card h-100 border border-light-subtle rounded-3 p-3 d-flex flex-column justify-content-between shadow-sm bg-white" style="min-height: 380px;">
                        
                        <div>
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <strong class="category-link text-uppercase text-muted d-block small fw-semibold tracking-wider" style="font-size: 0.75rem; font-family: monospace;">
                                    ${p.category || 'Sell Phone'}
                                </strong>
                                
                            
                            </div>

                            <div class="text-center d-flex align-items-center justify-content-center my-2" style="height: 180px; overflow: hidden;">
                                <a href="/shop/buy/${p.slug || '#'}" class="d-block w-100 h-100">
                                    <img 
                                        src="${p.image}" 
                                        class="img-fluid h-100" 
                                        alt="${p.name}" 
                                        style="object-fit: contain; max-width: 100%;"
                                    >
                                </a>
                            </div>
                        </div>

                        <div class="mt-3">
                            <h6 class="fw-bold text-dark mb-2 text-truncate-2-lines" style="font-size: 0.95rem; min-height: 2.4rem; line-height: 1.2;">
                                <a href="/shop/buy/${p.slug || '#'}" class="text-decoration-none text-dark">
                                    ${p.name}
                                </a>
                            </h6>

                            <div class="d-flex justify-content-between align-items-end pt-1">
                                <strong class="fw-bold fs-5 text-dark">£${p.price}</strong>
                                
                                                    <div>
                               <button class="btn btn-link p-0 border-0 bg-transparent fs-5 lh-1 wishlist-btn" 
                                style="color: ${heartColor};"
                                aria-label="Add to Wishlist" 
                                onclick='toggleWishlist(this, ${JSON.stringify(p)})'>
                            <i class="bi ${heartIcon}"></i>
                        </button>
                        <a class="btn btn-link p-0 text-dark fs-3" href="/shop/buy/${p.url || '#'}" aria-label="Add to Cart"><i class="bi bi-cart3"></i></a>
                        </div>
                            </div>
                        </div>

                    </div>
                </div>
            `);
        });


        if (data.banner?.image) {
            const bannerBox = document.getElementById('centerBanner'); 
            if (bannerBox) {
                bannerBox.innerHTML = `
                    <a class="img-fluid w-100 rounded d-block" 
                      style="background-image: url(${data.banner.image}); background-size: cover; background-position: center; min-height: 250px;" 
                      href="${data.banner.url || '#'}">
                    </a>
                `;
            }
        }
    }

    renderSmall(products, id) {
        const box = $("#" + id);
        if (!box.length) return;

        box.html("");

        (products || []).forEach(p => {
            box.append(`
                <div class="product-card mb-3">

        <a
          href="/shop/buy/${p.permalink || '#'}"
          class="d-flex gap-3 align-items-start text-decoration-none text-dark"
        >

          <img
            src="${p.image}"
            alt="${p.name}"
            class="rounded"
            style="
              width:84px;
              height:84px;
              object-fit:cover;
            "
          >

          <div class="product-info">

            <h6 class="mb-1">${p.name}</h6>

            <strong>£${p.price}</strong>

          </div>

        </a>

      </div>
            `);
        });
    }

  
}