<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->text('description')->nullable();
            $table->string('text')->nullable();
            $table->boolean('active')->default(1);

            $table->unsignedBigInteger('parent_id');
            $table->foreign('parent_id')->references('id')->on('main_groups')->onDelete('cascade');


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
        Schema::table('sub_groups', function ($table) {
            $table->dropForeign(['parent_id']);
            $table->dropIfExists('sub_groups');
        });
    }
}
