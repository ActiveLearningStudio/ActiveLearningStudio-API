<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewH5PMetadataColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('h5p_contents', function (Blueprint $table) {
            $table->string('royalty_type', 255)->nullable()->default(null);
            $table->string('royalty_terms_views', 255)->nullable()->default(null);
            $table->decimal('amount', 8, 2)->nullable()->default(null);
            $table->string('currency', 255)->nullable()->default(null);
            $table->text('copyright_notice')->nullable()->default(null);
            $table->text('credit_text')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('h5p_contents', function (Blueprint $table) {
            $table->dropColumn('royalty_type');
            $table->dropColumn('royalty_terms_views');
            $table->dropColumn('amount', 8, 2);
            $table->dropColumn('currency');
            $table->dropColumn('copyright_notice');
            $table->dropColumn('credit_text');
        });
    }
}
