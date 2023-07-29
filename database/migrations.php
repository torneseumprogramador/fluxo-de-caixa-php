<?php
require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

require __DIR__ . '/../src/config.php';

$capsule = new Capsule;
$capsule->addConnection($dbConfig);
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Run the migrations
$capsule->schema()->dropIfExists('caixas');
$capsule->schema()->create('caixas', function ($table) {
    $table->increments('id');
    $table->string('tipo', 100);
    $table->float('valor');
    $table->integer('status');
    $table->timestamps();
});
