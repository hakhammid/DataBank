<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    $models = \Gemini\Laravel\Facades\Gemini::models()->list();
    foreach ($models->models as $model) {
        echo $model->name . "\n";
    }
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
