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
        Schema::table('users', function (Blueprint $table) {
            $table->text('two_factor_secret')
                ->after('password')
                ->nullable();
                ->comment('将来的な2FA機能用としてFortifyにより自動生成されたが、現在は未使用のため保留');

            $table->text('two_factor_recovery_codes')
                ->after('two_factor_secret')
                ->nullable();
                ->comment('将来的な2FA機能用としてFortifyにより自動生成されたが、現在は未使用のため保留');

            $table->timestamp('two_factor_confirmed_at')
                ->after('two_factor_recovery_codes')
                ->nullable();
                ->comment('将来的な2FA機能用としてFortifyにより自動生成されたが、現在は未使用のため保留');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'two_factor_secret',
                'two_factor_recovery_codes',
                'two_factor_confirmed_at',
            ]);
        });
    }
};
