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
        Schema::create('sales_mens', function (Blueprint $table) {
            $table->id();
            $table->string('name');               // Salesman name
            $table->string('phone');              // Phone number
            $table->string('city');             // Sales region or area
            $table->string('area');             // Sales region or area
            $table->text('address');              // Physical address
            $table->decimal('salary', 10, 2); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_mens');
    }
};
