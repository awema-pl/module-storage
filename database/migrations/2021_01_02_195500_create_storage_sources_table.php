
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorageSourcesTable extends Migration
{
    public function up()
    {
        Schema::create(config('storage.database.tables.storage_sources'), function (Blueprint $table) {
            $table->id();
            $table->morphs('sourceable', '7CH7W1WBH47EPWFBA8987R');
            $table->json('settings')->nullable();
            $table->timestamps();
        });

        Schema::table(config('storage.database.tables.storage_sources'), function (Blueprint $table) {
            $table->foreignId('user_id')
                ->constrained(config('storage.database.tables.users'))
                ->onDelete('cascade');
        });

        Schema::table(config('storage.database.tables.storage_sources'), function (Blueprint $table) {
            $table->foreignId('warehouse_id')
                ->constrained(config('storage.database.tables.storage_warehouses'))
                ->onDelete('cascade');
        });

        Schema::table(config('storage.database.tables.storage_sources'), function (Blueprint $table) {
            $table->unique(['warehouse_id','sourceable_type', 'sourceable_id'], 'UHG0K1EIP3SI4V5LTVHZ4W');
        });
    }

    public function down()
    {
        Schema::table(config('storage.database.tables.storage_sources'), function (Blueprint $table) {
            $table->dropUnique(['warehouse_id','sourceable_type', 'sourceable_id']);
        });

         Schema::table(config('storage.database.tables.storage_sources'), function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['warehouse_id']);
        });

        Schema::drop(config('storage.database.tables.storage_sources'));
    }
}
