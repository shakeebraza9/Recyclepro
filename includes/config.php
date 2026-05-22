<?php
$is_localhost = False;

return [
    // 'BASE_URL' => $is_localhost ? 'http://localhost:8080/shop/' : 'https://www.recyclepro.co.uk/',
    'BASE_URL' =>  'http://localhost:8080/shop/',
    'API_URL'  => $is_localhost ? 'http://localhost:8080/bkrecyclepro/wp-json/' : 'https://www.recyclepro.co.uk/rp-dashboard/',
];