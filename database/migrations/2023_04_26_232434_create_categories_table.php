<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('category')->unique();
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->foreignId('category_id')
                ->nullable()
                ->after('user_id')
                ->constrained()
                ->nullOnDelete()
            ;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });

        Schema::dropIfExists('categories');
    }
};
