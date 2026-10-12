// Dữ liệu mẫu sản phẩm Tạp hóa
let cart = [
  {
    id: 1,
    name: "Cà chua sạch (500g)",
    price: 16000,
    quantity: 2,
    image: "https://images.unsplash.com/photo-1592924357228-91a4daadcfea?auto=format&fit=crop&w=300&q=80"
  },
  {
    id: 2,
    name: "Chuối già Nam Mỹ (1kg)",
    price: 28000,
    quantity: 1,
    image: "https://hoptacxathanhbinh.com/wp-content/uploads/2023/08/1-3.jpg"
  }
];

const shippingFee = 15000; // Phí ship tạp hóa nội thành

// Danh sách các mã giảm giá hợp lệ
const VALID_COUPONS = {
  "TAPHOA10": { type: "percent", value: 10, minOrder: 0, description: "Giảm 10%" },
  "TAPHOA20K": { type: "fixed", value: 20000, minOrder: 50000, description: "Giảm 20.000đ cho đơn từ 50.000đ" },
  "FREESHIP": { type: "fixed", value: 15000, minOrder: 30000, description: "Miễn phí vận chuyển (Giảm 15.000đ)" }
};

// Trạng thái mã giảm giá đang áp dụng
let appliedCoupon = null;

// Format tiền VNĐ
function formatVND(amount) {
  return amount.toLocaleString('vi-VN') + ' đ';
}

// === CÁC HÀM ĐIỀU KHIỂN MINI CART (SLIDE-OVER BÊN PHẢI) ===
function openMiniCart() {
  document.getElementById('mini-cart').classList.add('active');
  document.getElementById('cart-overlay').classList.add('active');
  renderMiniCart();
}

function closeMiniCart() {
  document.getElementById('mini-cart').classList.remove('active');
  document.getElementById('cart-overlay').classList.remove('active');
}

function renderMiniCart() {
  const miniCartBody = document.getElementById('mini-cart-body');
  const miniCartFooter = document.getElementById('mini-cart-footer');

  if (!miniCartBody) return;

  if (cart.length === 0) {
    // HIỂN THỊ TRẠNG THÁI TRỐNG GIỐNG ẢNH MẪU
    miniCartBody.innerHTML = `
      <div class="empty-cart-drawer">
        <i class="fa-solid fa-basket-shopping"></i>
        <p>Giỏ hàng của bạn hiện đang trống.</p>
        <a href="sanpham.html" class="btn-view-products" onclick="closeMiniCart()">XEM SẢN PHẨM</a>
      </div>
    `;
    if (miniCartFooter) miniCartFooter.style.display = 'none';
  } else {
    // HIỂN THỊ DANH SÁCH SẢN PHẨM BÊN TRONG MINI CART
    let html = '';
    let subtotal = 0;

    cart.forEach(item => {
      subtotal += item.price * item.quantity;
      html += `
        <div class="cart-item" style="padding: 10px 0; border-bottom: 1px solid #e2e8f0; display: flex; gap: 10px; align-items: center;">
          <img src="${item.image}" alt="${item.name}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
          <div style="flex: 1;">
            <div style="font-size: 13px; font-weight: 600;">${item.name}</div>
            <div style="font-size: 12px; color: var(--primary); font-weight: bold;">${formatVND(item.price)} x ${item.quantity}</div>
          </div>
          <i class="fa-solid fa-trash-can btn-delete" onclick="deleteItem(${item.id})" style="cursor: pointer;"></i>
        </div>
      `;
    });

    miniCartBody.innerHTML = html;
    if (miniCartFooter) {
      miniCartFooter.style.display = 'block';
      document.getElementById('mini-cart-total').innerText = formatVND(subtotal);
    }
  }
}

