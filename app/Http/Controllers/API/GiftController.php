<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\GiftModel;
use App\Models\DonatedGiftModel;
use App\Models\ProfileFields;
use App\Models\ProfileFieldsTypes;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use App\Models\ProfileGifts;

class GiftController extends Controller
{
    public function get_gift(Request $request) {   
        $mathes = GiftModel::all();
        return response(['status' => 'ok', 'response' => ['mathes' => $mathes]]);
    }
    // Подарить подарок
    public function give_a_gift(Request $request){
        // Выбираем все данные из запроса
        // Проверяем, есть ли в БД: отправитель, получатель и сам подарок
        $data = $request->all();
        $validator = Validator::make($data, [
            'initiator_id' => 'required|exists:profiles,id',
            'target_id' => 'exists:profiles,id',
            'id_gift' => 'exists:profile_gifts,gift_id'
        ]);

        if($validator->fails()){
            return response(['status' => 'error', 'error' => $validator->errors()]);
        }

        // Записываем в переменные данные для отправки подарка
        $initiator_id = $data['initiator_id'];
        $desc = $data['desc'];
        $target = $data['target'];
        $id_gift = $data['id_gift'];

        // Проверяем передавали ли поле экстра в запросе
        if (array_key_exists('extra', $data)) {
            $extra = $data['extra'];
        }
        else {
            $extra = '';
        }

        // Отправляем запрос в БД, чтобюы найти ту строку
        // Идет проверка на наличие поля Экстра (описание подарка)
        if ($extra == '') {
            $gift_type_tmp = ProfileGifts::where([
            ['profile_id','=', $initiator_id],
            ['gift_id','=', $id_gift],
            ])->get();
        } else {
            $gift_type_tmp = ProfileGifts::where([
            ['profile_id','=', $initiator_id],
            ['gift_id','=', $id_gift],
            ['extra','=', $extra],
            ])->get();
        }
        
        // Проверка существует ли данный подарок у пользователя
        if(empty($gift_type_tmp[0])) {
            return response(['status' => 'error', 'error' => ['gift_id' => 'Invalid gift_id']]);
        }
        
        // Ыфбираем первый попавшийся подарок у человека по ийди подарка
        $gift_type = $gift_type_tmp[0];
        // Записыываем в переменную айди подарка
        $gift_type_id = $gift_type->value('gift_id');

        // Удаляем у полязователя подаренный подарок
        $gift_type_tmp[0]->delete();
        // И добавляем пользователю новый подарок
        $field = ProfileGifts::create([
            'gift_id' => $id_gift,
            'profile_id' => $target,
            'extra' => $extra
        ]);

        return response(['status' => 'ok', 'response' => $field], 200);
    }

    // Вывод всех подарков профиля
    public function profile_gifts(Request $request) {   
        $data = $request->all();
        $validator = Validator::make($data, [
            'profile_id' => 'exists:profiles,user_id'
        ]);

        if($validator->fails()){
            return response(['status' => 'error', 'error' => $validator->errors()]);
        }

        $my_id = $data['profile_id'];

        // Выборка из БД всех подарков пррофиля
        $all_my_gifts = ProfileGifts::where('profile_id', $my_id)->get();
        // Выборка из БД всех типов подарков, для отправки их значений на фронт 
        $all_gifts =  GiftModel::all(); 
 
        // Массив для посчетка количества кадого подарка
        $gifts_count = array();
        // Массив ответа на запрос
        $gifts_response = array();
        // Массив для проверки пройденных подарков нашего профиля,
        // для того, чтобы избежать повторений в ответе
        $checked_gifts = array();

        // считаем количество одинаковых подарков
        foreach($all_my_gifts as $gift_field) {
            if (!array_key_exists($gift_field->gift_id, $gifts_count)) {
                $gifts_count[$gift_field->gift_id] = 1;
            } else {
                $gifts_count[$gift_field->gift_id]++;
            }
        }
        // Пробегаемся по всему массиву наших подарков и вытягивем оттуда значения для подарков
        // Заносим в массив проверенных подарков, чтобы избежать повторений в ответе на запрос
        foreach($all_my_gifts as $gift_field) {
            // Пробегаемя по массиву всех типов подарков, чтобы вытянуть значения полей для нужного подарка
            // P.S. Пытался отдельно по id вытаскивать из БД, не получалось
            // Фикс будет
            foreach($all_gifts as $value){
                // Условие для проверки совпадения Id нашего подарка с id подарка из списка всех существующих подарков
                // И условие для проверки занесли ли мы данный подарок в ответ
                if ($value['id'] == $gift_field->gift_id && !in_array($value['name'], $checked_gifts)) {
                    $gifts_response[] = [
                        'name' => $value['name'],
                        'gift_id' => $value['id'],
                        'tier' => $value['tier'],
                        'count' => $gifts_count[$value['id']],
                        'desc' => $value['desc'],
                        'icon' => $value['icon']
                    ];
                    // Заносим в массив пройденных подарков наш подарок
                    $checked_gifts[] = $value['name'];
                }
            }            
        }

        return response(['status' => 'ok', 'response' => ['gifts' => $gifts_response]]);
    }

