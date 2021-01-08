
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorageImagesTable extends Migration
{
    public function up()
    {
        Schema::create(config('storage.database.tables.storage_images'), function (Blueprint $table) {
            $table->id();
            $table->text('url');
            $table->boolean('main');
            $table->string('external_id')->nullable()->index();
            $table->timestamps();
        });

        Schema::table(config('storage.database.tables.storage_images'), function (Blueprint $table) {
            $table->foreignId('user_id')
                ->constrained(config('storage.database.tables.users'))
                ->onDelete('cascade');
        });

        Schema::table(config('storage.database.tables.storage_images'), function (Blueprint $table) {
            $table->foreignId('warehouse_id')
                ->constrained(config('storage.database.tables.storage_warehouses'))
                ->onDelete('cascade');
        });

        Schema::table(config('storage.database.tables.storage_images'), function (Blueprint $table) {
            $table->foreignId('product_id')
                ->constrained(config('storage.database.tables.storage_products'))
                ->onDelete('cascade');
        });

        Schema::table(config('storage.database.tables.storage_images'), function (Blueprint $table) {
            $table->foreignId('variant_id')
                ->nullable()
                ->constrained(config('storage.database.tables.storage_variants'))
                ->onDelete('cascade');
        });

        Schema::table(config('storage.database.tables.storage_images'), function (Blueprint $table) {
            $table->foreignId('source_id')
                ->nullable()
                ->constrained(config('storage.database.tables.storage_sources'))
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table(config('storage.database.tables.storage_images'), function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['warehouse_id']);
            $table->dropForeign(['variant_id']);
        });

        Schema::drop(config('storage.database.tables.storage_images'));
    }
}
