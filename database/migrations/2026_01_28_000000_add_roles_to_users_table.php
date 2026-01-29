<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Migrate from single role to multiple roles (JSON array).
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->json('roles')->nullable()->after('password');
        });

        // Migrate existing role to roles array
        $users = DB::table('users')->get();
        foreach ($users as $u) {
            $role = $u->role ?? 'USER';
            DB::table('users')->where('id', $u->id)->update([
                'roles' => json_encode([$role]),
            ]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('USER')->after('password');
        });

        $users = DB::table('users')->get();
        foreach ($users as $u) {
            $roles = json_decode($u->roles, true);
            $role = is_array($roles) && count($roles) > 0 ? $roles[0] : 'USER';
            DB::table('users')->where('id', $u->id)->update(['role' => $role]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('roles');
        });
    }
};
