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
                    <div id="pageContent" class="custom-scroll-content p-3 p-md-4" 
                         style="max-height: 300px; overflow-y: auto;">
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
      <div id="centerBanner" class="col-12 col-md-4"></div>
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
<section style="padding: 60px 20px; background-color: #ffffff; font-family: 'Segoe UI', Roboto, sans-serif;">
    <div class="tp-grid-container" style="max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: repeat(4, 1fr); gap: 30px;">
        
        <div style="text-align: center; padding: 30px 20px; background: #ffffff; border: 1px solid #eeeeee; border-radius: 12px; transition: transform 0.3s ease;">
            <div style="width: 70px; height: 70px; margin: 0 auto 20px auto; background-color: #ffffff; border: 2px solid #13564f; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-patch-check" style="font-size: 28px; color: #13564f;"></i>
            </div>
            <h3 style="font-size: 18px; font-weight: 600; color: #1a1a1a; margin: 0 0 12px 0;">Quality Products</h3>
            <p style="font-size: 14px; color: #555555; line-height: 1.6; margin: 0;">We hold high quality standards and offer best quality ever. The products that are designed by our experts are made from the top quality and genuine materials.</p>
        </div>

        <div style="text-align: center; padding: 30px 20px; background: #ffffff; border: 1px solid #eeeeee; border-radius: 12px; transition: transform 0.3s ease;">
            <div style="width: 70px; height: 70px; margin: 0 auto 20px auto; background-color: #ffffff; border: 2px solid #13564f; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-shield-lock" style="font-size: 28px; color: #13564f;"></i>
            </div>
            <h3 style="font-size: 18px; font-weight: 600; color: #1a1a1a; margin: 0 0 12px 0;">Secure Transaction</h3>
            <p style="font-size: 14px; color: #555555; line-height: 1.6; margin: 0;">TV Jackets is concerned about the safety and security of customers. Our payment process is extremely safe, and our customers information is secure.</p>
        </div>

        <div style="text-align: center; padding: 30px 20px; background: #ffffff; border: 1px solid #eeeeee; border-radius: 12px; transition: transform 0.3s ease;">
            <div style="width: 70px; height: 70px; margin: 0 auto 20px auto; background-color: #ffffff; border: 2px solid #13564f; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-headset" style="font-size: 28px; color: #13564f;"></i>
            </div>
            <h3 style="font-size: 18px; font-weight: 600; color: #1a1a1a; margin: 0 0 12px 0;">Customer Service</h3>
            <p style="font-size: 14px; color: #555555; line-height: 1.6; margin: 0;">Customer support staff is always at your service and ready to assist you 24/7 to meet the customer expectations.</p>
        </div>

        <div style="text-align: center; padding: 30px 20px; background: #ffffff; border: 1px solid #eeeeee; border-radius: 12px; transition: transform 0.3s ease;">
            <div style="width: 70px; height: 70px; margin: 0 auto 20px auto; background-color: #ffffff; border: 2px solid #13564f; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-truck" style="font-size: 28px; color: #13564f;"></i>
            </div>
            <h3 style="font-size: 18px; font-weight: 600; color: #1a1a1a; margin: 0 0 12px 0;">Fast Delivery</h3>
            <p style="font-size: 14px; color: #555555; line-height: 1.6; margin: 0;">Enjoy fast time definite delivery of your product with no extra cost or hidden charges for the shipment world-wide. Happy Shopping!</p>
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
