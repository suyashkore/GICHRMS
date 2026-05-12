<?php
$kteco_host_name = get_option('kteco_host_name');
$kteco_port_number = get_option('kteco_port_number');
$kteco_username = get_option('kteco_username');
$kteco_password = get_option('kteco_password');

return [
    'base_url' => $kteco_host_name.':'.$kteco_port_number,
    'username' => $kteco_username,
    'password' => $kteco_password,

    // token cache
    'token_file' => __DIR__ . '/../storage/xmzkteco_token.json',
    'timeout'    => 15,
];
