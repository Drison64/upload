<?php

namespace App\Http\Controllers;

use App\Models\Token;
use App\Models\User;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    public function newToken(Request $request) {
        $request->validate([
            'recovery_code' => 'required|string',
        ]);

        /** @var User $user */
        $user = User::where('recovery_code', $request['recovery_code'])->first();

        if(!$user) return response()->json(['status'=>'Invalid recovery code.']);

        $newRecoveryToken = $user->newRecoveryToken();

        $token = $user->createToken("api-token");

        return response()->json(['token' => $token->plainTextToken, 'recovery-code' => $newRecoveryToken])
            ->setStatusCode(200);
    }

}
