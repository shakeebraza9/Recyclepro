<?php
$pageTitle = 'Recycle Pro';
include __DIR__ . '/includes/header.php';
?>

<section>
  <div class="container-full">
    <div class="home-slider"></div>
  </div>
</section>


<section class="py-5 bg-light-subtle">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="content-scroll-wrapper">
                    <div id="pageContent" class="custom-scroll-content p-3 p-md-4">
                        </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="py-5 featured-products-section">
  <div class="container">
    <h2 id="category-heading">Product Category</h2>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-4"> 

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
      <a href="shop/category/" class="btn btn-outline-dark">View All Category</a>
    </div>
  </div>
</section>



<section class="py-4 ">
  <div class="container">
    <div id="banner"></div>
  </div>
</section>



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


<section class="py-4 bg-white">
  <div class="container">
    <div id="brands" class="brands-slider"></div>
  </div>
</section>


<section class="py-4">
  <div class="container">
    <div id="banner2"></div>
  </div>
</section>


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
                <input type="email"
                        class="form-control"
                        id="newsletter-email"
                        placeholder="Please enter your email address">

                  <button class="btn btn-dark"
                          type="button"
                          id="subscribe-btn">
                      Subscribe
                  </button>
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
<script src="assets/js/script.js"></script>
<script>
const apiService = new JqueryApiService();
const home = new Home();
$(document).ready(function () {
    const api = new JqueryApiService();
    const home = new Home(api);
    home.load(); 
});

document.getElementById('subscribe-btn').addEventListener('click', async function(){

    const btn = this;
    const email = document.getElementById('newsletter-email').value.trim();

    if(!email){
        alert('Please enter email');
        return;
    }
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if(!emailRegex.test(email)){
        alert('Please enter valid email');
        return;
    }

    btn.disabled = true;
    btn.innerText = 'Submitting...';

    try{

        const response = await fetch(`${baseAPI}wp-json/wp/v2/subscribe`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                email: email
            })
        });

        const data = await response.json();

        if(data.success){

            alert(data.message);

            document.getElementById('newsletter-email').value = '';

        }else{
            alert(data.message);
        }

    }catch(error){

        console.error(error);

        alert('Something went wrong');

    }finally{

        btn.disabled = false;
        btn.innerText = 'Subscribe';
    }
});

</script>
<?php include __DIR__ . '/includes/footer.php'; ?>
