<?php
// database/migrations/2026_05_13_081533_create_build_histories_table.php (updated)

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('build_histories', function (Blueprint $table) {
            $table->id();
            $table->string('version');
            $table->integer('asset_count')->default(0);
            $table->string('status')->default('Success');
            $table->timestamp('published_at');
            $table->string('environment')->default('production'); // NEW
            $table->string('deployed_by')->default('System'); // NEW
            $table->text('notes')->nullable(); // NEW
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('build_histories');
    }
};