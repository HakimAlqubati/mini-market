<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('name', 200);
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->string('text')->nullable();
            // $table->boolean('is_new')->default(1);
            $table->boolean('active')->default(1);

            $table->unsignedBigInteger('group_id');
            $table->foreign('group_id')->references('id')->on('sub_groups')->onDelete('cascade');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function ($table) {
            $table->dropForeign(['group_id']);
            $table->dropIfExists('products');
        });
    }
}
