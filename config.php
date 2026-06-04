<?php 

$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

$cleanHost = parse_url('https://' . $host, PHP_URL_HOST);

return [
    'BASE_URL' => 'https://www.recyclepro.co.uk/shop/',
    'API_URL'  => 'https://www.recyclepro.co.uk/rp-dashboard/',
    'STRIPE_SECRET_KEY' => getenv('RECYCLEPRO_STRIPE_SECRET_KEY') ?: '',
    'STRIPE_WEBHOOK_SECRET' => getenv('RECYCLEPRO_STRIPE_WEBHOOK_SECRET') ?: '',
];
