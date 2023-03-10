<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activity_items', function (Blueprint $table) {
            $table->boolean('is_active')->nullable()->default(true);
        });
        
        Artisan::call('db:seed', [
            '--class' => IsActiveColumnInActivityItemsSeeder::class,
            '--force' => true
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activity_items', function (Blueprint $table) {
            //
        });
    }
};
