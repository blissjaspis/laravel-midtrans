<?php

return [
    /**
     * The server key is used to authenticate requests to the Midtrans API.
     */
    'server_key' => env('MIDTRANS_SERVER_KEY', ''),

    /**
     * The client key is used to authenticate requests to the Midtrans API.
     */
    'client_key' => env('MIDTRANS_CLIENT_KEY', ''),

    /**
     * The is_production flag is used to determine if the Midtrans API should be used in production or sandbox mode.
     */
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),

    /**
     * HTTP request timeout in seconds.
     */
    'timeout' => (int) env('MIDTRANS_TIMEOUT', 10),

    /**
     * HTTP connection timeout in seconds.
     */
    'connect_timeout' => (int) env('MIDTRANS_CONNECT_TIMEOUT', 10),
];
