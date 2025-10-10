<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Avviamento della migration
     */
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('address');
            $table->string('zip_code', 5);
            $table->string('city');
            $table->string('province');
            $table->string('region');
            $table->string('email');
            $table->timestamps();
        });
    }

    /**
     * Rollback su le ultime modifiche
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
