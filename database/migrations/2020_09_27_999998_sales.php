<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Sales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            $table->foreignId('companies_id');
            $table->foreign('companies_id')->references('id')->on('companies');

            $table->foreignId('users_id');
            $table->foreign('users_id')->references('id')->on('users');

            $table->foreignId('products_id');
            $table->foreign('products_id')->references('id')->on('products');

            $table->decimal('amount', 10, 2);

            $table->timestamps();
            $table->softDeletes();
            $table->bigInteger('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
