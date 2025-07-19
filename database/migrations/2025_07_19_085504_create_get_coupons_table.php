<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('get_coupons', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('coins')->nullable();
            $table->string('giveaway')->nullable();
            $table->string('pkg_image_url')->nullable();
            $table->string('label_popular')->nullable();
            $table->string('label_color')->nullable();
            $table->string('price_per_coin')->nullable();
            $table->string('total_price')->nullable();
            $table->string('product_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('get_coupons');
    }
};