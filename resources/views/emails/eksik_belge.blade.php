<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>39 Kent Kart - Eksik Belge Bildirimi</title>
</head>
<body>
    <p>Sayın {{ $student->ad_soyad }},</p>
    <p>Başvurunuzda eksik veya hatalı belgeler bulunmaktadır. Lütfen aşağıdaki açıklamayı dikkate alarak başvurunuzu yeniden yapınız.</p>
    <p><strong>Açıklama:</strong></p>
    <p>{{ $aciklama }}</p>
    <p>Kırklareli Belediye Başkanlığı Ulaşım Hizmetleri Müdürlüğü</p>
    <a href="https://bilet.kirklareli.bel.tr">Online Başvuru</a>
</body>
</html>
