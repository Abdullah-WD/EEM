<?php
include 'backend/db.php';
$result = mysqli_query($conn, "SELECT * FROM products");
while ($row = mysqli_fetch_assoc($result)) {
  echo "
  <div class='product'>
    <img src='{$row['image_url']}' />
    <h3>{$row['name']}</h3>
    <p>Rs {$row['price']}</p>
    <button onclick='addToCart({$row['id']})'>Add to Cart</button>
  </div>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Elegant Events</title>
  <link rel="stylesheet" href="styles.css" />
  <!-- Use Google Fonts for cleaner look -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <!-- Optionally include font awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    :root {
      --primary: #002366;
      --secondary: #3a86ff;
      --accent: #e67e22;
      --light: #f8f9fa;
      --dark: #212529;
      --gray: #6c757d;
      --light-gray: #e9ecef;
      --success: #28a745;
      --shadow: 0 4px 20px rgba(0,0,0,0.08);
      --transition: all 0.3s ease;
    }
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f5f7fa;
      color: var(--dark);
      line-height: 1.6;
    }
    
    /* Header styles (keep your existing header) */
    
    .container {
      display: flex;
      max-width: 1400px;
      margin: 2rem auto;
      gap: 2rem;
      padding: 0 1rem;
    }
    
    /* Sidebar styles */
    .sidebar {
      width: 250px;
      background: white;
      border-radius: 12px;
      padding: 1.5rem;
      box-shadow: var(--shadow);
      position: sticky;
      top: 1rem;
      height: fit-content;
    }
    
    .sidebar h2 {
      font-size: 1.5rem;
      margin-bottom: 1.5rem;
      color: var(--dark);
      position: relative;
      padding-bottom: 0.75rem;
    }
    
    .sidebar h2::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 50px;
      height: 3px;
      background: var(--primary);
    }
    
    .category-list {
      list-style: none;
      margin-bottom: 2rem;
    }
    
    .category-list li {
      padding: 0.75rem 0;
      cursor: pointer;
      transition: var(--transition);
      border-bottom: 1px solid var(--light-gray);
      font-weight: 500;
      display: flex;
      align-items: center;
    }
    
    .category-list li::before {
      content: '\f054';
      font-family: 'Font Awesome 6 Free';
      font-weight: 900;
      margin-right: 0.75rem;
      font-size: 0.7rem;
      color: var(--gray);
      transition: var(--transition);
    }
    
    .category-list li:hover {
      color: var(--primary);
      padding-left: 0.5rem;
    }
    
    .category-list li:hover::before {
      color: var(--primary);
    }
    
    .category-list li.active {
      color: var(--primary);
      font-weight: 600;
    }
    
    /* Product Grid */
    .product-grid {
      flex: 1;
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
      gap: 1.5rem;
    }
    
    .product-card {
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: var(--shadow);
      transition: var(--transition);
      position: relative;
      border: 1px solid rgba(0,0,0,0.05);
    }
    
    .product-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.12);
    }
    
    .product-badge {
      position: absolute;
      top: 1rem;
      left: 1rem;
      background: var(--accent);
      color: white;
      padding: 0.25rem 0.75rem;
      border-radius: 50px;
      font-size: 0.75rem;
      font-weight: 600;
      z-index: 2;
    }
    
    .product-img-container {
      height: 200px;
      overflow: hidden;
      position: relative;
    }
    
    .product-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: var(--transition);
    }
    
    .product-card:hover .product-img {
      transform: scale(1.05);
    }
    
    .product-actions {
      position: absolute;
      top: 1rem;
      right: 1rem;
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
      opacity: 0;
      transition: var(--transition);
    }
    
    .product-card:hover .product-actions {
      opacity: 1;
    }
    
    .action-btn {
      width: 34px;
      height: 34px;
      border-radius: 50%;
      background: white;
      color: var(--dark);
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      transition: var(--transition);
      border: none;
      cursor: pointer;
      font-size: 0.9rem;
    }
    
    .action-btn:hover {
      background: var(--primary);
      color: white;
      transform: scale(1.1);
    }
    
    .product-info {
      padding: 1.25rem;
    }
    
    .product-category {
      color: var(--gray);
      font-size: 0.8rem;
      margin-bottom: 0.25rem;
      display: block;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    
    .product-title {
      font-size: 1rem;
      font-weight: 600;
      margin-bottom: 0.5rem;
      display: -webkit-box;
      /* -webkit-line-clamp: 2; */
      -webkit-box-orient: vertical;
      overflow: hidden;
      min-height: 2.8em;
    }
    
    .product-price {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      margin-bottom: 1rem;
    }
    
    .current-price {
      font-size: 1.1rem;
      font-weight: 700;
      color: var(--primary);
    }
    
    .original-price {
      font-size: 0.85rem;
      color: var(--gray);
      text-decoration: line-through;
    }
    
    .add-to-cart {
      width: 100%;
      padding: 0.65rem;
      background: var(--primary);
      color: white;
      border: none;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      font-size: 0.9rem;
    }
    
    .add-to-cart:hover {
      background: #002050;
    }
    
    .rating {
      display: flex;
      align-items: center;
      gap: 0.25rem;
      margin-bottom: 0.8rem;
    }
    
    .rating-stars {
      color: #ffc107;
      font-size: 0.9rem;
    }
    
    .rating-count {
      font-size: 0.75rem;
      color: var(--gray);
    }

    /* Product Grid Header */
    .product-grid-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
      padding: 0 0.5rem;
    }

    .product-grid-title {
      font-size: 1.5rem;
      font-weight: 600;
      color: var(--dark);
    }

    .sort-options {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .sort-options select {
      padding: 0.5rem;
      border-radius: 6px;
      border: 1px solid var(--light-gray);
      font-family: 'Poppins', sans-serif;
      font-size: 0.9rem;
    }
    
    /* Responsive */
    @media (max-width: 1200px) {
      .container {
        flex-direction: column;
      }
      
      .sidebar {
        width: 100%;
        position: static;
      }
      
      .product-grid {
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
      }
    }
    
    @media (max-width: 768px) {
      .product-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      }
    }
    
    @media (max-width: 576px) {
      .product-grid {
        grid-template-columns: 1fr 1fr;
      }

      .product-img-container {
        height: 160px;
      }

      .product-info {
        padding: 1rem;
      }
    }

    /* Price Filter */
    .filter-section {
  margin-top: 2rem;
  padding-top: 1.5rem;
  border-top: 1px solid var(--light-gray);
}

.filter-section h3 {
  font-size: 1.1rem;
  margin-bottom: 0.5rem;
  color: var(--dark);
}

.price-range-display {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 0.3rem;
  margin-bottom: 0.5rem;
  font-size: 0.9rem;
  color: var(--dark);
}

.price-slider-container {
  margin-bottom: 1rem;
}

.price-slider {
  width: 100%;
  height: 4px;
  -webkit-appearance: none;
  appearance: none;
  background: var(--light-gray);
  border-radius: 2px;
  outline: none;
  margin-bottom: 0.5rem;
}

.price-slider::-webkit-slider-thumb {
  -webkit-appearance: none;
  appearance: none;
  width: 16px;
  height: 16px;
  background: var(--primary);
  border-radius: 50%;
  cursor: pointer;
}

.price-slider::-moz-range-thumb {
  width: 16px;
  height: 16px;
  background: var(--primary);
  border-radius: 50%;
  cursor: pointer;
}

