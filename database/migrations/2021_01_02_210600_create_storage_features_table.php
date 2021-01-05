
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorageFeaturesTable extends Migration
{
    public function up()
    {
        Schema::create(config('storage.database.tables.storage_features'), function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable()->index();
            $table->string('name')->index();
            $table->text('value');
            $table->timestamps();
        });

        Schema::table(config('storage.database.tables.storage_features'), function (Blueprint $table) {
            $table->foreignId('user_id')
                ->constrained(config('storage.database.tables.users'))
                ->onDelete('cascade');
        });

        Schema::table(config('storage.database.tables.storage_features'), function (Blueprint $table) {
            $table->foreignId('warehouse_id')
                ->constrained(config('storage.database.tables.storage_warehouses'))
                ->onDelete('cascade');
        });

        Schema::table(config('storage.database.tables.storage_features'), function (Blueprint $table) {
            $table->foreignId('product_id')
                ->constrained(config('storage.database.tables.storage_products'))
                ->onDelete('cascade');
        });

        Schema::table(config('storage.database.tables.storage_features'), function (Blueprint $table) {
            $table->foreignId('variant_id')
                ->nullable()
                ->constrained(config('storage.database.tables.storage_variants'))
                ->onDelete('cascade');
        });

        Schema::table(config('storage.database.tables.storage_features'), function (Blueprint $table) {
            $table->unique(['product_id', 'variant_id', 'name'], 'RK1Y0Y011L0R19F7HG0ZDT');
        });
    }

    public function down()
    {
        Schema::table(config('storage.database.tables.storage_features'), function (Blueprint $table) {
            $table->dropUnique(['product_id', 'variant_id', 'name']);
        });

        Schema::table(config('storage.database.tables.storage_features'), function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['warehouse_id']);
            $table->dropForeign(['variant_id']);
        });

        Schema::drop(config('storage.database.tables.storage_features'));
    }
}
