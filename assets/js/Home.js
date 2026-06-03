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

        const screenWidth = $(window).width();
        this.renderProducts(data.featured_products?.featured_group_1, "featuredProducts1", 6000);
        this.renderProducts(data.featured_products?.featured_group_2, "featuredProducts2", 7000);
        if (screenWidth >= 576) {
            $("#featuredProducts3").parent().removeClass("d-none");
            this.renderProducts(data.featured_products?.featured_group_3, "featuredProducts3", 9000);
        } else {
            $("#featuredProducts3").parent().addClass("d-none");
        }
        if (screenWidth >= 992) {
            $("#featuredProducts4").parent().removeClass("d-none");
            $("#featuredProducts5").parent().removeClass("d-none");
            this.renderProducts(data.featured_products?.featured_group_4, "featuredProducts4", 10000);
            this.renderProducts(data.featured_products?.featured_group_5, "featuredProducts5", 15000);
        } else {
            $("#featuredProducts4").parent().addClass("d-none");
            $("#featuredProducts5").parent().addClass("d-none");
        }


        this.renderTopRated(data.top_rated_products || {});
        this.renderTopRatedMobile(data.top_rated_products || {});


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

            const uniqueCardId = p.id || Math.random().toString(36).substr(2, 9);

            box.append(`
                <div class="product-item-wrap py-2" style="width: 100%;">
                    <div class="card h-100 product-card border-0 position-relative" 
                        data-card-id="${uniqueCardId}"
                        style="transition: all 0.3s ease; height: 100%; background: #ffffff; border-radius: 16px; padding: 20px;"
                        onmouseenter="this.querySelector('.custom-overlay').style.opacity='1'; this.querySelector('.custom-overlay').style.visibility='visible'; this.querySelector('.custom-overlay').style.transform='translateY(0)';"
                        onmouseleave="this.querySelector('.custom-overlay').style.opacity='0'; this.querySelector('.custom-overlay').style.visibility='hidden'; this.querySelector('.custom-overlay').style.transform='translateY(10px)';"
                    >
                        
                        <div class="d-flex justify-content-between align-items-center mb-3" style="width: 100%;">
                            <span class="text-uppercase fw-bold text-dark tracking-wider" style="font-size: 11px; letter-spacing: 0.5px; opacity: 0.8;">
                                ${p.category || 'Category'}
                            </span>
                            <button class="btn btn-link p-0 text-dark border-0 bg-transparent toggle-direction-btn d-flex align-items-center" 
                                    style="font-size: 14px;"
                                    aria-label="Toggle Slider Direction"
                                    onclick="
                                        (() => {
                                            const $slider = $('#${id}');
                                            const current = $slider.slick('slickCurrentSlide');
                                            const total = $slider.slick('getSlick').slideCount;
                                            if (current === total - 1) {
                                                $slider.slick('slickPrev');
                                            } else {
                                                $slider.slick('slickNext');
                                            }
                                        })();
                                    ">
                                <i class="bi bi-arrow-left-right"></i>
                            </button>
                        </div>
                        
                        <div class="position-relative text-center overflow-hidden" 
                            style="height: 220px; background-color: #ffffff; border: 1px solid #f1f3f5; border-radius: 12px; padding: 15px; display: d-flex; align-items: center; justify-content: center;">
                            
                            ${p.isNew ? `<span class="badge position-absolute start-0 bottom-0 px-3 py-2 rounded-3 text-uppercase fw-bold text-white" style="background-color: #f26500; z-index: 3; margin: 10px; font-size: 10px;">New</span>` : ''}
                            
                            <a class="d-block w-100 h-100 featured-product-image-link" href="/shop/buy/${p.permalink || '#'}">
                                <img src="${p.image}" class="w-100 h-100" alt="${p.name}" style="object-fit: contain;">
                            </a>

                            <div class="custom-overlay d-flex align-items-center justify-content-center gap-2 position-absolute top-0 start-0 w-100 h-100"
                                style="background-color: rgba(255, 255, 255, 0.85); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px); opacity: 0; visibility: hidden; transform: translateY(10px); transition: all 0.3s ease-in-out; z-index: 2; border-radius: 12px;">
                                
                                <a href="/shop/buy/${p.permalink || '#'}" class="btn btn-primary btn-sm px-3 py-2 fw-semibold rounded-pill shadow-sm" style="font-size: 12px; background-color: #13564f; border-color: #13564f;">
                                    <i class="bi bi-bag-check me-1"></i> Shop
                                </a>
                                <a href="/shop/buy/${p.permalink || '#'}" class="btn btn-danger btn-sm px-3 py-2 fw-semibold rounded-pill shadow-sm" style="font-size: 12px; background-color: #f26500; border-color: #f26500;">
                                    <i class="bi bi-tags me-1"></i> Sale
                                </a>
                            </div>
                        </div>
                        
                        <div class="card-body p-0 mt-3 d-flex flex-column justify-content-between" style="flex-grow: 1;">
                            <h5 class="card-title fw-bold text-dark mb-2" style="font-size: 14px; line-height: 1.4; min-height: 40px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                <a href="/shop/buy/${p.permalink || '#'}" class="text-decoration-none text-dark style-title-link" style="transition: color 0.2s;">
                                    ${p.name || 'Product Title Placeholder'}
                                </a>
                            </h5>
                            
                            <div class="d-flex justify-content-between align-items-center mt-2" style="width: 100%;">
                                <span class="fw-bold text-dark" style="font-size: 15px;">
                                    ${p.currencySymbol || '£'}${p.price}
                                </span>
                                <div class="d-inline-flex align-items-center gap-3">
                                    <button class="btn btn-link p-0 border-0 bg-transparent lh-1 wishlist-btn d-flex align-items-center" 
                                            style="color: ${heartColor}; font-size: 18px;"
                                            aria-label="Add to Wishlist" 
                                            data-product='${JSON.stringify(p).replace(/'/g, "&apos;")}'
                                            onclick="toggleWishlist(this, JSON.parse(this.getAttribute('data-product')))">
                                        <i class="bi ${heartIcon}"></i>
                                    </button>
                                    
                                    <a class="btn btn-link p-0 text-dark lh-1 d-flex align-items-center" 
                                    href="/shop/buy/${p.permalink || '#'}" 
                                    style="font-size: 18px;"
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
                autoplay: true,         
                autoplaySpeed: speed,    
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
        console.log( products);

        products.slice(0, 4).forEach(p => {
            const isExist = currentWishlist.some(item => item.slug === p.slug);
            const heartColor = isExist ? '#13564f' : '#212529';
            const heartIcon = isExist ? 'bi-heart-fill' : 'bi-heart';

      
            $("#leftProducts").append(`
                <div class="col-md-6 p-2">
                    ${this.getTopRatedCardTemplate(p, heartColor, heartIcon)}
                </div>
            `);
        });


        products.slice(4, 8).forEach(p => {
            const isExist = currentWishlist.some(item => item.slug === p.slug);
            const heartColor = isExist ? '#13564f' : '#212529';
            const heartIcon = isExist ? 'bi-heart-fill' : 'bi-heart';

            $("#rightProducts").append(`
                <div class="col-md-6 p-2">
                    ${this.getTopRatedCardTemplate(p, heartColor, heartIcon)}
                </div>
            `);
        });


        if (data.banner?.image) {
            const bannerBox = document.getElementById('centerBanner'); 
            if (bannerBox) {
                bannerBox.innerHTML = `
                    <a class="d-block w-100 h-100" href="${data.banner.url || '#'}">
                        <img src="${data.banner.image}" 
                            class="w-100 rounded" 
                            alt="Banner" 
                            style="display: block; height: 100%; min-height: 520px; max-height: 82%; object-fit: contain;">
                    </a>
                `;
            }
        }
    }


    renderTopRatedMobile(data) {
        const products = data.products || [];
        const $mobileSlider = $("#mobileProductsSlider");

        if ($mobileSlider.length) {
            if ($mobileSlider.hasClass('slick-initialized')) {
                $mobileSlider.slick('unslick'); 
            }
            $mobileSlider.html(""); 
        }

        const currentWishlist = JSON.parse(localStorage.getItem('user_wishlist')) || [];

        if ($mobileSlider.length && products.length > 0) {

            products.slice(0, 8).forEach(p => {
                const isExist = currentWishlist.some(item => item.slug === p.slug);
                const heartColor = isExist ? '#13564f' : '#212529';
                const heartIcon = isExist ? 'bi-heart-fill' : 'bi-heart';

                $mobileSlider.append(`
                    <div class="px-2 h-100 ">
                        ${this.getTopRatedCardTemplate(p, heartColor, heartIcon)}
                    </div>
                `);
            });


            $mobileSlider.slick({
                dots: false,
                arrows: false,
                infinite: false,
                speed: 300,
                slidesToShow: 2, 
                slidesToScroll: 1,
                responsive: [
                    {
                        breakpoint: 576, 
                        settings: {
                            slidesToShow: 1.2, 
                            slidesToScroll: 1,
                            dots: true
                        }
                    }
                ]
            });
        }
    }


    getTopRatedCardTemplate(p, heartColor, heartIcon) {

        const uniqueCardId = p.id || Math.random().toString(36).substr(2, 9);

        return `
            <div class="card h-100 border border-light-subtle rounded-3 p-3 d-flex flex-column justify-content-between shadow-sm bg-white" 
                data-card-id="${uniqueCardId}"
                style="min-height: 380px; transition: all 0.3s ease;"
                onmouseenter="this.querySelector('.custom-overlay').style.opacity='1'; this.querySelector('.custom-overlay').style.visibility='visible'; this.querySelector('.custom-overlay').style.transform='translateY(0)';"
                onmouseleave="this.querySelector('.custom-overlay').style.opacity='0'; this.querySelector('.custom-overlay').style.visibility='hidden'; this.querySelector('.custom-overlay').style.transform='translateY(10px)';"
            >
                <div>
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <strong class="category-link text-uppercase text-muted d-block small fw-semibold tracking-wider" style="font-size: 0.75rem; font-family: monospace;">
                            ${p.category || 'Sell Phone'}
                        </strong>
                    </div>

                    <div class="position-relative text-center d-flex align-items-center justify-content-center my-2 overflow-hidden" 
                        style="height: 180px; background-color: #ffffff; border: 1px solid #f1f3f5; border-radius: 12px; padding: 10px;">
                        
                        <a href="/shop/buy/${p.slug || '#'}" class="d-block w-100 h-100">
                            <img src="${p.image}" class="img-fluid h-100" alt="${p.name}" style="object-fit: contain; max-width: 100%;">
                        </a>

                        <div class="custom-overlay d-flex align-items-center justify-content-center gap-2 position-absolute top-0 start-0 w-100 h-100"
                            style="background-color: rgba(255, 255, 255, 0.85); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px); opacity: 0; visibility: hidden; transform: translateY(10px); transition: all 0.3s ease-in-out; z-index: 2; border-radius: 12px;">
                            
                            <a href="/shop/buy/${p.slug || '#'}" class="btn btn-primary btn-sm px-3 py-2 fw-semibold rounded-pill shadow-sm" style="font-size: 11px; background-color: #13564f; border-color: #13564f;">
                                <i class="bi bi-bag-check me-1"></i> Shop
                            </a>
                            <a href="/shop/buy/${p.slug || '#'}" class="btn btn-danger btn-sm px-3 py-2 fw-semibold rounded-pill shadow-sm" style="font-size: 11px; background-color: #f26500; border-color: #f26500;">
                                <i class="bi bi-tags me-1"></i> Sale
                            </a>
                        </div>
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
                            <button class="btn btn-link p-0 border-0 bg-transparent fs-4 lh-1 wishlist-btn d-inline-flex align-items-center" 
                                    style="color: ${heartColor};"
                                    aria-label="Add to Wishlist" 
                                    data-product='${JSON.stringify(p).replace(/'/g, "&apos;")}'
                                    onclick="toggleWishlist(this, JSON.parse(this.getAttribute('data-product')))">
                                <i class="bi ${heartIcon}"></i>
                            </button>
                            <a class="btn btn-link p-0 text-dark fs-3 ms-2" href="/shop/buy/${p.url || p.slug || '#'}" aria-label="Add to Cart">
                                <i class="bi bi-cart3"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        `;
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