<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationPermissionTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_permission_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('display_name');
            $table->string('feature');
            $table->timestamps();
            $table->softDeletes();
        });

        \Artisan::call('db:seed', [
            '--class' => OrganizationPermissionTypeSeeder::class,
            '--force' => true
        ]);

        \Artisan::call('db:seed', [
            '--class' => DefaultSsoPermissionTypes::class,
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
        Schema::dropIfExists('organization_permission_types');
    }
}
