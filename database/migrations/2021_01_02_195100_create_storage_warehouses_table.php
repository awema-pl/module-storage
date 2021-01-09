
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorageWarehousesTable extends Migration
{
    public function up()
    {
        Schema::create(config('storage.database.tables.storage_warehouses'), function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('duplicate_products')->nullable();
            $table->timestamps();
        });

        Schema::table(config('storage.database.tables.storage_warehouses'), function (Blueprint $table) {
            $table->foreignId('user_id')
                ->constrained(config('storage.database.tables.users'))
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table(config('storage.database.tables.storage_warehouses'), function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::drop(config('storage.database.tables.storage_warehouses'));
    }
}
