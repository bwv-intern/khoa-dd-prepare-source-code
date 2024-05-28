<?php

use App\Traits\DatabaseCommonTrait;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTable extends Migration
{
    use DatabaseCommonTrait;

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->decimal('price', 15, 4)->default('0.0000');
            $table->text('content');
            $table->string('image_path', 50)->nullable();
            $table->tinyInteger('featured_flg')->default(0);
            $table->integer('viewed')->default(0)->nullable();
            $table->integer('ordered')->default(0)->nullable();
            $this->commonColumns($table);
            $this->commonCharset($table);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('products');
    }
}