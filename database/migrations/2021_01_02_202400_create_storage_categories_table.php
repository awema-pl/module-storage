
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorageCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create(config('storage.database.tables.storage_categories'), function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('parent_id')->nullable()->index();
            $table->string('external_id')->nullable()->index();
            $table->timestamps();
        });

        Schema::table(config('storage.database.tables.storage_categories'), function (Blueprint $table) {
            $table->foreignId('user_id')
                ->constrained(config('storage.database.tables.users'))
                ->onDelete('cascade');
        });

        Schema::table(config('storage.database.tables.storage_categories'), function (Blueprint $table) {
            $table->foreignId('warehouse_id')
                ->constrained(config('storage.database.tables.storage_warehouses'))
                ->onDelete('cascade');
        });

        Schema::table(config('storage.database.tables.storage_categories'), function (Blueprint $table) {
            $table->foreignId('source_id')
                ->nullable()
                ->constrained(config('storage.database.tables.storage_sources'))
                ->onDelete('cascade');
        });

        DB::statement('ALTER TABLE '.config('storage.database.tables.storage_categories').' ADD CONSTRAINT QK2T0F7JI74353OA05H4SE CHECK (id != parent_id);');
    }

    public function down()
    {
        Schema::table(config('storage.database.tables.storage_categories'), function (Blueprint $table) {
            DB::statement('ALTER TABLE '.config('storage.database.tables.storage_categories').' DROP CONSTRAINT QK2T0F7JI74353OA05H4SE;');
        });

        Schema::table(config('storage.database.tables.storage_categories'), function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['warehouse_id']);
        });

        Schema::drop(config('storage.database.tables.storage_categories'));
    }
}
