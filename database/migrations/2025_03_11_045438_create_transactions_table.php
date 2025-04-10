<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->string('serial');
        $table->decimal('amount', 10, 2);
        $table->enum('type', ['dépôt', 'retrait', 'transfert']);
        $table->foreignId('sender_wallet_id')->constrained('wallets')->onDelete('cascade')->nullable();
        $table->foreignId('receiver_wallet_id')->constrained('wallets')->onDelete('cascade')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
