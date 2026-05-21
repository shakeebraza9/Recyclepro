<?php
$pageTitle = 'Recycle Pro';
include __DIR__ . '/includes/header.php';
?>

<!-- SLIDER -->
<section>
  <div class="container-full">
    <div class="home-slider"></div>
  </div>
</section>

<!-- PAGE CONTENT -->
<section class="py-5">
  <div class="container">
    <div class="row">
      <div id="pageContent"></div>
    </div>
  </div>
</section>

<!-- FEATURED -->
<section class="py-5 featured-products-section">
  <div class="container">
<div class="featured-products-section-row  g-4">   

<div class="col">
  <div id="featuredProducts1" class="featured-slider"></div>
</div>

<div class="col">
  <div id="featuredProducts2" class="featured-slider"></div>
</div>


<div class="col">
  
  <div id="featuredProducts3" class="featured-slider"></div>
</div>


<div class="col">

  <div id="featuredProducts4" class="featured-slider"></div>
</div>


<div class="col">
  <div id="featuredProducts5" class="featured-slider"></div>
</div>
</div>
    <div class="text-center mt-4">
      <a href="/shop/" class="btn btn-outline-dark">View All Products</a>
    </div>
  </div>
</section>


<!-- BANNER -->
<section class="py-4">
  <div class="container">
    <div id="banner"></div>
  </div>
</section>


<!-- TOP PRODUCTS -->
<section class="py-5 bg-light">
  <div class="container">
    <h3>Top Rated Products</h3>

    <div class="row g-4">
      <div class="col-12 col-md-4">
        <div class="row g-3" id="leftProducts"></div>
      </div>
      <div class="col-12 col-md-4" id="centerBanner"></div>
      <div class="col-12 col-md-4">
        <div class="row g-3" id="rightProducts"></div>
      </div>
    </div>
  </div>
</section>

<!-- BRANDS -->
<section class="py-4 bg-white">
  <div class="container">
    <div id="brands" class="brands-slider"></div>
  </div>
</section>

<!-- BANNER -->
<section class="py-4">
  <div class="container">
    <div id="banner2"></div>
  </div>
</section>

<!-- LAST 3 COLUMNS -->
<section class="py-5">
  <div class="container">
    <div class="row g-4">
      <div class="col-12 col-md-4">
        <h4>Featured Products</h4>
        <div id="endFeaturedBox"></div>
      </div>
      <div class="col-12 col-md-4">
        <h4>Top Selling Products</h4>
        <div id="topSellingBox"></div>
      </div>
      <div class="col-12 col-md-4">
        <h4>Hot New Releases</h4>
        <div id="hotNewBox"></div>
      </div>
    </div>
  </div>
</section>


<section class="py-5 bg-light">
        <div class="container">
            <h3 class="text-center mb-4">Stay up-to-date with Recycle Pro</h3>
            <div class="row justify-content-center mb-4">
                <div class="col-md-3 text-center">
                    
                    <p class="d-flex  mt-2 align-items-center justify-content-center gap-2"> <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>  Latest News</p>
                </div>
                <div class="col-md-3 text-center">
                    
                    <p class="d-flex  mt-2 align-items-center justify-content-center gap-2"> <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>Brand &amp; Product Updates</p>
                </div>
                <div class="col-md-3 text-center">
                   
                    <p class="d-flex  mt-2 align-items-center justify-content-center gap-2"> <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i> Promotions &amp; Offers</p>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Please enter your email address">
                        <button class="btn btn-dark" type="button">Subscribe</button>
                    </div>
                    <p class="text-center mt-3 small text-muted">
                        <label for="terms"> <input type="checkbox" class="form-check-input me-2" id="terms">
                        I AGREE to give this the mailing list, producing you with the latest trends, products, offers and updates regarding the services. For full info check our data Policy.</label>
                    </p>
                </div>
            </div>
        </div>
    </section>

<script src="assets/js/JqueryApiService.js"></script>
<script src="assets/js/Home.js"></script>
<script>
const apiService = new JqueryApiService();
const home = new Home();
$(document).ready(function () {

    const api = new JqueryApiService();
    const home = new Home(api);

    home.load(); 


});



// ========================================
// TOP RATED PRODUCTS
// ========================================
function renderTopRated(data) {

  const leftBox = document.getElementById("leftProducts");
  const rightBox = document.getElementById("rightProducts");
  const bannerBox = document.getElementById("centerBanner");

  leftBox.innerHTML = "";
  rightBox.innerHTML = "";
  bannerBox.innerHTML = "";

  const products = data.products || [];

  // LEFT PRODUCTS
  products.slice(0, 4).forEach(p => {

    leftBox.innerHTML += `
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
    `;

  });

  // RIGHT PRODUCTS
  products.slice(4, 8).forEach(p => {

    rightBox.innerHTML += `
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
    `;

  });

  // CENTER BANNER
  if (data.banner?.image) {

    bannerBox.innerHTML = `
      <a   class="img-fluid w-100 rounded"
 style=" background-image: url(${data.banner.image}); background-size: cover; background-position: center; " href="${data.banner.url || '#'}">

      

      </a>
    `;

  }

}

// ========================================
// SMALL PRODUCTS
// ========================================
function renderSmallProducts(products, containerId) {

  const box = document.getElementById(containerId);

  if (!box) return;

  box.innerHTML = "";

  (products || []).forEach(p => {

    box.innerHTML += `
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
    `;

  });

}


</script>
<?php include __DIR__ . '/includes/footer.php'; ?>
