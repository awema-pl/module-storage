
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use AwemaPL\Storage\User\Sections\Products\Services\Contracts\Availability;

class CreateStorageProductsTable extends Migration
{
    public function up()
    {
        /** @var Availability $availability */
        $availability = app(Availability::class);
        Schema::create(config('storage.database.tables.storage_products'), function (Blueprint $table) use (&$availability) {
            $table->id();
            $table->boolean('active')->default(false)->index();
            $table->string('name');
            $table->string('ean')->nullable()->index();
            $table->string('sku')->nullable()->index();
            $table->integer('stock');
            $table->string('availability')->default($availability->getDefault())->index();
            $table->decimal('brutto_price', 12, 4);
            $table->integer('tax_rate')->nullable()->index();
            $table->string('external_id')->nullable()->index();
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
            $table->foreignId('default_category_id')
                ->constrained(config('storage.database.tables.storage_categories'))
                ->onDelete('restrict');
        });

        Schema::table(config('storage.database.tables.storage_products'), function (Blueprint $table) {
            $table->foreignId('manufacturer_id')
                ->nullable()
                ->constrained(config('storage.database.tables.storage_manufacturers'))
            ->nullOnDelete();
        });

        Schema::table(config('storage.database.tables.storage_products'), function (Blueprint $table) {
            $table->foreignId('source_id')
                ->nullable()
                ->constrained(config('storage.database.tables.storage_sources'))
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table(config('storage.database.tables.storage_products'), function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['warehouse_id']);
            $table->dropForeign(['category_id']);
            $table->dropForeign(['manufacturer_id']);
            $table->dropForeign(['source_id']);
        });

        Schema::drop(config('storage.database.tables.storage_products'));
    }
}