    public function get_tier(Request $request) {   
        $data = $request->all();
        $validator = Validator::make($data, [
            'tier' => 'integer'
        ]);

        if($validator->fails()){
            return response(['status' => 'error', 'error' => $validator->errors()]);
        }

        $my_id = $data['tier'];

        $all_my_gifts = GiftModel::where('tier', $my_id)->get(); 
        return response(['status' => 'ok', 'response' => ['gifts' => $all_my_gifts]]);
    }
    // Открытие лутбокса
    public function lutebox(Request $request){
        $data = $request->all();
        // Проверка на наличие значения в БД
        $validator = Validator::make($data, [
            // Required - не пустое ли поле
            // exists - существет ли запись в таблице БД 
            // после ":" название таблицы, название столбца
            'profile_id' => 'required|exists:profile_fields,profile_id'
        ]);
        if($validator->fails()){
            return response(['status' => 'error', 'error' => $validator->errors()]);
        }
        $profile_id = $data['profile_id'];
        $lootbox_id = $data['lootbox_id'];
        $drop_probability = array(1,1,1,1,1,1,1,1,1,1,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3,3);
        
        if ($lootbox_id == 1) { $count_gifts = 6; }
        $your_surprise = array();
        // $added_gifts = array();
        
        for($i = 0; $i < $count_gifts; $i++) {    
            $count = 1;
            // Выбираем 2 рандомных уровня из массива
            // На выходе получаем массив из 2 ключей случайных элементов
            // Зачем 2 пока не понятно 
            $rand_tier = array_rand($drop_probability, 2);
            // Выбираем уровень подарка, по второму зарандомленному ключу 
            $you_got = $drop_probability[$rand_tier[1]];
            // Записываем сам подарок из модели с подараками по уровню
            $all_my_gifts = GiftModel::where('tier', $you_got)->get();
            $encode_arr =  json_decode($all_my_gifts,true);
            // Так как подарков одного уровня может быть несколько, 
            // Рандомим слуайный среди них
            $count_bitch = count($encode_arr);
            $rand_price = rand(0, $count_bitch-1);
            $gift_tmp = $encode_arr[$rand_price];
            $your_surprise[] = $gift_tmp;

            // if ( !array_key_exists($gift_tmp['name'], $added_gifts)) {
            //     $gift_tmp['count'] = $count;
            //     $your_surprise[] = $gift_tmp;
            //     $added_gifts[$gift_tmp['name']] = 1;
                
            // }
            // else{
            //     for($i=0; $i<count($your_surprise); $i++) {
            //         if ($your_surprise[$i]['name'] == $gift_tmp['name']) { $your_surprise[$i]['count']++;}
            //     }
            // }
            
            $extra = '{}';
            // Добавление в БД в таблицу profile_gifts открытого подарка 
            $field = ProfileGifts::create([
                'gift_id' => $gift_tmp['id'],
                'profile_id' => $profile_id,
                'extra' => $extra
            ]);
        }
        return response(['status' => 'ok', 'response' => ['you got gift' => $your_surprise]]);
    }
}