// === RENDER GIỎ HÀNG CHÍNH Ở TRANG THANH TOÁN ===
function renderCart() {
  const cartList = document.getElementById('cart-items');
  if (!cartList) return;
  cartList.innerHTML = '';

  if (cart.length === 0) {
    cartList.innerHTML = `
      <div class="empty-cart-box">
        <i class="fa-solid fa-basket-shopping empty-cart-icon"></i>
        <p class="empty-cart-text">Chưa có mặt hàng nào trong giỏ</p>
        <a href="sanpham.html" class="btn-continue-shopping">
          <i class="fa-solid fa-cart-plus"></i> Quay lại mua sản phẩm
        </a>
      </div>
    `;

    document.getElementById('subtotal').innerText = '0 đ';
    document.getElementById('shipping').innerText = '0 đ';
    document.getElementById('total-price').innerText = '0 đ';
    document.getElementById('cart-count').innerText = '0';
    
    const discountRow = document.getElementById("discount-row");
    if (discountRow) discountRow.style.display = "none";
    
    appliedCoupon = null;
    const couponMsg = document.getElementById("coupon-message");
    if (couponMsg) couponMsg.innerText = "";
    
    renderMiniCart();
    return;
  }

  let subtotal = 0;
  let totalCount = 0;

  cart.forEach(item => {
    subtotal += item.price * item.quantity;
    totalCount += item.quantity;

    const itemRow = document.createElement('div');
    itemRow.className = 'cart-item';
    itemRow.innerHTML = `
      <img src="${item.image}" alt="${item.name}">
      <div class="item-info">
        <div class="item-title">${item.name}</div>
        <div class="item-price">${formatVND(item.price)}</div>
        <div class="qty-box">
          <button class="qty-btn" onclick="updateQty(${item.id}, -1)">-</button>
          <span>${item.quantity}</span>
          <button class="qty-btn" onclick="updateQty(${item.id}, 1)">+</button>
        </div>
      </div>
      <i class="fa-solid fa-trash-can btn-delete" onclick="deleteItem(${item.id})" title="Xóa"></i>
    `;
    cartList.appendChild(itemRow);
  });

  let discountAmount = 0;
  const couponMsg = document.getElementById("coupon-message");

  if (appliedCoupon && VALID_COUPONS[appliedCoupon]) {
    const coupon = VALID_COUPONS[appliedCoupon];
    if (subtotal >= coupon.minOrder) {
      if (coupon.type === "percent") {
        discountAmount = Math.round((subtotal * coupon.value) / 100);
      } else if (coupon.type === "fixed") {
        discountAmount = coupon.value;
      }
    } else {
      appliedCoupon = null;
      if (couponMsg) {
        couponMsg.innerText = `Mã bị hủy do đơn hàng dưới ${formatVND(coupon.minOrder)}.`;
        couponMsg.className = "coupon-message error";
      }
    }
  }

  const totalPrice = Math.max(0, subtotal + shippingFee - discountAmount);

  document.getElementById('subtotal').innerText = formatVND(subtotal);
  document.getElementById('shipping').innerText = formatVND(shippingFee);
  document.getElementById('total-price').innerText = formatVND(totalPrice);
  document.getElementById('cart-count').innerText = totalCount;

  const discountRow = document.getElementById("discount-row");
  if (discountRow) {
    if (discountAmount > 0) {
      document.getElementById("applied-coupon-code").innerText = appliedCoupon;
      document.getElementById("discount-amount").innerText = "- " + formatVND(discountAmount);
      discountRow.style.display = "flex";
    } else {
      discountRow.style.display = "none";
    }
  }

  renderMiniCart();
}

// Tăng / Giảm số lượng
function updateQty(id, change) {
  const item = cart.find(p => p.id === id);
  if (item) {
    item.quantity += change;
    if (item.quantity <= 0) {
      deleteItem(id);
    } else {
      renderCart();
    }
  }
}

// Xóa sản phẩm
function deleteItem(id) {
  cart = cart.filter(p => p.id !== id);
  renderCart();
}

// Áp dụng mã giảm giá
function applyCoupon() {
  const couponInput = document.getElementById("coupon-code");
  const couponMsg = document.getElementById("coupon-message");

  if (!couponInput || !couponMsg) return;

  const code = couponInput.value.trim().toUpperCase();

  if (cart.length === 0) {
    couponMsg.innerText = "Giỏ hàng đang trống, không thể áp dụng!";
    couponMsg.className = "coupon-message error";
    return;
  }

  if (!code) {
    couponMsg.innerText = "Vui lòng nhập mã giảm giá!";
    couponMsg.className = "coupon-message error";
    return;
  }

  const currentSubtotal = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);

  if (VALID_COUPONS[code]) {
    const coupon = VALID_COUPONS[code];
    if (currentSubtotal < coupon.minOrder) {
      couponMsg.innerText = `Mã này chỉ áp dụng cho đơn hàng từ ${formatVND(coupon.minOrder)} trở lên.`;
      couponMsg.className = "coupon-message error";
    } else {
      appliedCoupon = code;
      couponMsg.innerText = `Áp dụng thành công mã ${code}! (${coupon.description})`;
      couponMsg.className = "coupon-message success";
      renderCart();
    }
  } else {
    couponMsg.innerText = "Mã giảm giá không hợp lệ hoặc đã hết hạn!";
    couponMsg.className = "coupon-message error";
  }
}

// Form Đặt Hàng
const checkoutForm = document.getElementById('checkout-form');
if (checkoutForm) {
  checkoutForm.addEventListener('submit', function(e) {
    e.preventDefault();

    if (cart.length === 0) {
      alert('Giỏ hàng của bạn đang trống!');
      return;
    }

    const orderId = '#TH' + Math.floor(1000 + Math.random() * 9000);
    document.getElementById('order-id').innerText = orderId;
    document.getElementById('success-modal').style.display = 'flex';

    cart = [];
    appliedCoupon = null;
    
    const couponInput = document.getElementById("coupon-code");
    const couponMsg = document.getElementById("coupon-message");
    if (couponInput) couponInput.value = "";
    if (couponMsg) couponMsg.innerText = "";

    renderCart();
    this.reset();
  });
}

function closeModal() {
  document.getElementById('success-modal').style.display = 'none';
}

// Khởi tạo ban đầu
document.addEventListener("DOMContentLoaded", () => {
  const applyBtn = document.getElementById("btn-apply-coupon");
  if (applyBtn) {
    applyBtn.addEventListener("click", applyCoupon);
  }

  const couponInput = document.getElementById("coupon-code");
  if (couponInput) {
    couponInput.addEventListener("keypress", (e) => {
      if (e.key === "Enter") {
        e.preventDefault();
        applyCoupon();
      }
    });
  }

  renderCart();
});
