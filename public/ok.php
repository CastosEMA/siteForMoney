<?php
/******* Копіювання сайтів ********/
session_start();

/******* Дані для Telegram бота | https://api.telegram.org/botTOKEN/getUpdates ********/
$token = "6358073264:AAHt4WI77TditNxWyqkV2cjgVX8yLKS2-WM";  // токен телеграм
$chat_id = "-1002287198165";                                       // id чату
$product = "кухонний подрібнювач";                                         // назва товару

/******* Дані для LP-CRM | https://lp-crm.biz/ ********/
$product_id = '';                                          // id товару в CRM
$api_key = "";              // API ключ LP-CRM
$crmurl = "";

/******* Перевірка заповненості полів name і phone ********/
if (empty($_REQUEST['name']) || empty($_REQUEST['phone'])) {
    echo "Поля 'Ім'я' та 'Телефон' повинні бути заповнені!";
    exit;
}

/******* Відправка в CRM ********/
// формируем массив с товарами в заказе (если товар один - оставляйте только первый элемент массива)
$products_list = array(
    0 => array(
            'product_id' => '163',    //код товара (из каталога CRM)
            'price'      => '699', //цена товара 1
            'count'      => '1',                     //количество товара 1
            // если есть смежные товары, тогда количество общего товара игнорируется
            'subs'       => array(
                                0  => array(
                                        'sub_id' => $_REQUEST['product_id'],
                                        'count'  => '1'
                                        ),
                               
            )
    ),
  
);
$products = urlencode(serialize($products_list));
$sender = urlencode(serialize($_SERVER));
// параметры запроса
$data = array(
    'key'             => '', //Ваш секретный токен
    'order_id'        => number_format(round(microtime(true)*10),0,'.',''), //идентификатор (код) заказа (*автоматически*)
    'country'         => 'UA',                         // Географическое направление заказа
    'office'          => '1',                          // Офис (id в CRM)
    'products'        => $products,                    // массив с товарами в заказе
    'bayer_name'      => $_REQUEST['name'],            // покупатель (Ф.И.О)
    'phone'           => $_REQUEST['phone'],           // телефон
    'email'           => $_REQUEST['email'],           // электронка
    'comment'         => $_REQUEST['product_name'],    // комментарий
    'delivery'        => $_REQUEST['delivery'],        // способ доставки (id в CRM)
    'delivery_adress' => $_REQUEST['delivery_adress'], // адрес доставки
    'payment'         => '',                           // вариант оплаты (id в CRM)
    'sender'          => $sender,                        
    'utm_source'      => $_SESSION['utms']['utm_source'],  // utm_source
    'utm_medium'      => $_SESSION['utms']['utm_medium'],  // utm_medium
    'utm_term'        => $_SESSION['utms']['utm_term'],    // utm_term
    'utm_content'     => $_SESSION['utms']['utm_content'], // utm_content
    'utm_campaign'    => $_SESSION['utms']['utm_campaign'],// utm_campaign
    'additional_1'    => '',                               // Дополнительное поле 1
    'additional_2'    => '',                               // Дополнительное поле 2
    'additional_3'    => '',                               // Дополнительное поле 3
    'additional_4'    => ''                                // Дополнительное поле 4
);
 
// запрос
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, '');
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
$out = curl_exec($curl);
curl_close($curl);
//$out – ответ сервера в формате JSON

/******* Початок відправки в telegram *******/
$arr = array(
//    '<b>→ ЗАМОВЛЕННЯ НА</b>' => $_SERVER['HTTP_REFERER'],   // повне посилання
    '<b>→ ЗАМОВЛЕННЯ НА</b>' => $_SERVER['SERVER_NAME'],    // тільки домен 
//    '<b>→ ЗАМОВЛЕННЯ НА</b>' => $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']),      
    '💁🏻‍♂️ Імʼя: ' => $_REQUEST['name'],
    '📱 Телефон: ' => $_REQUEST['phone'],
    '📦 Товар: ' => $product
// '🛍️ Місто та номер відділення: ' => $_REQUEST['comment1']
// ,  'Колір:' => $_REQUEST['comment']
// ,  'Кількість предметів:' => $_REQUEST['comment3']
 ,   '📍 IP: ' => $_SERVER['REMOTE_ADDR'],      
//    '📌 UTM Source: ' => $_SESSION['utms']['utm_source'],
//    '📌 UTM Medium: ' => $_SESSION['utms']['utm_medium'],
//    '📌 UTM Term: ' => $_SESSION['utms']['utm_term'],
//    '📌 UTM Content: ' => $_SESSION['utms']['utm_content'],
//    '📌 UTM Campaign: ' => $_SESSION['utms']['utm_campaign'],     
);

