<!-- Säänäyttö ver 1.0.0 | Mika Autio, Valakia Interactive Oy -->
<!-- Ikonikirjasto: Font Awesome 4.7.0 -->
<!-- Säätieto: OpenWeatherMap API -->

<html>
<head>
   <meta charset="utf-8">
   <title>Sää</title>
   <link rel="stylesheet" href="style.css">
   <link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div class="container">
<?php




$city = vaasa; // Muokkaa tähän haluttu kaupunki. Muista ääkköset eli Seinäjoki on seinaejoki




// OpenWeatherMap API -haku määritellään tässä
$apiurl = 'http://api.openweathermap.org/data/2.5/weather?APPID=844ec52fd7f49c86d89c303e72748e20&units=metric&q=' . $city;
$response = file_get_contents($apiurl);
$weather = json_decode($response);

// Haetaan tiedoista lämpötila ja sanitoidaan että jää vain numerot (desimaalilla), pyöristetään se ja muutetaan -0 nollaksi
$temp = $weather->main->temp;
$sanitized_temp = filter_var($temp, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
$rounded_temp = round($sanitized_temp, 0);

if (strpos($rounded_temp, '-0') !== false) {
    $final_temp = '0';
} else {
    $final_temp = $rounded_temp;
}

// Haetaan tiedoista paikkakunnan nimi, sanitoidaan että jää vain kirjaimet ja muutetaan ae ja oe oikeiksi kirjaimiksi
$cityname = $weather->name;
$sanitized_cityname = filter_var($cityname, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
$search = array("ae", "oe");
$replace = array("ä", "ö");
$real_cityname = str_replace($search, $replace, $sanitized_cityname);

// Haetaan tiedoista säätila, sanitoidaan että jää vain kirjaimet ja numerot sekä liitetään ikonit eri säätiloihin
$condition = $weather->weather[0]->icon;
$sanitized_condition = filter_var($condition, FILTER_SANITIZE_STRING);
$search1 = array("01d", "01n", "02d", "02n", "03d", "03n", "04d", "04n", "09d", "09n", "10d", "10n", "11d", "11n", "13d", "13n", "50d", "50n");
$replace1 = array("fa fa-sun-o fa-4x", "fa fa-moon-o fa-4x", "fa fa-cloud fa-4x", "fa fa-cloud fa-4x", "fa fa-cloud fa-4x", "fa fa-cloud fa-4x", "fa fa-cloud fa-4x", "fa fa-cloud fa-4x", "fa fa-tint fa-4x", "fa fa-tint fa-4x", "fa fa-tint fa-4x", "fa fa-tint fa-4x", "fa fa-bolt fa-4x", "fa fa-bolt fa-4x", "fa fa-snowflake-o fa-4x", "fa fa-snowflake-o fa-4x", "fa fa-cloud fa-4x", "fa fa-cloud fa-4x");
$real_condition = str_replace($search1, $replace1, $sanitized_condition);

// Asetetaan aikavyöhyke ja kieli sekä haetaan päivä ja aika
date_default_timezone_set(DateTimeZone::listIdentifiers(DateTimeZone::UTC)[0]);
setlocale(LC_TIME, "fi_FI");
$pv = strftime("%A ");

if (date('I', time()) == 1) {
    $today = date("G.i", strtotime('+1 hours'));
} else {
    $today = date("G.i", strtotime('+2 hours'));
}

// Tulostetaan asiat
echo '<h2><span style="text-transform: uppercase;">' . $real_cityname . "</span><br>" . $pv . $today . '</h2>';
echo '<h1><span style="margin-left: 30px; margin-right: 15px;">' . $final_temp . '&#176;</span></h1><i style="color: white;" class="' . $real_condition . '" aria-hidden="true"></i>';
?>
</div>
</body>
</html>