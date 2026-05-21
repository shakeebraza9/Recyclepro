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

        // =========================
        // SLIDER
        // =========================
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


        this.renderProducts(data.featured_products?.featured_group_1, "featuredProducts1");
        this.renderProducts(data.featured_products?.featured_group_2, "featuredProducts2");
        this.renderProducts(data.featured_products?.featured_group_3, "featuredProducts3");
        this.renderProducts(data.featured_products?.featured_group_4, "featuredProducts4");
        this.renderProducts(data.featured_products?.featured_group_5, "featuredProducts5");


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
                    <a href="/brands/${b.slug}">
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
                ? `<section style="background:url('${data.banner_2.image}') center/cover;">
                        <a href="${data.banner_2.link || '#'}">
                            ${data.banner_2.content || ''}
                        </a>
                   </section>`
                : ""
        );
    }


    renderProducts(products, id) {
        const box = $("#" + id);
        if (!box.length) return;

        box.html("");

        (products || []).forEach(p => {
            box.append(`
<div class="px-2">

        <div class="card h-100">

          <div class="position-relative">

            <strong class="category-link">
              ${p.category || 'Product'}
            </strong>

            <a
              class="featured-product-image-link"
              href="/shop/buy/${p.permalink || '#'}"
            >

              <img
                src="${p.image}"
                class="card-img-top"
                alt="${p.name}"
              >

            </a>

          </div>

          <div class="card-body">

            <h6 class="card-title mt-2">

              <a href="/shop/buy/${p.permalink || '#'}">
                ${p.name}
              </a>

            </h6>

            <div class="d-flex mt-2 justify-content-between align-items-center gap-2">

              <strong>£${p.price}</strong>

              <a
                class="buy-now"
                href="/shop/buy/${p.permalink || '#'}"
              >
                Buy Now
              </a>

            </div>

          </div>

        </div>

      </div>
            `);
        });

        if ((products || []).length) {
            box.slick({
                slidesToShow: 1,
                autoplay: true,
                arrows: false
            });
        }
    }


    renderTopRated(data) {
        const products = data.products || [];

        $("#leftProducts").html("");
        $("#rightProducts").html("");

        products.slice(0, 4).forEach(p => {
            $("#leftProducts").append(`
              <div class="col-md-6">

        <div class="card h-100">

          <div class="position-relative">

            <strong class="category-link">
              ${p.category || 'Sell Phone'}
            </strong>

            <a href="/shop/buy/${p.slug || '#'}">
              <img src="${p.image}" class="card-img-top">
            </a>

          </div>

          <div class="card-body">

            <h6>${p.name}</h6>

            <div class="d-flex justify-content-between">

              <strong>£${p.price}</strong>

              <a
                class="buy-now"
                href="/shop/buy/${p.url || '#'}"
              >
                Buy Now
              </a>

            </div>

          </div>

        </div>

      </div>

              `);
        });
        products.slice(4, 8).forEach(p => {
          $("#rightProducts").append(`
                <div class="col-md-6">

                  <div class="card h-100">

                    <div class="position-relative">

                      <strong class="category-link">
                        ${p.category || 'Sell Phone'}
                      </strong>

                      <a href="/shop/buy/${p.slug || '#'}">
                        <img src="${p.image}" class="card-img-top">
                      </a>

                    </div>

                    <div class="card-body">

                      <h6>${p.name}</h6>

                      <div class="d-flex justify-content-between">

                        <strong>£${p.price}</strong>

                        <a
                          class="buy-now"
                          href="/shop/buy/${p.url || '#'}"
                        >
                          Buy Now
                        </a>

                      </div>

                    </div>

                  </div>

                </div>
                
                `);
          });
            if (data.banner?.image) {

    bannerBox.innerHTML = `
      <a   class="img-fluid w-100 rounded"
 style=" background-image: url(${data.banner.image}); background-size: cover; background-position: center; " href="${data.banner.url || '#'}">

      

      </a>
    `;

  }
    }

    renderSmall(products, id) {
        const box = $("#" + id);
        if (!box.length) return;

        box.html("");

        (products || []).forEach(p => {
            box.append(`
                <div class="small-product">
                    <img src="${p.image}" width="60"/>
                    <div>
                        <h6>${p.name}</h6>
                        <strong>£${p.price}</strong>
                    </div>
                </div>
            `);
        });
    }
}