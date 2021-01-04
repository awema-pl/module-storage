
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorageProductsTable extends Migration
{
    public function up()
    {
        Schema::create(config('storage.database.tables.storage_products'), function (Blueprint $table) {
            $table->id();
            $table->string('external_id')->nullable()->index();
            $table->string('name');
            $table->string('ean')->nullable()->index();
            $table->string('sku')->nullable()->index();
            $table->integer('stock')->default(0);
            $table->string('availability')->default('1')->index();
            $table->decimal('brutto_price', 12, 4);
            $table->integer('tax_rate')->nullable()->index();
            $table->timestamps();
        });

        Schema::table(config('storage.database.tables.storage_products'), function (Blueprint $table) {
            $table->foreignId('user_id')
                ->constrained(config('storage.database.tables.users'))
                ->onDelete('cascade');
        });

        Schema::table(config('storage.database.tables.storage_products'), function (Blueprint $table) {
            $table->foreignId('warehouse_id')
                ->constrained(config('storage.database.tables.storage_warehouses'))
                ->onDelete('cascade');
        });

        Schema::table(config('storage.database.tables.storage_products'), function (Blueprint $table) {
            $table->foreignId('category_id')
                ->constrained(config('storage.database.tables.storage_categories'));
        });

        Schema::table(config('storage.database.tables.storage_products'), function (Blueprint $table) {
            $table->foreignId('manufacturer_id')
                ->nullable()
                ->constrained(config('storage.database.tables.storage_manufacturers'));
        });
    }

    public function down()
    {
        Schema::table(config('storage.database.tables.storage_products'), function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['warehouse_id']);
            $table->dropForeign(['category_id']);
            $table->dropForeign(['manufacturer_id']);
        });

        Schema::drop(config('storage.database.tables.storage_products'));
    }
}
