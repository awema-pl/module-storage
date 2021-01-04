
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorageVariantsTable extends Migration
{
    public function up()
    {
        Schema::create(config('storage.database.tables.storage_variants'), function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ean')->nullable()->index();
            $table->string('sku')->nullable()->index();
            $table->integer('stock')->default(0);
            $table->string('availability')->default('1')->index();
            $table->decimal('brutto_price', 12, 4);
            $table->timestamps();
        });

        Schema::table(config('storage.database.tables.storage_variants'), function (Blueprint $table) {
            $table->foreignId('user_id')
                ->constrained(config('storage.database.tables.users'))
                ->onDelete('cascade');
        });

        Schema::table(config('storage.database.tables.storage_variants'), function (Blueprint $table) {
            $table->foreignId('warehouse_id')
                ->constrained(config('storage.database.tables.storage_warehouses'))
                ->onDelete('cascade');
        });

        Schema::table(config('storage.database.tables.storage_variants'), function (Blueprint $table) {
            $table->foreignId('product_id')
                ->constrained(config('storage.database.tables.storage_products'))
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table(config('storage.database.tables.storage_variants'), function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['warehouse_id']);
        });

        Schema::drop(config('storage.database.tables.storage_variants'));
    }
}
