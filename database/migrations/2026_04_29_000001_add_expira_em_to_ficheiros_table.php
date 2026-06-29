<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ficheiros', function (Blueprint $table) {
            // Data de expiração — NULL significa que não expira
            $table->timestamp('expira_em')->nullable()->after('grupo_id');
        });
    }

    public function down(): void
    {
        Schema::table('ficheiros', function (Blueprint $table) {
            $table->dropColumn('expira_em');
        });
    }
};