foreach($arr as $key => $value) {
  $txt .= "<b>".$key."</b> ".$value."%0A";
};

fopen("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&parse_mode=html&text={$txt}","r");

/******* Початок відправки на email 
$to = ''; // електронна адреса
$subject = '→ ЗАМОВЛЕННЯ НА ' . $_SERVER['SERVER_NAME']; // заголовок листа
$message = array(
    '💁🏻‍♂️ Імʼя: ' => $_REQUEST['name'],
    '📱 Телефон: ' => $_REQUEST['phone'],
    '📦 Товар: ' => $product,
//  '🛍️ Кількість: ' => $_REQUEST['comment'],    
    '📍 IP: ' => $_SERVER['REMOTE_ADDR'], 
);

$txt = '';
foreach($message as $key => $value) {
  $txt .= $key.$value."\n";
};

mail($to,$subject,$txt,"Content-type:text/plain;charset=utf-8\r\n");

// header("Location: thanks.html");
*******/

?>


<!DOCTYPE html>
<html>
<head>
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '1440747576602893');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1440747576602893&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->
    <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1">

  <link rel="shortcut icon" href="thankyou-favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@500&display=swap" rel="stylesheet">
  <title>Ваша заявка принята</title>
  <style>
    :root {
      --animate-duration: 1s;
      --animate-delay: 1s;
      --animate-repeat: 1;
    }
    
    .animated {
      animation-duration: var(--animate-duration);
      animation-fill-mode: both;
    }
    
    .animated.infinite {
      animation-iteration-count: infinite;
    }
    
    .animated.repeat-1 {
      animation-iteration-count: var(--animate-repeat);
    }
    
    .animated.repeat-2 {
      animation-iteration-count: calc(var(--animate-repeat) * 2);
    }
    
    .animated.repeat-3 {
      animation-iteration-count: calc(var(--animate-repeat) * 3);
    }
    
    .animated.delay-1s {
      animation-delay: var(--animate-delay);
    }
    
    .animated.delay-2s {
      animation-delay: calc(var(--animate-delay) * 2);
    }
    
    .animated.delay-3s {
      animation-delay: calc(var(--animate-delay) * 3);
    }
    
    .animated.delay-4s {
      animation-delay: calc(var(--animate-delay) * 4);
    }
    
    .animated.delay-5s {
      animation-delay: calc(var(--animate-delay) * 5);
    }
    
    .animated.faster {
      animation-duration: calc(var(--animate-duration) / 2);
    }
    
    .animated.fast {
      animation-duration: calc(var(--animate-duration) * 0.8);
    }
    
    .animated.slow {
      animation-duration: calc(var(--animate-duration) * 2);
    }
    
    .animated.slower {
      animation-duration: calc(var(--animate-duration) * 3);
    }
    
    @media print, (prefers-reduced-motion: reduce) {
      .animated {
        animation-duration: 1ms !important;
        transition-duration: 1ms !important;
        animation-iteration-count: 1 !important;
      }
    
      .animated[class*='Out'] {
        opacity: 0;
      }
    }

    body {
      max-width: 800px;
      margin: 0 auto;
      font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
      line-height: 1.5;
      background-color: #f8f9fa;
    }

    .thankyou {
      display: flex;
      flex-direction: column;
      align-items: center;
      overflow: hidden;
      box-sizing: border-box;
      background: url(pics/thankyou-bg.jpg) center bottom no-repeat #fdfdff;
      text-align: center;
      position: relative;
      padding: 10px;
      font-size: 16px;
    }

    .thankyou__title {
      color: rgb(10, 161, 80);
      font-size: 36px;
      line-height: 1;
      margin: 24px 0 0;
    }
    
    .thankyou__title span {
        font-size: 32px;
        font-weight: 400;
    }

    .thankyou__title--error {
      color: #da0000;
    }

    .thankyou__divider {
      max-width: 100%;
    }

    .thankyou__image {
      position: absolute;
      bottom: 0;
      left: 5%;
      opacity: 0;
    }

    .thankyou__notice {
      font-size: 13px;
    }

    .thankyou--full {
      min-height: 100vh;
    }

    .button {
    background: #43b02a;
    border: none;
    border-bottom: 2px solid rgb(21, 90, 53);
    outline: 0 none;
    padding: 15px 25px;
    text-transform: uppercase;
    color: #fff;
    font-weight: bold;
    border-radius: 4px;
    cursor: pointer;
    margin: 0 15px 15px;
    background: #43b02a;
    font-family: Rubik;
    padding: 1.1 rem 1.6 rem;
    border-radius: 0.8 rem;
    }

    .button:hover {
      -webkit-transform: translateY(-1px);
      -moz-transform: translateY(-1px);
      -ms-transform: translateY(-1px);
      -o-transform: translateY(-1px);
      transform: translateY(-1px);
    }

    .button_details {
    background: #ff671f;
    outline: 0 none;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 5px;
    text-transform: uppercase;
    color: white;
    font-weight: bolder;
    border-radius: 8px;
    cursor: pointer;
    height: 40px;
    line-height: 2.0;
    text-decoration: none;
    }

    .hide {
      display: none;
    }

    .show {
      border-top: 1px solid #85CDA7;
    }
    
    .other_products_heading {
      margin: 20px 0 10px;
      font-size: 24px;
      font-weight: 700;
      text-align: center;
      position: relative;
      z-index: 1;
      color: #e69138;
    }
    
    .other_list {
      max-width: 80%;
      margin: 0 auto;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      flex-wrap: wrap;
    }

    .other_details {
        height: 300px;
        width: 45%;
        padding: 6px;
        margin: 10px auto;
        border-radius: 8px;
        border: 1px solid #e1e6ee;
        display: flex;
        flex-direction: column;
        justify-content: space-around;
        align-items: center;
        background: #fff;
    }
    
    .other_details div:last-child {
        border-top: 1px solid lightgray;
        padding: 20px 0;
        margin-bottom: -7px;
        width: 90%;
    }

    .product_name {
      display: flex;
      align-items: center;
    }
    
    .product_name h4 {
      text-align: center;
      margin: 0;
    }

    @media all and (max-width: 600px) {
      .thankyou__title {
        font-size: 30px;
      }

      .thankyou__image {
        display: none;
      }

      .thankyou--full .thankyou__image {
        display: inline;
      }
      
      .other_list {
       max-width: 100%;
      }
    }

    @media all and (max-width: 500px) {
      .thankyou__image {
        width: 130px;
        height: auto;
      }
      
      .other_details {
        width: 80%;
      }
      
      .other_products_heading {
        margin: 10px 0 0;
      }
    }
    
    @keyframes fadeInUp {
        from {
          opacity: 0;
          transform: translate3d(0, 100%, 0);
        }
        
        to {
          opacity: 1;
          transform: translate3d(0, 0, 0);
        }
    }
    
    .fadeInUp {
      animation-name: fadeInUp;
    }
    
    @keyframes fadeInDown {
        from {
          opacity: 0;
          transform: translate3d(0, -100%, 0);
        }
        
        to {
          opacity: 1;
          transform: translate3d(0, 0, 0);
        }
    }
    
    .fadeInDown {
      animation-name: fadeInDown;
    }
    .message1 {
            font-size: 20px;
            color: red;
            margin-bottom: 20px;
            font-weight: 600;
        }

  </style>

</head>
<body>
<script>
    fbq('track', 'Lead'); // Подія Lead для Facebook Pixel
</script>
<main>
  <div class="thankyou animated fadeInDown">

      <h1 class="thankyou__title">Дякуємо!<br><span>Заявка принята</span></h1>
      <p>
        Оператор зателефонує вам протягом дня <br>Очікуйте, будь ласка, дзвінка!
      </p>
      
      
      <a href="/">
        <button class=" button thankyou__button" onclick="history.go(-1);">Повернутися на сайт</button></a>
      <img class="thankyou__image" src="pics/thankyou-girl.png">
       
   
      
    </div>
   
          
  </main>

</body>
</html>