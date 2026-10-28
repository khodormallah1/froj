<?php
require 'config.php';
$cats = $con->query("SELECT * FROM categories ORDER BY id")->fetch_all(MYSQLI_ASSOC);
$items = [];
foreach ($con->query("SELECT * FROM items ORDER BY id")->fetch_all(MYSQLI_ASSOC) as $it) {
    $items[$it['category_id']][] = $it;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#111111">
<title>فروج وتوم | المنيو</title>
<meta name="description" content="فروج على الفحم، ساندويشات، وجبات، مقبلات ومشروبات. البابلية والجوّار. للطلب: 76 195 012">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;800&display=swap" rel="stylesheet">
<style>
:root{--bg:#111111;--card:#1b1b1b;--line:#2c2c2c;--text:#f5f1ea;--muted:#a8a29a;--accent:#f5a623;--green:#25d366;--red:#ff4757}
*{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{background:var(--bg);color:var(--text);font-family:'Cairo',Tahoma,sans-serif;line-height:1.6;padding-bottom:90px}
.wrap{max-width:720px;margin:0 auto;padding:0 16px}
.hero{text-align:center;padding:48px 16px 32px;background:radial-gradient(ellipse at top,#2a1d0a 0%,var(--bg) 70%)}
.hero h1{font-size:clamp(2.4rem,9vw,3.6rem);font-weight:800;color:var(--accent);line-height:1.2}
.hero p{color:var(--muted);margin:8px 0 24px;font-size:1.05rem}
.actions{display:flex;gap:10px;justify-content:center;flex-wrap:wrap}
.btn{display:inline-flex;align-items:center;gap:8px;padding:12px 20px;border-radius:999px;font:600 1rem 'Cairo',sans-serif;text-decoration:none;border:1px solid var(--line);color:var(--text);background:var(--card);cursor:pointer}
.btn.wa{background:var(--green);border-color:var(--green);color:#06210f}
.btn:focus-visible{outline:2px solid var(--accent);outline-offset:2px}
.menu-title{margin:32px 0 4px;font-size:1.8rem;font-weight:800}
.sub{color:var(--muted);margin-bottom:24px}
.cat{background:var(--card);border:1px solid var(--line);border-radius:18px;overflow:hidden;margin-bottom:20px}
.cat-img{height:250px;background:#2a2a2a center/cover no-repeat;position:relative}
.cat-img::after{content:"";position:absolute;inset:0;background:linear-gradient(transparent 30%,var(--card))}
.cat h3{position:absolute;z-index:1;bottom:10px;right:16px;font-size:1.35rem;font-weight:800}
.items{list-style:none;padding:4px 16px 12px}
.items li{display:flex;justify-content:space-between;align-items:center;gap:12px;padding:10px 0;border-bottom:1px dashed var(--line)}
.items li:last-child{border-bottom:0}
.name{font-weight:600}
.en{display:block;font-size:.8rem;color:var(--muted);font-weight:400;direction:ltr;text-align:right}
.price{color:var(--accent);font-weight:800;white-space:nowrap;direction:ltr}
.side{display:flex;align-items:center;gap:12px}
.qty{display:flex;align-items:center;gap:8px;direction:ltr}
.qty button{width:32px;height:32px;border-radius:50%;border:1px solid var(--line);background:var(--bg);color:var(--text);font-size:1.2rem;cursor:pointer;line-height:1}
.qty button.plus{background:var(--accent);border-color:var(--accent);color:#111;font-weight:800}
.qv{min-width:20px;text-align:center;font-weight:800}
li.sel .name{color:var(--accent)}
section.info{background:var(--card);border:1px solid var(--line);border-radius:18px;padding:20px;margin-bottom:20px}
section.info h2{font-size:1.3rem;margin-bottom:10px}
.open{display:inline-block;margin-top:8px;padding:2px 12px;border-radius:999px;font-size:.9rem;font-weight:600}
.open.yes{background:#143d22;color:#5be08b}
.open.no{background:#40161a;color:#ff7b86}
a.adm{text-decoration:none;opacity:.12;font-size:.75rem;margin-inline-start:6px}
a.adm:hover{opacity:.6}
footer{text-align:center;color:var(--muted);padding:24px 16px 40px;font-size:.9rem}
/* شريط الطلب */
.bar{position:fixed;bottom:0;left:0;right:0;background:#1b1b1bf2;border-top:1px solid var(--line);padding:12px 16px;display:none;z-index:10}
.bar.show{display:block}
.bar-in{max-width:720px;margin:0 auto;display:flex;justify-content:space-between;align-items:center;gap:12px}
.bar b{color:var(--accent)}
</style>
</head>
<body>

<header class="hero">
  <h1>فروج وتوم</h1>
  <p>طعم ولا أطيب… وجبة بتستاهل</p>
  <div class="actions">
    <a class="btn wa" id="waBtn" href="#" target="_blank" rel="noopener">اطلب عبر واتساب</a>
    <a class="btn" href="tel:+96176195012" dir="ltr">76 195 012</a>
    <a class="btn" id="mapBtn" href="#" target="_blank" rel="noopener">الاتجاهات</a>
  </div>
</header>

<main class="wrap">
  <h2 class="menu-title">🍗 المنيو</h2>
  <p class="sub">فروج على الفحم وساندويشات ووجبات ومقبلات ومشروبات. البابلية والجوّار</p>

  <?php foreach ($cats as $c): ?>
  <article class="cat">
    <div class="cat-img" <?php if ($c['image']): ?>style="background-image:url('<?= e(str_replace(' ', '%20', $c['image'])) ?>')"<?php endif; ?>>
      <h3><?= e($c['title']) ?></h3>
    </div>
    <ul class="items">
      <?php foreach ($items[$c['id']] ?? [] as $it): ?>
      <li class="item" data-id="<?= $it['id'] ?>" data-name="<?= e($it['name']) ?>" data-price="<?= e($it['price']) ?>">
        <div>
          <span class="name"><?= e($it['name']) ?></span>
          <?php if ($it['name_en']): ?><span class="en"><?= e($it['name_en']) ?></span><?php endif; ?>
        </div>
        <div class="side">
          <span class="price">$<?= number_format($it['price'], 2) ?></span>
          <div class="qty">
            <button type="button" data-d="-1" aria-label="نقص">−</button>
            <span class="qv">0</span>
            <button type="button" class="plus" data-d="1" aria-label="زيد">+</button>
          </div>
        </div>
      </li>
      <?php endforeach; ?>
    </ul>
  </article>
  <?php endforeach; ?>

  <section class="info">
    <h2>ساعات العمل</h2>
    <p>كل يوم من 11:00 صباحًا حتى 11:00 مساءً</p>
    <span class="open" id="openState"></span>
  </section>

  <section class="info">
    <h2>موقعنا</h2>
    <p>البابلية والجوّار — جنوب لبنان</p>
    <div class="actions" style="justify-content:flex-start;margin-top:12px">
      <a class="btn" id="mapBtn2" href="#" target="_blank" rel="noopener">الاتجاهات</a>
    </div>
  </section>

  <section class="info">
    <h2>اتصل بنا</h2>
    <p>فروج وتوم — البابلية والجوّار<br>للطلب والاستفسار: <span dir="ltr">76 195 012</span></p>
    <div class="actions" style="justify-content:flex-start;margin-top:12px">
      <a class="btn wa" id="waBtn2" href="#" target="_blank" rel="noopener">اطلب عبر واتساب</a>
      <a class="btn" href="tel:+96176195012">اتصال</a>
    </div>
  </section>
</main>

<footer>© فروج وتوم <a class="adm" href="login.php" aria-label="الإدارة" title="الإدارة">🔒</a></footer>

<div class="bar" id="bar">
  <div class="bar-in">
    <div><span id="cnt">0</span> صنف — المجموع <b id="total">$0.00</b></div>
    <button class="btn wa" id="orderBtn" type="button">اطلب</button>
  </div>
</div>

<script>
const PHONE = "<?= e(PHONE) ?>";
const LAT = 33.4193255, LNG = 35.3281368;
const OPEN_HOUR = 11, CLOSE_HOUR = 23;

const q = {};
const bar = document.getElementById("bar");

function update(){
  let n = 0, t = 0;
  document.querySelectorAll(".item").forEach(li => {
    const c = q[li.dataset.id] || 0;
    li.querySelector(".qv").textContent = c;
    li.classList.toggle("sel", c > 0);
    n += c; t += c * parseFloat(li.dataset.price);
  });
  document.getElementById("cnt").textContent = n;
  document.getElementById("total").textContent = "$" + t.toFixed(2);
  bar.classList.toggle("show", n > 0);
}

document.addEventListener("click", e => {
  const b = e.target.closest("[data-d]");
  if (!b) return;
  const id = b.closest(".item").dataset.id;
  q[id] = Math.max(0, (q[id] || 0) + Number(b.dataset.d));
  update();
});

document.getElementById("orderBtn").addEventListener("click", () => {
  let lines = [], total = 0;
  document.querySelectorAll(".item").forEach(li => {
    const c = q[li.dataset.id] || 0;
    if (c > 0){
      lines.push("- " + li.dataset.name + " × " + c);
      total += c * parseFloat(li.dataset.price);
    }
  });
  if (!lines.length) return;
  const text = "مرحبا بدي اطلب:\n" + lines.join("\n") + "\nالمجموع: $" + total.toFixed(2);
  window.open("https://wa.me/" + PHONE + "?text=" + encodeURIComponent(text), "_blank");
});

const wa = "https://wa.me/" + PHONE + "?text=" + encodeURIComponent("مرحبا، بدي أطلب");
const map = "https://www.google.com/maps/dir/?api=1&destination=" + LAT + "," + LNG;
["waBtn","waBtn2"].forEach(id => document.getElementById(id).href = wa);
["mapBtn","mapBtn2"].forEach(id => document.getElementById(id).href = map);

(function(){
  const h = new Date().getHours();
  const isOpen = h >= OPEN_HOUR && h < CLOSE_HOUR;
  const el = document.getElementById("openState");
  el.textContent = isOpen ? "مفتوح الآن" : "مغلق حاليًا";
  el.className = "open " + (isOpen ? "yes" : "no");
})();
</script>
</body>
</html>