.apply-filter-btn {
  width: 100%;
  padding: 0.5rem;
  background: var(--primary);
  color: white;
  border: none;
  border-radius: 4px;
  font-weight: 500;
  cursor: pointer;
  transition: var(--transition);
  text-transform: uppercase;
  font-size: 0.8rem;
  letter-spacing: 0.5px;
}

.apply-filter-btn:hover {
  background: #001a4d;
}


/* Responsive Sidebar Toggle Button */
.filter-toggle {
  display: none;
  background: var(--primary);
  color: white;
  border: none;
  padding: 10px 20px;
  font-size: 1rem;
  font-weight: 600;
  border-radius: 6px;
  margin: 10px;
  cursor: pointer;
  box-shadow: var(--shadow);
}
.filter-toggle i {
  margin-right: 8px;
}

/* Slide-in Sidebar for Small Screens */
@media (max-width: 991px) {
  .filter-toggle {
    display: block;
  }
  .sidebar {
    position: fixed;
    top: 0;
    left: -100%;
    height: 90vh;
    z-index: 1100;
    transition: var(--transition);
    width: 75%;
    overflow-y: auto;
    padding-top: 2rem;
    border-radius: 0px;
  }
  .sidebar.open {
    left: 0;
    box-shadow: 2px 0 10px rgba(0,0,0,0.1);
  }
  body.sidebar-open::before {
    content: "";
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0,0,0,0.3);
    z-index: 998;
  }
}
.sidebar .close-btn {
  position: absolute;
  top: 10px;
  right: 15px;
  font-size: 24px;
  background: none;
  color: var(--dark);
  border: none;
  cursor: pointer;
}
@media (min-width: 992px) {
  .close-btn {
    display: none !important;
  }
}


.badge {
  background: #ffd700;
  color: #000;
  font-size: 0.5rem;
  border-radius: 50%;
  padding: 0.2rem 0.4rem;
  position: absolute;
  top: 30px;
  right: 21px;
  display: none;
}
  </style>

</head>
<body>

  <header class="navbar">
    <div class="mobile-header">
      <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
        <i class="fas fa-bars"></i>
      </button>
      <div class="logo"><span>Elegant Events</span></div>
      <a href="cart.html" style="color: white;"><i class="fa-solid fa-cart-shopping"></i><span id="cart-count" class="badge">0</span></a>
    </div>

    <div class="mobile-menu" id="mobileMenu">
      <button class="close-btn" onclick="toggleMobileMenu()">&times;</button>
      <button class="close-btn" onclick="closeMobileMenu()">×</button>
      <a href="index.html">Home</a>
      <a href="Shop.html" onclick="sessionStorage.setItem('openFilterOnLoad', 'true')">Shop</a>
      <a href="Offers.html">Offers</a>
      <a href="WholeSale.html">Whole Sale</a>
      <a href="AboutUS.html">About Us</a>
      <a href="ContactUs.html">Contact Us</a>
      <a href="order-tracking.html">Order Tracking</a>
      <a href="privacy-policy.html">Privacy Policy</a>
      <a href="terms-and-conditions.html">Terms and Conditions</a>
    </div>

    <div class="navbar-top">
      <div class="logo">
        <span>Elegant Events</span>
      </div>
      <div class="search-bar">
  <div class="dropdown-container">
    <select id="category-select">
      <option selected>All</option>
    </select>
  </div>
  <input type="text" id="searchInput" placeholder="Search your favorite product..." />
  <button onclick="searchProducts()">Search</button>
</div>


      <div class="icons">
        <div class="icon-item">
          <a href="Login.html" style="color: white; text-decoration: none;">
            <i class="fa-regular fa-user"></i>
          </a>
          <a href="Login.html" style="color: white; text-decoration: none;">
            <span>Sign In<br />Account</span>
          </a>
        </div>

        <div class="icon-item" style="position: relative;">
          <a href="wishlist.html" style="color: white; text-decoration: none;">
            <i class="fa-regular fa-heart"></i>
            <span id="wishlist-count" style="position: absolute; top: -11px; right: -11px; background: #ffd700; color: #000; font-size: 9px; padding: 2px 6px; border-radius: 50%;">0</span>
          </a>
        </div>

        <div class="icon-item" style="position: relative;">
          <a href="cart.html" style="color: white; text-decoration: none;">
            <i class="fa-solid fa-cart-shopping"></i>
            <span id="cart-count" style="position: absolute; top: -11px; right: -11px; background: #ffd700; color: #000; font-size: 9px; padding: 2px 6px; border-radius: 50%;">0</span>
          </a>
        </div>
      </div>
    </div>

    <div class="navbar-bottom">
      <nav>
        <a href="index.html">Home</a>
        <a href="Shop.html">Shop</a>
        <a href="Offers.html">Offers</a>
        <a href="WholeSale.html">Whole Sale</a>
        <a href="AboutUS.html">About Us</a>
        <a href="ContactUs.html">Contact Us</a>
      </nav>
    </div>
  </header>

  <div class="mobile-nav">
    <a href="Shop.html" onclick="sessionStorage.setItem('openFilterOnLoad', 'true')">
  <i class="fas fa-store"></i>Store
</a>
    <a href="#"><i class="fas fa-search"></i>Search</a>
    <a href="wishlist.html"><i class="far fa-heart"></i>Wishlist</a>
    <a href="Login.html"><i class="far fa-user"></i>Account</a>
  </div>

  <script>
  const menu = document.getElementById('mobileMenu');

  function toggleMobileMenu() {
    menu.classList.toggle('open');
  }

  function closeMobileMenu() {
    menu.classList.remove('open');
  }

  // Close menu when clicking outside
  document.addEventListener('click', function(event) {
    const isClickInsideMenu = menu.contains(event.target);
    const isHamburger = event.target.closest('.mobile-menu-toggle');
    
    if (!isClickInsideMenu && !isHamburger && menu.classList.contains('open')) {
      closeMobileMenu();
    }
  });
</script>

<!-- Script to update cart & wishlist -->
<script>
  function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem("cart")) || [];
    const total = cart.reduce((sum, item) => sum + item.quantity, 0);
    const cartCounters = document.querySelectorAll("#cart-count");

    cartCounters.forEach(el => {
      el.textContent = total;
      el.style.display = total > 0 ? "inline-block" : "none";
    });
  }

  function updateWishlistCount() {
    const wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];
    const wishlistCounters = document.querySelectorAll("#wishlist-count");

    wishlistCounters.forEach(el => {
      el.textContent = wishlist.length;
      el.style.display = wishlist.length > 0 ? "inline-block" : "none";
    });
  }

  updateCartCount();
  updateWishlistCount();
</script>



  <div class="promo-slider">
        <div class="slider-track">
            &nbsp;&nbsp;|® DEVELOPED BY ABDULLAH ZAFAR &nbsp;&nbsp;|® DEVELOPED BY ABDULLAH ZAFAR &nbsp;&nbsp;|® DEVELOPED BY ABDULLAH ZAFAR &nbsp;&nbsp;
        </div>
    </div>

    <div class="container">
    <!-- Filter Toggle Button (Visible only on small screens) -->
<button id="filterToggle" class="filter-toggle">
  <i class="fas fa-filter"></i> Filter Products
</button>

