<?php
$pageTitle = 'Recycle Pro';
include __DIR__ . '/includes/header.php';
?>

<section class="home-slider"></section>

<section class="py-5">
  <div class="container" id="pageContent"></div>
</section>

<section class="py-5">
  <div class="container">
    <h3>Featured Products</h3>
    <div class="row" id="featuredProducts"></div>
  </div>
</section>

<section class="py-5 bg-light">
  <div class="container">
    <h3>Top Rated Products</h3>
    <div class="row" id="topProducts"></div>
  </div>
</section>

<section class="py-5">
  <div class="container">
    <h3>Hot New Releases</h3>
    <div class="row" id="hotProducts"></div>
  </div>
</section>

<section class="py-4">
  <div class="container" id="banner"></div>
</section>

<section class="py-5">
  <div class="container">
    <div class="row text-center" id="brands"></div>
  </div>
</section>

<script>
const homeAPI = "https://www.recyclepro.co.uk/rp-dashboard/wp-json/wp/v2/home";

async function loadData() {
  try {
    const response = await fetch(homeAPI);
    const homeData = await response.json();
    renderHome(homeData || {});
  } catch (err) {
    console.error("API Error:", err);
  }
}

loadData();

function renderHome(data) {
  const slider = $(".home-slider");

  if (slider.hasClass("slick-initialized")) {
    slider.slick("unslick");
  }

  slider.html("");
  (data.slides || []).forEach(s => {
    slider.append(`<div><img src="${s.image}" /></div>`);
  });

  if ((data.slides || []).length > 0) {
    slider.slick({
      autoplay: true,
      dots: true,
      arrows: false
    });
  }

  document.getElementById("pageContent").innerHTML = data.page?.content || "";
  renderProducts(data.top_rated_products?.products || [], "topProducts");
  renderProducts(data.hot_new_releases || [], "hotProducts");
  renderProducts(data.featured_products || [], "featuredProducts");

  const brands = document.getElementById("brands");
  brands.innerHTML = "";
  (data.brands || []).forEach(b => {
    brands.innerHTML += `<div class="col-md-2">${b.name}</div>`;
  });

  const banner = document.getElementById("banner");
  if (data.banner_2?.image) {
    banner.innerHTML = `<img src="${data.banner_2.image}" class="img-fluid">`;
  }
}

function renderProducts(products, id) {
  const box = document.getElementById(id);
  box.innerHTML = "";

  (products || []).forEach(p => {
    box.innerHTML += `
      <div class="col-md-3 mb-3">
        <div class="product">
          <img src="${p.image}" class="img-fluid" />
          <h6>${p.name}</h6>
          <strong>£${p.price}</strong>
        </div>
      </div>`;
  });
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
