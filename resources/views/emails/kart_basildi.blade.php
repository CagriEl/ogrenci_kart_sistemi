<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartınız Basıldı</title>
</head>
<body>
    <h1>Merhaba, Sayın {{ $adSoyad }}</h1>
    <p>Öğrenci kartı basılmıştır ve sicil numaranız oluşturulmuştur.</p>
    <p>Sicil Numaranız: <strong>{{ $sicil }}</strong></p>

    <p>39 Kent Kart ödemenizi <a href="https://e-belediye.kirklareli.bel.tr">E-Belediye</a> sistemi üzerinden veya belediye veznelerimizden ödeyebilirsiniz.
       <br> <br>Teşekkür ederiz.</p>
        
    <h3> Not: Kartınızı teslim almak için kart ücretini  30 gün içerisinde ödemeniz gerekmektedir. <br>Ödemenizi gerçekleştirdikten sonra ödeme dekontunuz ile beraber 
            kartınızı <br>Kırklareli Belediye Başkanlığı Ulaşım Hizmetleri Müdürlüğünden alabilirsiniz.
        <br>Adres: Karaca İbrahim Mahallesi. Kurtuluş Cd. Ulaşım Hizmetleri Müdürlüğü
    </h3>
    <img src="https://bilet.kirklareli.bel.tr/public/imza.jpg">
</body>
</html>
