<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create(config('newsletter.table_name'), function (Blueprint $table) {
            $table->uuid('id');
            $table->string('email');
            $table->string('token');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->unique([
                'email',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('newsletter.table_name'));
    }
};
