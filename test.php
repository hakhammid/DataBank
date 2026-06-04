<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    $result = \Gemini\Laravel\Facades\Gemini::generativeModel('gemini-flash-latest')->generateContent('hi');
    echo "SUCCESS: " . $result->text();
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
