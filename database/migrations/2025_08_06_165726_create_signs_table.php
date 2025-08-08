<?php

use App\Models\Campaign;
use App\Models\Team;
use App\Models\User;
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
        Schema::create('signs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Team::class);
            $table->foreignIdFor(Campaign::class);

            $table->string('address');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->text('notes')->nullable();
            $table->string('image')->nullable();

            // Who placed the sign
            $table->foreignIdFor(User::class, 'placed_by_user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('placed_at')->nullable();

            // Who recovered the sign
            $table->foreignIdFor(User::class, 'recovered_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('recovered_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signs');
    }
};
