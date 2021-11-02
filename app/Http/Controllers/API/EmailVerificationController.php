<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\User;
// Класс для верификации пользователя через почту
class EmailVerificationController extends Controller
{
    // Функция верификации имейла
    public function verify(Request $request)
    {   
        // Поиск пользователя по id
        $user = User::find($request->route('id'));
        
        // Проверка верифицирован ли уже имейл
        if ($user->hasVerifiedEmail()) {
            return [
                'message' => 'Email already verified'
            ];
        }

        // Иначе вызывается фунцкия отметки пользователя как верифицированного,
        // и отправка уведомления о верификации
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }
        
        return [
            'message'=>'Email verified'
        ];
    }
}
