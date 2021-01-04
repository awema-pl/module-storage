
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorageCategoryProductTable extends Migration
{
    public function up()
    {
        Schema::create(config('storage.database.tables.storage_category_product'), function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::table(config('storage.database.tables.storage_category_product'), function (Blueprint $table) {
            $table->foreignId('user_id')
                ->constrained(config('storage.database.tables.users'))
                ->onDelete('cascade');
        });

        Schema::table(config('storage.database.tables.storage_category_product'), function (Blueprint $table) {
            $table->foreignId('warehouse_id')
                ->constrained(config('storage.database.tables.storage_warehouses'))
                ->onDelete('cascade');
        });

        Schema::table(config('storage.database.tables.storage_category_product'), function (Blueprint $table) {
            $table->foreignId('category_id')
                ->constrained(config('storage.database.tables.users'));
        });

        Schema::table(config('storage.database.tables.storage_category_product'), function (Blueprint $table) {
            $table->foreignId('product_id')
                ->constrained(config('storage.database.tables.storage_products'))
                ->onDelete('cascade');
        });

        Schema::table(config('storage.database.tables.storage_category_product'), function (Blueprint $table) {
            $table->unique(['category_id', 'product_id'], 'SR4O39ITMD1FKGG6TQ77QG');
        });
    }

    public function down()
    {
        Schema::table(config('storage.database.tables.storage_category_product'), function (Blueprint $table) {
            $table->dropUnique(['category_id', 'product_id']);
        });

        Schema::table(config('storage.database.tables.storage_category_product'), function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['warehouse_id']);
            $table->dropForeign(['category_id']);
            $table->dropForeign(['product_id']);
        });

        Schema::drop(config('storage.database.tables.storage_category_product'));
    }
}
