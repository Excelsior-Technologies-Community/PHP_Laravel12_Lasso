<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. CREATE TABLE (જો table ન હોય તો)
        if (!Schema::hasTable('build_histories')) {
            Schema::create('build_histories', function (Blueprint $table) {
                $table->id();
                $table->string('version');
                $table->integer('asset_count')->default(0);
                $table->string('status')->default('Success');
                $table->timestamp('published_at');
                $table->string('environment')->default('production');
                $table->string('deployed_by')->default('System');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 2. ADD COLUMNS (જો columns ન હોય તો)
        Schema::table('build_histories', function (Blueprint $table) {
            if (!Schema::hasColumn('build_histories', 'is_active')) {
                $table->boolean('is_active')->default(false)->after('status');
            }
            if (!Schema::hasColumn('build_histories', 'build_log')) {
                $table->longText('build_log')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('build_histories', 'backup_path')) {
                $table->string('backup_path')->nullable()->after('build_log');
            }
        });

        // 3. CREATE BUILD_LOGS TABLE
        if (!Schema::hasTable('build_logs')) {
            Schema::create('build_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('build_id')->constrained('build_histories')->onDelete('cascade');
                $table->text('log_content');
                $table->string('log_type')->default('build');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('build_logs');
        Schema::dropIfExists('build_histories');
    }
};