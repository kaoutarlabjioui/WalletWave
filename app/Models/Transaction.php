<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;



    protected  $fillable =[
        'serial',
        'amount',
        'sender_wallet_id',
        'receiver_wallet_id',
        'type'
    ];



    public function senderWallet(){
      return  $this->belongsTo(Wallet::class,'sender_wallet_id');
    }

    public function receiverWallet()
    {
      return  $this->belongsTo(Wallet::class,'receiver_wallet_id');
    }
}
