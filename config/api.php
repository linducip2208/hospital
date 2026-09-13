<?php

return [
    // Session authentication is retained only for local/testing backward compatibility.
    'allow_session' => env('API_ALLOW_SESSION', in_array(env('APP_ENV', 'production'), ['local', 'testing'], true)),
    'rate_limit' => (int) env('API_RATE_LIMIT', 60),
];
