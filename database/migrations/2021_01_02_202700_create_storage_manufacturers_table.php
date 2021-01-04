
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorageManufacturersTable extends Migration
{
    public function up()
    {
        Schema::create(config('storage.database.tables.storage_manufacturers'), function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('image_url')->nullable();
            $table->timestamps();
        });

        Schema::table(config('storage.database.tables.storage_manufacturers'), function (Blueprint $table) {
            $table->foreignId('user_id')
                ->constrained(config('storage.database.tables.users'))
                ->onDelete('cascade');
        });

        Schema::table(config('storage.database.tables.storage_manufacturers'), function (Blueprint $table) {
            $table->foreignId('warehouse_id')
                ->constrained(config('storage.database.tables.storage_warehouses'))
                ->onDelete('cascade');
        });

        Schema::table(config('storage.database.tables.storage_manufacturers'), function (Blueprint $table) {
            $table->unique(['warehouse_id', 'name'], 'YKXV281P8U52DCJJ3QQKJU');
        });
    }

    public function down()
    {
        Schema::table(config('storage.database.tables.storage_manufacturers'), function (Blueprint $table) {
            $table->dropUnique(['warehouse_id', 'name']);
        });

        Schema::table(config('storage.database.tables.storage_manufacturers'), function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['warehouse_id']);
        });

        Schema::drop(config('storage.database.tables.storage_manufacturers'));
    }
}
