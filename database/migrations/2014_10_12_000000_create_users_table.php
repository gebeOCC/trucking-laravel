<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin','driver', 'client']);
            $table->rememberToken();
            $table->timestamps();
        });
        $this->run();
    }

    private function run()
    {
        $users = [
            [
                'email' => 'admin@example.com',
                'password' => Hash::make('123'),
                'role' => 'admin',
            ],
            [
                'email' => 'driver@example.com',
                'password' => Hash::make('123'),
                'role' => 'driver',
            ],
            [
                'email' => 'client@example.com',
                'password' => Hash::make('123'),
                'role' => 'client',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
