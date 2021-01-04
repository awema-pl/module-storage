
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorageDescriptionsTable extends Migration
{
    public function up()
    {
        Schema::create(config('storage.database.tables.storage_descriptions'), function (Blueprint $table) {
            $table->id();
            $table->string('key')->default('default')->index();
            $table->mediumText('value');
            $table->timestamps();
        });

        Schema::table(config('storage.database.tables.storage_descriptions'), function (Blueprint $table) {
            $table->foreignId('user_id')
                ->constrained(config('storage.database.tables.users'))
                ->onDelete('cascade');
        });

        Schema::table(config('storage.database.tables.storage_descriptions'), function (Blueprint $table) {
            $table->foreignId('warehouse_id')
                ->constrained(config('storage.database.tables.storage_warehouses'))
                ->onDelete('cascade');
        });

        Schema::table(config('storage.database.tables.storage_descriptions'), function (Blueprint $table) {
            $table->foreignId('product_id')
                ->constrained(config('storage.database.tables.storage_products'))
                ->onDelete('cascade');
        });

        Schema::table(config('storage.database.tables.storage_descriptions'), function (Blueprint $table) {
            $table->unique(['product_id', 'key'], 'UPHHD69H02XDMUYF9WMYPR');
        });
    }

    public function down()
    {
        Schema::table(config('storage.database.tables.storage_descriptions'), function (Blueprint $table) {
            $table->dropUnique(['product_id', 'key']);
        });

        Schema::table(config('storage.database.tables.storage_descriptions'), function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['warehouse_id']);
        });

        Schema::drop(config('storage.database.tables.storage_descriptions'));
    }
}
