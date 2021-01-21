
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorageDuplicateProductsTable extends Migration
{
    public function up()
    {
        Schema::create(config('storage.database.tables.storage_duplicate_products'), function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::table(config('storage.database.tables.storage_duplicate_products'), function (Blueprint $table) {
            $table->foreignId('user_id')
                ->constrained(config('storage.database.tables.users'))
                ->onDelete('cascade');
        });

        Schema::table(config('storage.database.tables.storage_duplicate_products'), function (Blueprint $table) {
            $table->foreignId('warehouse_id')
                ->constrained(config('storage.database.tables.storage_warehouses'))
                ->onDelete('cascade');
        });

        Schema::table(config('storage.database.tables.storage_duplicate_products'), function (Blueprint $table) {
            $table->foreignId('product_id')
                ->constrained(config('storage.database.tables.storage_products'))
                ->onDelete('cascade');
        });

        Schema::table(config('storage.database.tables.storage_duplicate_products'), function (Blueprint $table) {
            $table->foreignId('duplicate_product_id')
                ->constrained(config('storage.database.tables.storage_products'))
                ->onDelete('cascade');
        });

        Schema::table(config('storage.database.tables.storage_duplicate_products'), function (Blueprint $table) {
            $table->unique(['duplicate_product_id', 'product_id'], '8UGXL33G1R7PNWKICKRNLE');
        });
    }

    public function down()
    {
     
        Schema::table(config('storage.database.tables.storage_duplicate_products'), function (Blueprint $table) {
            $table->dropUnique(['duplicate_product_id', 'product_id']);
        });

        Schema::table(config('storage.database.tables.storage_duplicate_products'), function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['warehouse_id']);
            $table->dropForeign(['product_id']);
            $table->dropForeign(['duplicate_product_id']);
        });

        Schema::drop(config('storage.database.tables.storage_duplicate_products'));
    }
}
