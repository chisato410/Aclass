<?php
session_start();
session_regenerate_id(true);

if (!isset($_SESSION['member_login'])) {
  $member_message = <<<HTML
ようこそ、ゲスト様
<a href="member_login.html" class="login__btn">会員ログイン</a><br><br>
HTML;
} else {
  $name = htmlspecialchars($_SESSION['member_name'], ENT_QUOTES, 'UTF-8');
  $member_message = <<<HTML
ようこそ、{$name}様
<a href="member_logout.php" class="login__btn">ログアウト</a><br><br>
HTML;
}
?>
<!doctype html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>商品カート | ろくまる農園</title>
  <?php include '../common/head.php'; ?>
</head>

<body>
  <?php include '../common/header.php'; ?>

  <main class="main">
    <div class="main__inner">
      <?php echo $member_message; ?>

      <?php
      try {
        if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
          $cart = $_SESSION['cart'];
          $kazu = $_SESSION['kazu'];
          $max = count($cart);
        } else {
          $max = 0;
        }

        if ($max === 0) {
          echo '<p>カートに商品が入っていません。</p>';
          echo '<a href="shop_list.php" class="link-back">商品一覧に戻る</a>';
          exit();
        }

        $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8mb4';
        $user = 'root';
        $password = 'root';
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $total = 0;

        foreach ($cart as $key => $val) {
          $sql = 'SELECT code,name,price,gazou FROM mst_product WHERE code=?';
          $stmt = $dbh->prepare($sql);
          $stmt->execute([$val]);

          $rec = $stmt->fetch(PDO::FETCH_ASSOC);

          $pro_name[] = htmlspecialchars($rec['name'], ENT_QUOTES, 'UTF-8');
          $pro_price[] = $rec['price'];
          $total += $rec['price'] * $kazu[$key];

          $pro_gazou_name = $rec['gazou'];
          if ($pro_gazou_name) {
            $gazou_file = '../product/gazou/' . $pro_gazou_name;
            $pro_gazou[] = '<img src="' . $gazou_file . '" alt="">';
          } else {
            $pro_gazou[] = '';
          }
        }
        $dbh = null;
      } catch (Exception $e) {
        echo '<p>ただいま障害によりご迷惑をおかけしております。</p>';
        exit();
      }
      ?>

      <p>カートの中身</p>
      <div class="cart-message" id="cart-message"></div>
      <form method="post" action="kazu_change.php" id="cart-form">
        <table border="1" class="cart-table">
          <thead>
            <tr>
              <th>商品</th>
              <th>画像</th>
              <th>価格</th>
              <th>数量</th>
              <th>小計</th>
              <th>削除</th>
            </tr>
          </thead>
          <tbody>
            <?php for ($i = 0; $i < $max; $i++) : ?>
              <tr>
                <td><?php echo $pro_name[$i]; ?></td>
                <td>
                  <div class="gazou_wrap"><?php echo $pro_gazou[$i]; ?></div>
                </td>
                <td><?php echo number_format($pro_price[$i]); ?>円</td>
                <td>
                  <div class="quantity-wrapper">
                    <button type="button" class="quantity-btn" onclick="changeQuantity(<?php echo $i; ?>, -1)">-</button>
                    <input type="number" name="kazu<?php echo $i; ?>" id="kazu<?php echo $i; ?>" value="<?php echo $kazu[$i]; ?>" class="cart-quantity" min="1">
                    <button type="button" class="quantity-btn" onclick="changeQuantity(<?php echo $i; ?>, 1)">+</button>
                  </div>
                </td>
                <td id="subtotal<?php echo $i; ?>">
                  <?php echo number_format($pro_price[$i] * $kazu[$i]); ?>円
                </td>
                <td><input value="on" type="checkbox" name="sakujo<?php echo $i; ?>"></td>
              </tr>
            <?php endfor; ?>
          </tbody>
        </table>
        <div class="cart-total">
          合計：<span id="total-price"><?php echo number_format($total); ?></span>円
        </div>
        <input type="hidden" name="max" value="<?php echo $max; ?>">
        <input type="submit" value="数量変更" class="main__submit">
      </form>

      <a href="shop_list.php" class="link-back">商品一覧に戻る</a>
      <br><br>
      <a href="shop_form.html" class="main__btn">購入手続きへ進む</a>
      <br><br>
      <?php if (isset($_SESSION["member_login"])) : ?>
        <a href="shop_kantan_check.php" class="main__btn">会員かんたん注文へ進む</a>
      <?php endif; ?>
    </div>
  </main>

  <script>
    function changeQuantity(index, delta) {
      const input = document.getElementById(`kazu${index}`);
      let value = parseInt(input.value) + delta;
      if (value < 1) value = 1;
      input.value = value;
      updateMessage("数量を変更しました。");
      updateSubtotal(index);
      updateTotal();
    }

    function updateMessage(msg) {
      const msgBox = document.getElementById("cart-message");
      msgBox.textContent = msg;
      setTimeout(() => {
        msgBox.textContent = "";
      }, 2000);
    }

    function updateSubtotal(index) {
      const price = <?php echo json_encode($pro_price); ?>;
      const input = document.getElementById(`kazu${index}`);
      const subtotal = price[index] * parseInt(input.value);
      document.getElementById(`subtotal${index}`).textContent = subtotal.toLocaleString() + "円";
    }

    function updateTotal() {
      const price = <?php echo json_encode($pro_price); ?>;
      let total = 0;
      for (let i = 0; i < price.length; i++) {
        const quantity = parseInt(document.getElementById(`kazu${i}`).value);
        total += price[i] * quantity;
      }
      document.getElementById("total-price").textContent = total.toLocaleString();
    }

    document.querySelectorAll(".cart-quantity").forEach((input, i) => {
      input.addEventListener("change", () => {
        if (input.value < 1) input.value = 1;
        updateMessage("数量を変更しました。");
        updateSubtotal(i);
        updateTotal();
      });
    });
  </script>
</body>

</html>