<!-- Sidebar for Categories & Price Filter -->
<aside class="sidebar" id="filterSidebar">
  <button class="close-btn" onclick="closeSidebar()">×</button>
  <h2>Categories</h2>
  <ul class="category-list">
    <li class="active" onclick="filterProducts('all')">All Products</li>
    <li onclick="filterProducts('mehndi')">Mehndi Items</li>
    <li onclick="filterProducts('wedding')">Fancy Envelop</li>
    <li onclick="filterProducts('birthday')">Birthday Items</li>
    <li onclick="filterProducts('date')">Date Fix</li>
    <li onclick="filterProducts('hajj')">Hajj Decor</li>
    <li onclick="filterProducts('baat')">Baat Pakki</li>
    <li onclick="filterProducts('nikkah')">Nikkah Items</li>
  </ul>

  <div class="filter-section">
    <h3>Price</h3>
    <div class="price-range-display">
      <span id="minPrice">Rs0</span> <span> — </span>
      <span id="maxPrice">Rs5,000</span>
    </div>
    <div class="price-slider-container">
      <input type="range" min="0" max="5000" value="5000" step="100" class="price-slider" id="priceRange">
    </div>
    <button class="apply-filter-btn" onclick="applyPriceFilter()">FILTER</button>
  </div>
</aside>
<script>
  const toggleBtn = document.getElementById('filterToggle');
  const sidebar = document.getElementById('filterSidebar');

  toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('open');
    document.body.classList.toggle('sidebar-open');
  });

  // Close sidebar if clicking outside
  window.addEventListener('click', function(e) {
    if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
      sidebar.classList.remove('open');
      document.body.classList.remove('sidebar-open');
    }
  });
  function closeSidebar() {
  const sidebar = document.getElementById('filterSidebar');
  sidebar.classList.remove('open');
  document.body.classList.remove('sidebar-open');
}


</script>
<script>
  window.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('filterSidebar');

    // Check if we should auto-open the sidebar
    if (sessionStorage.getItem('openFilterOnLoad') === 'true') {
      sidebar.classList.add('open');
      document.body.classList.add('sidebar-open');
      sessionStorage.removeItem('openFilterOnLoad');
    }
  });
</script>



    <!-- Product Grid -->
    <main class="product-grid">
      <!-- Mehndi Items Product -->
      <div class="product-card" data-category="mehndi">
        <span class="product-badge">Best Seller</span>
        <div class="product-img-container">
          <img src="images/Mehndi/Product1.jpg" alt="Premium Mehndi Cone Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist" onclick="addToWishlist('Premium Mehndi Cone Set', 250, 'images/Mehndi/Product1.jpg')">
  <i class="far fa-heart"></i>
</button>
<script>
  function addToWishlist(name, price, image) {
    const wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];
    const exists = wishlist.find(item => item.name === name);
    if (!exists) {
      wishlist.push({ name, price, image, date: new Date().toLocaleDateString() });
      localStorage.setItem("wishlist", JSON.stringify(wishlist));
    }
    window.location.href = 'wishlist.html';
  }
