<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

\DB::listen(function($query) { dump($query->sql); });
$purchase = App\Models\Purchase::first();
if($purchase && $purchase->productCategory) {
    try {
        $purchase->productCategory->products()->get();
    } catch(\Exception $e) {
        echo $e->getMessage();
    }
}
echo "Done\n";
