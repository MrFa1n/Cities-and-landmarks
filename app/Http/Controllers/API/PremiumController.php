<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile;
use App\Premium;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\ProfileFields;

class PremiumController extends Controller
{
    public function add_premium(Request $request) {
        $data = $request->all();
        // Стандартная проверка на существоание пользователя
        $validator = Validator::make($data, [
            'profile_id' => 'required|exists:profiles,user_id'
        ]);

        if($validator->fails()){
            return response(['status' => 'error', 'error' => $validator->errors()]);
        }
        
        // Получаем дату обращения
        $current_date = Carbon::now();
        // Прибавляем количество месяцев подписки
        $expiration_date = Carbon::now()->addMonths(1); 
        $profile_id = $data['profile_id'];

        // Выбираем строку с премиумом из таблицы Premia
        $prem_get = Premium::where([['profile_id','=', $profile_id],
                                    ['prem_flag', '=', true],    
                                    ])->first();

        // Выбираем строку с премиумом из таблицы ProfileFields
        $profile_field = ProfileFields::where([['profile_id','=', $profile_id],
                                               ['field_type_id', '=', 14]    
                                               ])->first();

        // Проверяем не пустое ли поле с данным пользовтелем в таблице премиума
        if($prem_get != null){

            // Если оно не пустое смотрим не истекло ли время премиума
            $prem_flag = $this->check_date($current_date, $prem_get->expiration_date);

            // Если не истекло и пользователь хочет продлить подписку,
            // то передыдущую запись обнуляем и добавляем новую,
            // в которой дату начала оставляем прежнюю,
            // а дату истечения увеличиваем на 1 месяц
            if($prem_flag == true) {
                $current_date = $prem_get->purchase_date;
                $expiration_date = Carbon::parse($prem_get->expiration_date)->addMonths(1);
                $prem_get->update(['prem_flag' => false]);
                $prem = $this->create_premium($current_date, $expiration_date, $profile_id, $profile_field);
            } 
            // Если же время истекло, то просто обнуляем предыдущую
            // и создаем новую запись с текущим временем и временем окончания
            else {
                $prem_get->update(['prem_flag' => false]);
                $prem = $this->create_premium($current_date, $expiration_date, $profile_id, $profile_field);
            }
        } 
        // Если пользователь не существует просто добавляем нового
        else {
            $prem = $this->create_premium($current_date, $expiration_date, $profile_id, $profile_field);
        }
        return response(['status' => 'ok', 'response' => $prem], 200);
    }
    
    /*
    Функция для получения данных о премиуме по profile_id
    */
    public function get_premium(Request $request) {
        $data = $request->all();

        $validator = Validator::make($data, [
            'profile_id' => 'required|exists:profiles,user_id'
        ]);

        if($validator->fails()){
            return response(['status' => 'error', 'error' => $validator->errors()]);
        }

        $current_date = Carbon::now();

        $prem_get = Premium::where([['profile_id','=', $data['profile_id']],
                                    ['prem_flag', '=', true],    
                                    ])->first();

        $profile_field = ProfileFields::where([['profile_id','=', $profile_id],
                                               ['field_type_id', '=', 14]    
                                               ])->first();

        if($prem_get != null){
            $prem_flag = $this->check_date($current_date, $prem_get->expiration_date);
            if($prem_flag == true) {
                return response (['status' => 'ok', 'response' => 'premium'], 200);
            } 
            else {
                $prem_get->update(['prem_flag' => false]);
                $profile_field->update(['value' => 'false']);
                return response (['status' => 'ok', 'response' => false], 200);
            }
        } 
        else {
            return response (['status' => 'ok', 'response' => 'user don`t have premium'], 200);
        }
    }

    /*
    Функция для сравнения текущей даты с датой окончания подписки
    */
    public function check_date($current_date, $expiration_date) {
        // Парсинг даты окнчания подписки из БД
        $expiration_date_n = Carbon::parse($expiration_date);
        // Сранение: если текущая дата меньше даты окончания - возвращается true
        // В противном случае false
        $check = $current_date->lte($expiration_date_n);
        return ($check);
    }

    /*
    Функция проверки премиума у пользователя
    и одновременное обновление информации в таблицах БД
    */
    public function check_premium($profile_id) {

        $current_date = Carbon::now();

        $prem_get = Premium::where([['profile_id','=', $profile_id],
                                    ['prem_flag', '=', true],    
                                    ])->first();

        $profile_field = ProfileFields::where([['profile_id','=', $profile_id],
                                               ['field_type_id', '=', 14]    
                                               ])->first();

        if($prem_get != null){
            $prem_flag = $this->check_date($current_date, $prem_get->expiration_date);
            if($prem_flag == true) {
                $profile_field->update(['value' => 'true']);
                return (true);
            } 
            else {
                $prem_get->update(['prem_flag' => false]);
                $profile_field->update(['value' => 'false']);
                return (false);
            }
        } else {
            $profile_field->update(['value' => 'false']);
            return (false);
        }
    }

    /*
    Функция создания поля с подпиской в БД
    */ 
    public function create_premium($current_date, $expiration_date, $profile_id, $profile_field) {
        $profile_field->update(['value' => 'true']);
        // Создаем запись в бд с подпиской пользователя
        $prem = Premium::create([
            'profile_id' => $profile_id,
            'prem_flag' => true,
            'purchase_date' => $current_date,
            'expiration_date' => $expiration_date
        ]);
        return ($prem);
    }
}
