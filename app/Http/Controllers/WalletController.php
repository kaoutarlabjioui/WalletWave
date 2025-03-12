<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;


class WalletController extends Controller
{

    public function depot(Request $request)
    {

        try {
            DB::beginTransaction();
            $user = Auth()->user();
            $amount = $request->input('amount');
            // return ["amount" => $amount];

            if (!$user->name || !$user->email) {
                return response()->json(['message' => 'Invalide user data']);
            }

            if ($amount <= 0) {
                return response()->json(['message' => 'Invalide amount']);
            }


            $user->wallet->balance += $amount;
            $user->wallet->save();

            $randomSerial = Str::random(9) . $user->id;

            $transaction = Transaction::create([

                'sender_wallet_id' => $user->wallet->id,
                'receiver_wallet_id' => $user->wallet->id,
                'amount' => $amount,
                'serial' => strtoupper($randomSerial),
                'type' => 'dépôt'
            ]);
            DB::commit();
            return response()->json([
                'message' => 'successful dépôt',
                'balance' => $user->wallet->balance
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'dépôt failed',
                'error' => $e->getMessage()
            ]);
        }
    }

public function retrait(Request $request)
{
    try{
        DB::begintransaction();

        $user= Auth()->user();
        $amount = $request->input('amount');

        if(!$user->name || !$user->email){
         return response()->json(['message' => 'Invalide user data']);
        }
        if ($amount <= 0) {
            return response()->json(['message' => 'Invalide amount']);
        }


        if($user->wallet->balance < $amount){
            return response()->json(['message'=> 'Insufficient balance']);
        }

        $user->wallet->balance -= $amount;
        $user->wallet->save();
        $randomSerial = Str::random(9) . $user->id;
        $transaction = Transaction::create([

            'sender_wallet_id' => $user->wallet->id,
            'receiver_wallet_id' => $user->wallet->id,
            'amount' => $amount,
            'serial' => strtoupper($randomSerial),
            'type' => 'retrait'
        ]);

        DB::commit();
        return response()->json(['message'=>'successful retrait',
        'balance'=>$user->wallet->balance]);


    }catch (\Exception $e) {
        DB::rollback();
        return response()->json([
            'message' => 'retrait failed',
            'error' => $e->getMessage()
        ]);
    }
}




public function transfert(Request $request){


    $transfertData = $request->validate([
        'amount' => 'required',
        'receiver_email' => 'required',
        'receiver_name' => 'required'
    ]);


    try{
        DB::begintransaction();

        $sender = auth()->user();

        $receiver = User::where('email',$transfertData['receiver_email'])->first();

        if ($sender == null) {
            return response()->json(['message' => 'Sender email not found!']);
        } else if ($receiver == null) {
            return response()->json(['message' => 'Receiver email not found!']);

        }else if ($receiver->name != $transfertData['receiver_name'] ) {
            return response()->json(['message' => 'Receiver data do not match any user!']);
        } else if ($sender->wallet == null) {
            return response()->json(['message' => 'Sender wallet not found!']);
        } else if ($receiver->wallet == null) {
            return response()->json(['message' => 'Receiver wallet not found!']);
        }

            $amount = $transfertData(['amount']);

            if ($sender->wallet->balance < $amount) {
                    return response()->json(['message' => 'Your fonds are not enough!']);
            }
            $sender->wallet->balance -= $amount;
            $sender->wallet->save();

            $receiver->wallet->balance += $amount;
            $receiver->wallet->save();

            $transaction = Transaction::create([
                'sender_wallet_id' => $sender->wallet->id,
                'recipient_wallet_id' => $receiver->wallet->id,
                'amount' => $amount,
                'type' => 'transfert',
            ]);

        
    }catch (\Exception $e) {
        // Rollback en cas d'erreur
        DB::rollback();
        return response()->json([
            'message' => 'Transfert failed',
            'error' => $e->getMessage()
        ], 500);

    }
}


}



