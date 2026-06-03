<?php 

$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

$cleanHost = parse_url('https://' . $host, PHP_URL_HOST);

return [
    'BASE_URL' => 'https://www.recyclepro.co.uk/shop/',
    'API_URL'  => 'https://www.recyclepro.co.uk/rp-dashboard/',
];