</script>


          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Mehndi Items</span>
          <h3 class="product-title">Mehndi Rasam Plate (18")</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(36)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 950.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="mehndi">
        <span class="product-badge">Best Seller</span>
        <div class="product-img-container">
          <img src="images/Mehndi/Product1.jpg" alt="Premium Mehndi Cone Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Mehndi Items</span>
          <h3 class="product-title">Mehndi Rasam Plate (18")</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(36)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 950.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="mehndi">
        <span class="product-badge">Best Seller</span>
        <div class="product-img-container">
          <img src="images/Mehndi/Product1.jpg" alt="Premium Mehndi Cone Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Mehndi Items</span>
          <h3 class="product-title">Mehndi Rasam Plate (18")</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(36)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 950.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="mehndi">
        <span class="product-badge">Best Seller</span>
        <div class="product-img-container">
          <img src="images/Mehndi/Product1.jpg" alt="Premium Mehndi Cone Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Mehndi Items</span>
          <h3 class="product-title">Mehndi Rasam Plate (18")</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(36)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 950.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="mehndi">
        <span class="product-badge">Best Seller</span>
        <div class="product-img-container">
          <img src="images/Mehndi/Product1.jpg" alt="Premium Mehndi Cone Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Mehndi Items</span>
          <h3 class="product-title">Mehndi Rasam Plate (18")</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(36)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 950.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="mehndi">
        <span class="product-badge">Best Seller</span>
        <div class="product-img-container">
          <img src="images/Mehndi/Product1.jpg" alt="Premium Mehndi Cone Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Mehndi Items</span>
          <h3 class="product-title">Mehndi Rasam Plate (18")</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(36)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 950.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="mehndi">
        <span class="product-badge">Best Seller</span>
        <div class="product-img-container">
          <img src="images/Mehndi/Product1.jpg" alt="Premium Mehndi Cone Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Mehndi Items</span>
          <h3 class="product-title">Mehndi Rasam Plate (18")</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(36)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 950.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="mehndi">
        <span class="product-badge">Best Seller</span>
        <div class="product-img-container">
          <img src="images/Mehndi/Product1.jpg" alt="Premium Mehndi Cone Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Mehndi Items</span>
          <h3 class="product-title">Mehndi Rasam Plate (18")</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(36)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 950.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="mehndi">
        <span class="product-badge">Best Seller</span>
        <div class="product-img-container">
          <img src="images/Mehndi/Product1.jpg" alt="Premium Mehndi Cone Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Mehndi Items</span>
          <h3 class="product-title">Mehndi Rasam Plate (18")</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(36)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 950.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="mehndi">
        <span class="product-badge">Best Seller</span>
        <div class="product-img-container">
          <img src="images/Mehndi/Product1.jpg" alt="Premium Mehndi Cone Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Mehndi Items</span>
          <h3 class="product-title">Mehndi Rasam Plate (18")</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(36)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 950.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="mehndi">
        <span class="product-badge">Best Seller</span>
        <div class="product-img-container">
          <img src="images/Mehndi/Product1.jpg" alt="Premium Mehndi Cone Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Mehndi Items</span>
          <h3 class="product-title">Mehndi Rasam Plate (18")</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(36)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 950.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="mehndi">
        <span class="product-badge">Best Seller</span>
        <div class="product-img-container">
          <img src="images/Mehndi/Product1.jpg" alt="Premium Mehndi Cone Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Mehndi Items</span>
          <h3 class="product-title">Mehndi Rasam Plate (18")</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(36)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 950.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="mehndi">
        <span class="product-badge">Best Seller</span>
        <div class="product-img-container">
          <img src="images/Mehndi/Product1.jpg" alt="Premium Mehndi Cone Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Mehndi Items</span>
          <h3 class="product-title">Mehndi Rasam Plate (18")</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(36)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 950.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="mehndi">
        <span class="product-badge">Best Seller</span>
        <div class="product-img-container">
          <img src="images/Mehndi/Product1.jpg" alt="Premium Mehndi Cone Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Mehndi Items</span>
          <h3 class="product-title">Mehndi Rasam Plate (18")</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(36)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 950.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="mehndi">
        <span class="product-badge">Best Seller</span>
        <div class="product-img-container">
          <img src="images/Mehndi/Product1.jpg" alt="Premium Mehndi Cone Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Mehndi Items</span>
          <h3 class="product-title">Mehndi Rasam Plate (18")</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(36)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 950.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="mehndi">
        <span class="product-badge">Best Seller</span>
        <div class="product-img-container">
          <img src="images/Mehndi/Product1.jpg" alt="Premium Mehndi Cone Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Mehndi Items</span>
          <h3 class="product-title">Mehndi Rasam Plate (18")</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(36)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 950.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="mehndi">
        <span class="product-badge">Best Seller</span>
        <div class="product-img-container">
          <img src="images/Mehndi/Product1.jpg" alt="Premium Mehndi Cone Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Mehndi Items</span>
          <h3 class="product-title">Mehndi Rasam Plate (18")</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(36)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 950.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="mehndi">
        <span class="product-badge">Best Seller</span>
        <div class="product-img-container">
          <img src="images/Mehndi/Product1.jpg" alt="Premium Mehndi Cone Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Mehndi Items</span>
          <h3 class="product-title">Mehndi Rasam Plate (18")</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(36)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 950.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="mehndi">
        <span class="product-badge">Best Seller</span>
        <div class="product-img-container">
          <img src="images/Mehndi/Product1.jpg" alt="Premium Mehndi Cone Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Mehndi Items</span>
          <h3 class="product-title">Mehndi Rasam Plate (18")</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(36)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 950.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="mehndi">
        <span class="product-badge">Best Seller</span>
        <div class="product-img-container">
          <img src="images/Mehndi/Product1.jpg" alt="Premium Mehndi Cone Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Mehndi Items</span>
          <h3 class="product-title">Mehndi Rasam Plate (18")</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(36)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 950.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="mehndi">
        <span class="product-badge">Best Seller</span>
        <div class="product-img-container">
          <img src="images/Mehndi/Product1.jpg" alt="Premium Mehndi Cone Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Mehndi Items</span>
          <h3 class="product-title">Mehndi Rasam Plate (18")</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(36)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 950.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="mehndi">
        <span class="product-badge">Best Seller</span>
        <div class="product-img-container">
          <img src="images/Mehndi/Product1.jpg" alt="Premium Mehndi Cone Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Mehndi Items</span>
          <h3 class="product-title">Mehndi Rasam Plate (18")</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(36)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 950.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="mehndi">
        <span class="product-badge">Best Seller</span>
        <div class="product-img-container">
          <img src="images/Mehndi/Product1.jpg" alt="Premium Mehndi Cone Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Mehndi Items</span>
          <h3 class="product-title">Mehndi Rasam Plate (18")</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(36)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 950.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="mehndi">
        <span class="product-badge">Best Seller</span>
        <div class="product-img-container">
          <img src="images/Mehndi/Product1.jpg" alt="Premium Mehndi Cone Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Mehndi Items</span>
          <h3 class="product-title">Mehndi Rasam Plate (18")</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(36)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 950.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="mehndi">
        <span class="product-badge">Best Seller</span>
        <div class="product-img-container">
          <img src="images/Mehndi/Product1.jpg" alt="Premium Mehndi Cone Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Mehndi Items</span>
          <h3 class="product-title">Mehndi Rasam Plate (18")</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(36)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 950.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="mehndi">
        <span class="product-badge">Best Seller</span>
        <div class="product-img-container">
          <img src="images/Mehndi/Product1.jpg" alt="Premium Mehndi Cone Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Mehndi Items</span>
          <h3 class="product-title">Mehndi Rasam Plate (18")</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(36)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 950.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="mehndi">
        <span class="product-badge">Best Seller</span>
        <div class="product-img-container">
          <img src="images/Mehndi/Product1.jpg" alt="Premium Mehndi Cone Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Mehndi Items</span>
          <h3 class="product-title">Mehndi Rasam Plate (18")</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(36)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 950.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="mehndi">
        <span class="product-badge">Best Seller</span>
        <div class="product-img-container">
          <img src="images/Mehndi/Product1.jpg" alt="Premium Mehndi Cone Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Mehndi Items</span>
          <h3 class="product-title">Mehndi Rasam Plate (18")</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(36)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 950.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="mehndi">
        <span class="product-badge">Best Seller</span>
        <div class="product-img-container">
          <img src="images/Mehndi/Product1.jpg" alt="Premium Mehndi Cone Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Mehndi Items</span>
          <h3 class="product-title">Mehndi Rasam Plate (18")</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(36)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 950.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="mehndi">
        <span class="product-badge">Best Seller</span>
        <div class="product-img-container">
          <img src="images/Mehndi/Product1.jpg" alt="Premium Mehndi Cone Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Mehndi Items</span>
          <h3 class="product-title">Mehndi Rasam Plate (18")</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(36)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 950.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="mehndi">
        <span class="product-badge">Best Seller</span>
        <div class="product-img-container">
          <img src="images/Mehndi/Product1.jpg" alt="Premium Mehndi Cone Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Mehndi Items</span>
          <h3 class="product-title">Mehndi Rasam Plate (18")</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(36)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 950.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="mehndi">
        <span class="product-badge">Best Seller</span>
        <div class="product-img-container">
          <img src="images/Mehndi/Product1.jpg" alt="Premium Mehndi Cone Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Mehndi Items</span>
          <h3 class="product-title">Mehndi Rasam Plate (18")</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(36)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 950.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="mehndi">
        <span class="product-badge">Best Seller</span>
        <div class="product-img-container">
          <img src="images/Mehndi/Product1.jpg" alt="Premium Mehndi Cone Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Mehndi Items</span>
          <h3 class="product-title">Mehndi Rasam Plate (18")</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(36)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 950.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>

      <!-- Fancy Envelop Product -->
      <div class="product-card" data-category="wedding">
        <div class="product-img-container">
          <img src="images/Fancy_Envelop/Product1.jpg" alt="Luxury Wedding Envelop" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Fancy Envelop</span>
          <h3 class="product-title">Nikkah Mubarak Envelop (Wallpaper)</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="far fa-star"></i>
            </div>
            <span class="rating-count">(28)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 150.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="wedding">
        <div class="product-img-container">
          <img src="images/Fancy_Envelop/Product1.jpg" alt="Luxury Wedding Envelop" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Fancy Envelop</span>
          <h3 class="product-title">Nikkah Mubarak Envelop (Wallpaper)</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="far fa-star"></i>
            </div>
            <span class="rating-count">(28)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 150.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="wedding">
        <div class="product-img-container">
          <img src="images/Fancy_Envelop/Product3.jpg" alt="Luxury Wedding Envelop" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Fancy Envelop</span>
          <h3 class="product-title">Nikkah Mubarak Envelop (Wallpaper)</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="far fa-star"></i>
            </div>
            <span class="rating-count">(28)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 150.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="wedding">
        <div class="product-img-container">
          <img src="images/Fancy_Envelop/Product4.jpg" alt="Luxury Wedding Envelop" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Fancy Envelop</span>
          <h3 class="product-title">Nikkah Mubarak Envelop (Wallpaper)</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="far fa-star"></i>
            </div>
            <span class="rating-count">(28)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 150.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="wedding">
        <div class="product-img-container">
          <img src="images/Fancy_Envelop/Product1.jpg" alt="Luxury Wedding Envelop" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Fancy Envelop</span>
          <h3 class="product-title">Nikkah Mubarak Envelop (Wallpaper)</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="far fa-star"></i>
            </div>
            <span class="rating-count">(28)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 150.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="wedding">
        <div class="product-img-container">
          <img src="images/Fancy_Envelop/Product6.jpg" alt="Luxury Wedding Envelop" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Fancy Envelop</span>
          <h3 class="product-title">Nikkah Mubarak Envelop (Wallpaper)</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="far fa-star"></i>
            </div>
            <span class="rating-count">(28)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 150.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="wedding">
        <div class="product-img-container">
          <img src="images/Fancy_Envelop/Product1.jpg" alt="Luxury Wedding Envelop" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Fancy Envelop</span>
          <h3 class="product-title">Nikkah Mubarak Envelop (Wallpaper)</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="far fa-star"></i>
            </div>
            <span class="rating-count">(28)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 150.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="wedding">
        <div class="product-img-container">
          <img src="images/Fancy_Envelop/Product8.jpg" alt="Luxury Wedding Envelop" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Fancy Envelop</span>
          <h3 class="product-title">Nikkah Mubarak Envelop (Wallpaper)</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="far fa-star"></i>
            </div>
            <span class="rating-count">(28)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 150.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>

      <!-- Birthday Items Product -->
      <div class="product-card" data-category="birthday">
        <span class="product-badge">New</span>
        <div class="product-img-container">
          <img src="images/Birthday/Product1.jpeg" alt="Birthday Party Pack" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Birthday Items</span>
          <h3 class="product-title">Complete Birthday Party Pack</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(45)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 2,800</span>
            <span class="original-price">PKR 3,200</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="birthday">
        <span class="product-badge">New</span>
        <div class="product-img-container">
          <img src="images/Birthday/Product1.jpeg" alt="Birthday Party Pack" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Birthday Items</span>
          <h3 class="product-title">Complete Birthday Party Pack</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(45)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 2,800</span>
            <span class="original-price">PKR 3,200</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="birthday">
        <span class="product-badge">New</span>
        <div class="product-img-container">
          <img src="images/Birthday/Product1.jpeg" alt="Birthday Party Pack" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Birthday Items</span>
          <h3 class="product-title">Complete Birthday Party Pack</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(45)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 2,800</span>
            <span class="original-price">PKR 3,200</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="birthday">
        <span class="product-badge">New</span>
        <div class="product-img-container">
          <img src="images/Birthday/Product1.jpeg" alt="Birthday Party Pack" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Birthday Items</span>
          <h3 class="product-title">Complete Birthday Party Pack</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(45)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 2,800</span>
            <span class="original-price">PKR 3,200</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="birthday">
        <span class="product-badge">New</span>
        <div class="product-img-container">
          <img src="images/Birthday/Product1.jpeg" alt="Birthday Party Pack" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Birthday Items</span>
          <h3 class="product-title">Complete Birthday Party Pack</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(45)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 2,800</span>
            <span class="original-price">PKR 3,200</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="birthday">
        <span class="product-badge">New</span>
        <div class="product-img-container">
          <img src="images/Birthday/Product1.jpeg" alt="Birthday Party Pack" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Birthday Items</span>
          <h3 class="product-title">Complete Birthday Party Pack</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(45)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 2,800</span>
            <span class="original-price">PKR 3,200</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="birthday">
        <span class="product-badge">New</span>
        <div class="product-img-container">
          <img src="images/Birthday/Product1.jpeg" alt="Birthday Party Pack" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Birthday Items</span>
          <h3 class="product-title">Complete Birthday Party Pack</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(45)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 2,800</span>
            <span class="original-price">PKR 3,200</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="birthday">
        <span class="product-badge">New</span>
        <div class="product-img-container">
          <img src="images/Birthday/Product1.jpeg" alt="Birthday Party Pack" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Birthday Items</span>
          <h3 class="product-title">Complete Birthday Party Pack</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(45)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 2,800</span>
            <span class="original-price">PKR 3,200</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="birthday">
        <span class="product-badge">New</span>
        <div class="product-img-container">
          <img src="images/Birthday/Product1.jpeg" alt="Birthday Party Pack" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Birthday Items</span>
          <h3 class="product-title">Complete Birthday Party Pack</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(45)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 2,800</span>
            <span class="original-price">PKR 3,200</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="birthday">
        <span class="product-badge">New</span>
        <div class="product-img-container">
          <img src="images/Birthday/Product1.jpeg" alt="Birthday Party Pack" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Birthday Items</span>
          <h3 class="product-title">Complete Birthday Party Pack</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(45)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 2,800</span>
            <span class="original-price">PKR 3,200</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="birthday">
        <span class="product-badge">New</span>
        <div class="product-img-container">
          <img src="images/Birthday/Product1.jpeg" alt="Birthday Party Pack" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Birthday Items</span>
          <h3 class="product-title">Complete Birthday Party Pack</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(45)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 2,800</span>
            <span class="original-price">PKR 3,200</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="birthday">
        <span class="product-badge">New</span>
        <div class="product-img-container">
          <img src="images/Birthday/Product1.jpeg" alt="Birthday Party Pack" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Birthday Items</span>
          <h3 class="product-title">Complete Birthday Party Pack</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(45)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 2,800</span>
            <span class="original-price">PKR 3,200</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="birthday">
        <span class="product-badge">New</span>
        <div class="product-img-container">
          <img src="images/Birthday/Product1.jpeg" alt="Birthday Party Pack" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Birthday Items</span>
          <h3 class="product-title">Complete Birthday Party Pack</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(45)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 2,800</span>
            <span class="original-price">PKR 3,200</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="birthday">
        <span class="product-badge">New</span>
        <div class="product-img-container">
          <img src="images/Birthday/Product1.jpeg" alt="Birthday Party Pack" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Birthday Items</span>
          <h3 class="product-title">Complete Birthday Party Pack</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(45)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 2,800</span>
            <span class="original-price">PKR 3,200</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="birthday">
        <span class="product-badge">New</span>
        <div class="product-img-container">
          <img src="images/Birthday/Product1.jpeg" alt="Birthday Party Pack" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Birthday Items</span>
          <h3 class="product-title">Complete Birthday Party Pack</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(45)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 2,800</span>
            <span class="original-price">PKR 3,200</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="birthday">
        <span class="product-badge">New</span>
        <div class="product-img-container">
          <img src="images/Birthday/Product1.jpeg" alt="Birthday Party Pack" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Birthday Items</span>
          <h3 class="product-title">Complete Birthday Party Pack</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(45)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 2,800</span>
            <span class="original-price">PKR 3,200</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="birthday">
        <span class="product-badge">New</span>
        <div class="product-img-container">
          <img src="images/Birthday/Product1.jpeg" alt="Birthday Party Pack" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Birthday Items</span>
          <h3 class="product-title">Complete Birthday Party Pack</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(45)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 2,800</span>
            <span class="original-price">PKR 3,200</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="birthday">
        <span class="product-badge">New</span>
        <div class="product-img-container">
          <img src="images/Birthday/Product1.jpeg" alt="Birthday Party Pack" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Birthday Items</span>
          <h3 class="product-title">Complete Birthday Party Pack</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(45)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 2,800</span>
            <span class="original-price">PKR 3,200</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="birthday">
        <span class="product-badge">New</span>
        <div class="product-img-container">
          <img src="images/Birthday/Product1.jpeg" alt="Birthday Party Pack" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Birthday Items</span>
          <h3 class="product-title">Complete Birthday Party Pack</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(45)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 2,800</span>
            <span class="original-price">PKR 3,200</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>

      <!-- Date Fix Product -->
      <div class="product-card" data-category="date">
        <div class="product-img-container">
          <img src="images/Date_Fix/Product1.jpg" alt="Date Fix Gift Box" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Date Fix</span>
          <h3 class="product-title">Date Fix Foil Balloon</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(19)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 350.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="date">
        <div class="product-img-container">
          <img src="images/Date_Fix/Product2.jpg" alt="Date Fix Gift Box" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Date Fix</span>
          <h3 class="product-title">Date Fix Foil Balloon</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(19)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 350.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="date">
        <div class="product-img-container">
          <img src="images/Date_Fix/Product3.jpg" alt="Date Fix Gift Box" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Date Fix</span>
          <h3 class="product-title">Date Fix Foil Balloon</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(19)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 350.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="date">
        <div class="product-img-container">
          <img src="images/Date_Fix/Product4.jpg" alt="Date Fix Gift Box" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Date Fix</span>
          <h3 class="product-title">Date Fix Foil Balloon</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(19)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 350.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="date">
        <div class="product-img-container">
          <img src="images/Date_Fix/Product5.jpg" alt="Date Fix Gift Box" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Date Fix</span>
          <h3 class="product-title">Date Fix Foil Balloon</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(19)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 350.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>

      <!-- Baat Pakki Product -->
      <div class="product-card" data-category="baat">
        <span class="product-badge">Popular</span>
        <div class="product-img-container">
          <img src="images/Baat_Pakki/Product1.jpg" alt="Baat Pakki Gift Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Baat Pakki</span>
          <h3 class="product-title">Baat Pakki Mithai Topers</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="far fa-star"></i>
            </div>
            <span class="rating-count">(22)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 100.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="baat">
        <span class="product-badge">Popular</span>
        <div class="product-img-container">
          <img src="images/Baat_Pakki/Product1.jpg" alt="Baat Pakki Gift Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Baat Pakki</span>
          <h3 class="product-title">Baat Pakki Mithai Topers</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="far fa-star"></i>
            </div>
            <span class="rating-count">(22)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 100.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="baat">
        <span class="product-badge">Popular</span>
        <div class="product-img-container">
          <img src="images/Baat_Pakki/Product1.jpg" alt="Baat Pakki Gift Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Baat Pakki</span>
          <h3 class="product-title">Baat Pakki Mithai Topers</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="far fa-star"></i>
            </div>
            <span class="rating-count">(22)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 100.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="baat">
        <span class="product-badge">Popular</span>
        <div class="product-img-container">
          <img src="images/Baat_Pakki/Product1.jpg" alt="Baat Pakki Gift Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Baat Pakki</span>
          <h3 class="product-title">Baat Pakki Mithai Topers</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="far fa-star"></i>
            </div>
            <span class="rating-count">(22)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 100.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="baat">
        <span class="product-badge">Popular</span>
        <div class="product-img-container">
          <img src="images/Baat_Pakki/Product1.jpg" alt="Baat Pakki Gift Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Baat Pakki</span>
          <h3 class="product-title">Baat Pakki Mithai Topers</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="far fa-star"></i>
            </div>
            <span class="rating-count">(22)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 100.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="baat">
        <span class="product-badge">Popular</span>
        <div class="product-img-container">
          <img src="images/Baat_Pakki/Product1.jpg" alt="Baat Pakki Gift Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Baat Pakki</span>
          <h3 class="product-title">Baat Pakki Mithai Topers</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="far fa-star"></i>
            </div>
            <span class="rating-count">(22)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 100.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="baat">
        <span class="product-badge">Popular</span>
        <div class="product-img-container">
          <img src="images/Baat_Pakki/Product1.jpg" alt="Baat Pakki Gift Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Baat Pakki</span>
          <h3 class="product-title">Baat Pakki Mithai Topers</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="far fa-star"></i>
            </div>
            <span class="rating-count">(22)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 100.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="baat">
        <span class="product-badge">Popular</span>
        <div class="product-img-container">
          <img src="images/Baat_Pakki/Product1.jpg" alt="Baat Pakki Gift Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Baat Pakki</span>
          <h3 class="product-title">Baat Pakki Mithai Topers</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="far fa-star"></i>
            </div>
            <span class="rating-count">(22)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 100.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="baat">
        <span class="product-badge">Popular</span>
        <div class="product-img-container">
          <img src="images/Baat_Pakki/Product1.jpg" alt="Baat Pakki Gift Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Baat Pakki</span>
          <h3 class="product-title">Baat Pakki Mithai Topers</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="far fa-star"></i>
            </div>
            <span class="rating-count">(22)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 100.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="baat">
        <span class="product-badge">Popular</span>
        <div class="product-img-container">
          <img src="images/Baat_Pakki/Product1.jpg" alt="Baat Pakki Gift Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Baat Pakki</span>
          <h3 class="product-title">Baat Pakki Mithai Topers</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="far fa-star"></i>
            </div>
            <span class="rating-count">(22)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 100.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="baat">
        <span class="product-badge">Popular</span>
        <div class="product-img-container">
          <img src="images/Baat_Pakki/Product1.jpg" alt="Baat Pakki Gift Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Baat Pakki</span>
          <h3 class="product-title">Baat Pakki Mithai Topers</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="far fa-star"></i>
            </div>
            <span class="rating-count">(22)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 100.00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>

      <!-- Nikkah Item Product -->
      <div class="product-card" data-category="nikkah">
        <div class="product-img-container">
          <img src="images/Nikkah/Product5.jpg" alt="Premium Suit Box" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Nikkah Item</span>
          <h3 class="product-title">Nikkah Thumb Frame (Square)</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(31)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 1500,00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="nikkah">
        <div class="product-img-container">
          <img src="images/Nikkah/Product5.jpg" alt="Premium Suit Box" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Nikkah Item</span>
          <h3 class="product-title">Nikkah Thumb Frame (Square)</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(31)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 1500,00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="nikkah">
        <div class="product-img-container">
          <img src="images/Nikkah/Product5.jpg" alt="Premium Suit Box" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Nikkah Item</span>
          <h3 class="product-title">Nikkah Thumb Frame (Square)</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(31)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 1500,00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="nikkah">
        <div class="product-img-container">
          <img src="images/Nikkah/Product5.jpg" alt="Premium Suit Box" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Nikkah Item</span>
          <h3 class="product-title">Nikkah Thumb Frame (Square)</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(31)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 1500,00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="nikkah">
        <div class="product-img-container">
          <img src="images/Nikkah/Product5.jpg" alt="Premium Suit Box" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Nikkah Item</span>
          <h3 class="product-title">Nikkah Thumb Frame (Square)</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(31)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 1500,00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="nikkah">
        <div class="product-img-container">
          <img src="images/Nikkah/Product5.jpg" alt="Premium Suit Box" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Nikkah Item</span>
          <h3 class="product-title">Nikkah Thumb Frame (Square)</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(31)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 1500,00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="nikkah">
        <div class="product-img-container">
          <img src="images/Nikkah/Product5.jpg" alt="Premium Suit Box" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Nikkah Item</span>
          <h3 class="product-title">Nikkah Thumb Frame (Square)</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(31)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 1500,00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="nikkah">
        <div class="product-img-container">
          <img src="images/Nikkah/Product5.jpg" alt="Premium Suit Box" class="product-img">
          <div class="product-actions">
           <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Nikkah Item</span>
          <h3 class="product-title">Nikkah Thumb Frame (Square)</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(31)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 1500,00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="nikkah">
        <div class="product-img-container">
          <img src="images/Nikkah/Product5.jpg" alt="Premium Suit Box" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Nikkah Item</span>
          <h3 class="product-title">Nikkah Thumb Frame (Square)</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(31)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 1500,00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="nikkah">
        <div class="product-img-container">
          <img src="images/Nikkah/Product5.jpg" alt="Premium Suit Box" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Nikkah Item</span>
          <h3 class="product-title">Nikkah Thumb Frame (Square)</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(31)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 1500,00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="nikkah">
        <div class="product-img-container">
          <img src="images/Nikkah/Product5.jpg" alt="Premium Suit Box" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Nikkah Item</span>
          <h3 class="product-title">Nikkah Thumb Frame (Square)</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(31)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 1500,00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="nikkah">
        <div class="product-img-container">
          <img src="images/Nikkah/Product5.jpg" alt="Premium Suit Box" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Nikkah Item</span>
          <h3 class="product-title">Nikkah Thumb Frame (Square)</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(31)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 1500,00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="nikkah">
        <div class="product-img-container">
          <img src="images/Nikkah/Product5.jpg" alt="Premium Suit Box" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Nikkah Item</span>
          <h3 class="product-title">Nikkah Thumb Frame (Square)</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(31)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 1500,00</span>
          </div>
           <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="nikkah">
        <div class="product-img-container">
          <img src="images/Nikkah/Product5.jpg" alt="Premium Suit Box" class="product-img">
          <div class="product-actions">
           <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Nikkah Item</span>
          <h3 class="product-title">Nikkah Thumb Frame (Square)</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(31)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 1500,00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="nikkah">
        <div class="product-img-container">
          <img src="images/Nikkah/Product5.jpg" alt="Premium Suit Box" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Nikkah Item</span>
          <h3 class="product-title">Nikkah Thumb Frame (Square)</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(31)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 1500,00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="nikkah">
        <div class="product-img-container">
          <img src="images/Nikkah/Product5.jpg" alt="Premium Suit Box" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Nikkah Item</span>
          <h3 class="product-title">Nikkah Thumb Frame (Square)</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(31)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 1500,00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>
      <div class="product-card" data-category="nikkah">
        <div class="product-img-container">
          <img src="images/Nikkah/Product5.jpg" alt="Premium Suit Box" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Nikkah Item</span>
          <h3 class="product-title">Nikkah Thumb Frame (Square)</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(31)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 1500,00</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>

      <!-- Hajj Mubarak Decor Set -->
      <div class="product-card" data-category="hajj">
        <span class="product-badge">Popular</span>
        <div class="product-img-container">
          <img src="images/Hajj/Product1.jpg" alt="Hajj Mubarak Decor Set" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Hajj Decor</span>
          <h3 class="product-title">Hajj Mubarak Decor Set</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(24)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 1,250</span>
            <span class="original-price">PKR 1,500</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>

      <!-- Hajj Cake Topper -->
      <div class="product-card" data-category="hajj">
        <div class="product-img-container">
          <img src="images/Hajj/Product2.jpg" alt="Hajj Cake Topper" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button><button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Hajj Decor</span>
          <h3 class="product-title">Hajj Cake Topper</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="far fa-star"></i>
            </div>
            <span class="rating-count">(18)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 150</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>

      <!-- Hajj Tabarruk Boxes -->
      <div class="product-card" data-category="hajj">
        <span class="product-badge">Best Value</span>
        <div class="product-img-container">
          <img src="images/Hajj/Product5.jpg" alt="Hajj Tabarruk Boxes" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Hajj Decor</span>
          <h3 class="product-title">Hajj Tabarruk Boxes (Set of 10)</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(32)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 550</span>
            <span class="original-price">PKR 650</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>

      <!-- Hajj Tabarruk Hang Bags -->
      <div class="product-card" data-category="hajj">
        <div class="product-img-container">
          <img src="images/Hajj/Product6.jpg" alt="Hajj Tabarruk Hang Bags" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Hajj Decor</span>
          <h3 class="product-title">Hajj Tabarruk Hang Bags (Set of 5)</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
            <span class="rating-count">(15)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 600</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>

      <!-- Hajj Mubarak Card Chain -->
      <div class="product-card" data-category="hajj">
        <span class="product-badge">New</span>
        <div class="product-img-container">
          <img src="images/Hajj/Product3.jpg" alt="Hajj Mubarak Card Chain" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Card Chain', 250, 'images/Hajj/Product3.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Card Chain', 'images/Hajj/Product3.jpg', 250)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Hajj Decor</span>
          <h3 class="product-title">Hajj Mubarak Card Chain</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="far fa-star"></i>
            </div>
            <span class="rating-count">(8)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 250</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Card Chain', 250, 'images/Hajj/Product3.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
        </div>
      </div>

      <!-- Hajj Mubarak Cake -->
      <div class="product-card" data-category="hajj">
        <div class="product-img-container">
          <img src="images/Hajj/Product4.jpg" alt="Hajj Mubarak Cake" class="product-img">
          <div class="product-actions">
            <button class="action-btn" title="Quick View" onclick="quickViewProduct('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
  <i class="fas fa-eye"></i>
</button>
            <button class="action-btn" title="Add to Wishlist"
  onclick="addToWishlist('Hajj Mubarak Cake (2kg)', 'images/Hajj/Product4.jpg', 4500)">
  <i class="far fa-heart"></i>
</button>
          </div>
        </div>
        <div class="product-info">
          <span class="product-category">Hajj Decor</span>
          <h3 class="product-title">Hajj Mubarak Cake (2kg)</h3>
          <div class="rating">
            <div class="rating-stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <span class="rating-count">(42)</span>
          </div>
          <div class="product-price">
            <span class="current-price">PKR 4,500</span>
            <span class="original-price">PKR 5,000</span>
          </div>
          <button class="add-to-cart" onclick="addToCart('Hajj Mubarak Cake (2kg)', 4500, 'images/Hajj/Product4.jpg')">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
          <!-- <button onclick="">Add to Cart</button> -->
        </div>
      </div>
    </main>
    <p id="noResultsMsg" style="display: none; text-align: center; font-weight: bold; color: red; padding: 20px;">
  No products were found matching your selection.
</p>
  </div>
<script>
  function searchProducts() {
  const input = document.getElementById("searchInput").value.toLowerCase();
  const cards = document.querySelectorAll(".product-card");
  let visibleCount = 0;

  cards.forEach(card => {
    const title = card.querySelector(".product-title").textContent.toLowerCase();
    const matches = title.includes(input);
    card.style.display = matches ? "block" : "none";
    if (matches) visibleCount++;
  });

  // Show or hide the no-results message
  const msg = document.getElementById("noResultsMsg");
  msg.style.display = visibleCount === 0 ? "block" : "none";
}

</script>
<script>
  document.getElementById("searchInput").addEventListener("keypress", function(e) {
    if (e.key === "Enter") {
      searchProducts();
    }
  });
</script>
<script>
  window.addEventListener("DOMContentLoaded", function () {
    const storedTerm = localStorage.getItem("searchTerm");
    if (storedTerm) {
      document.getElementById("searchInput").value = storedTerm;
      searchProducts(); // Reuse your search logic
      localStorage.removeItem("searchTerm"); // clear after search
    }
  });
</script>




  <!-- Your existing footer code here -->

  <script>
    // Function to extract URL parameters
    function getUrlParam(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }

    // On page load, check the URL and filter products
    document.addEventListener("DOMContentLoaded", function() {
        const category = getUrlParam("category") || "all"; // Default: show all
        filterProducts(category); // Apply the filter
    });

    // Your existing filter function
    function filterProducts(category) {
        const products = document.querySelectorAll(".product-card");
        products.forEach(product => {
            if (category === "all" || product.dataset.category === category) {
                product.style.display = "block"; // Show matching products
            } else {
                product.style.display = "none"; // Hide others
            }
        });
        
        // Highlight the active category in the sidebar
        const categoryLinks = document.querySelectorAll(".category-list li");
        categoryLinks.forEach(link => {
            link.classList.remove("active");
            if (link.textContent.toLowerCase().includes(category)) {
                link.classList.add("active");
            }
        });
    }
</script>

<script>
  // Example "Add to Cart" function in shop.html
function addToCart(name, price, image) {
  let cart = JSON.parse(localStorage.getItem('cart')) || [];

  // Check if item already exists
  const existingItemIndex = cart.findIndex(item => item.name === name);
  if (existingItemIndex !== -1) {
    cart[existingItemIndex].quantity += 1;
  } else {
    cart.push({ name, price, image, quantity: 1 });
  }

  localStorage.setItem('cart', JSON.stringify(cart));
  window.location.href = "cart.html"; // Redirect to cart
}
</script>

<!-- Price Filter -->
<script>
  // Price Filter Functionality
const priceSlider = document.getElementById('priceRange');
const maxPriceDisplay = document.getElementById('maxPrice');

// Update price display as slider moves
priceSlider.addEventListener('input', function() {
  maxPriceDisplay.textContent = `Rs${this.value}`;
});

// Function to apply price filter
function applyPriceFilter() {
  const maxPrice = parseInt(priceSlider.value);
  const products = document.querySelectorAll('.product-card');
  
  products.forEach(product => {
    // Get the product price (remove "Rs" or "PKR" and commas, then parse to number)
    const priceText = product.querySelector('.current-price').textContent;
    const price = parseInt(priceText.replace(/[^\d]/g, ''));
    
    if (price <= maxPrice) {
      product.style.display = 'block';
    } else {
      product.style.display = 'none';
    }
  });
  
  // Update the active filter display
  document.querySelector('.product-grid-title').textContent = 
    `Products under Rs${maxPrice}`;
}

// Make sure price filter works with category filter
function filterProducts(category) {
  // Update active category in sidebar
  const categoryItems = document.querySelectorAll('.category-list li');
  categoryItems.forEach(item => {
    item.classList.remove('active');
    if (item.getAttribute('onclick').includes(`'${category}'`)) {
      item.classList.add('active');
    }
  });
  
  // Reset price slider to show all prices when changing category
  priceSlider.value = 5000;
  maxPriceDisplay.textContent = 'Rs5,000';
  
  // Filter products by category
  const products = document.querySelectorAll('.product-card');
  products.forEach(product => {
    if (category === 'all' || product.dataset.category === category) {
      product.style.display = 'block';
    } else {
      product.style.display = 'none';
    }
  });
  
  // Update grid title
  const activeCategory = document.querySelector('.category-list li.active');
const titleElement = document.querySelector('.product-grid-title');

if (titleElement && activeCategory) {
  titleElement.textContent = category === 'all' ? 'All Products' : activeCategory.textContent;
}
closeSidebar();
}
</script>

<!-- Add To Wishlist -->
<script>
  function addToWishlist(name, image, price) {
    const wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];

    const exists = wishlist.find(item => item.name === name);
    if (!exists) {
      wishlist.push({
        name: name,
        image: image,
        price: price,
        dateAdded: new Date().toLocaleDateString()
      });
      localStorage.setItem('wishlist', JSON.stringify(wishlist));
    }
    window.location.href = 'wishlist.html';
  }
</script>

<!-- Quick view -->
<script>
  function quickViewProduct(name, price, image) {
    const product = { name, price, image };
    localStorage.setItem("quickViewProduct", JSON.stringify(product));
    window.location.href = "product-details.html";
  }
</script>




  <section class="newsletter-section">
        <h2 class="newsletter-header">Be The First To Get Discounts</h2>
        
        <div class="newsletter-container">
            <p class="newsletter-text">
                Get E-Mail Updates About Our Latest Shop And Special Offers.
            </p>
            
            <form class="newsletter-form">
                <input type="email" class="email-input" placeholder="Email" required>
                <button type="submit" class="subscribe-btn">Subscribe</button>
            </form>
        </div>
    </section>

    <footer class="footer">
        <div class="footer-container">
            <!-- First Column: Brand Info -->
            <div class="footer-column">
                <div class="brand-name">Elegant Events</div>
                <p class="about-text">
                    At Elegant Events, we specialize in making your celebrations unforgettable. From birthdays to weddings, festivals, and beyond, our diverse range of decoration items brings joy and elegance to every occasion.
                </p>
                <div class="social-bar">
                    <a href="https://www.facebook.com/ELEGANT-EVENT-MANAGERS-100063915645993/" class="social-icon" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/explore/locations/223978067695106/elegant-event-managers/" class="social-icon" target="_blank"><i class="fab fa-instagram"></i></a>
                    <!-- <a href="#" class="social-icon" target="_blank"><i class="fab fa-snapchat-ghost"></i></a>
                    <a href="#" class="social-icon" target="_blank"><i class="fab fa-tiktok"></i></a> -->
                    <a href="https://www.youtube.com/@amadtip" class="social-icon" target="_blank"><i class="fab fa-youtube"></i></a>
                  </div>
            </div>
            
            <!-- Second Column: Quick Links -->
            <div class="footer-column">
                <h3 class="footer-title">Quick Links</h3>
                <ul class="footer-links">
                    <li><a href="AboutUS.html">About Us</a></li>
                    <li><a href="ContactUs.html">Contact Us</a></li>
                    <li><a href="WholeSale.html">Whole Sale</a></li>
                    <li><a href="faqs.html">FAQs</a></li>
                    <li><a href="order-tracking.html">Order Tracking</a></li>
                    <li><a href="privacy-policy.html">Privacy Policy</a></li>
                    <li><a href="terms-and-conditions.html">Terms and Conditions</a></li>
                </ul>
            </div>
            
            <!-- Third Column: Get In Touch -->
            <div class="footer-column">
                <h3 class="footer-title">Get In Touch</h3>
                <div class="contact-info">
                    <p><strong>Email:</strong> amadtip@gmail.com</p>
                    <p><strong>Phone:</strong> 0333 2243753</p>
                </div>
            </div>
        </div>
    </footer>

<!-- WhatsApp Floating Chat -->
<div class="whatsapp-chat">
  <a href="https://wa.me/923332243753" target="_blank" class="whatsapp-link">
    <span class="chat-text">Need Help? <strong>Chat with us</strong></span>
    <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp" class="chat-icon" />
  </a>
</div>

<style>
  .whatsapp-chat {
    position: fixed;
    bottom: 70px;
    right: 10px;
    z-index: 9999;
    display: flex;
    align-items: center;
  }

  .whatsapp-link {
    display: flex;
    align-items: center;
    background: #fff;
    border-radius: 40px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
    text-decoration: none;
    padding: 12px 18px;
    color: #000;
    font-size: 16px;
    font-weight: 500;
    transition: all 0.3s ease;
  }

  .whatsapp-link:hover {
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
  }

  .chat-text {
    margin-right: 12px;
    white-space: nowrap;
  }

  .chat-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #25D366;
    padding: 5px;
  }

  /* Responsive Adjustments */
  @media (max-width: 600px) {
    .whatsapp-link {
      padding: 10px 14px;
      font-size: 14px;
    }

    .chat-text {
      display: none;
    }

    .chat-icon {
      width: 38px;
      height: 38px;
      padding: 4px;
    }
  }
</style>

</body>
</html>