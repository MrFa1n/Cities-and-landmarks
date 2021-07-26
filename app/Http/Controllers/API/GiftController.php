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

    public function give_a_gift(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, [
            'initiator_id' => 'required|exists:profiles,id',
            'target_id' => 'exists:profiles,id',
            'id_gift' => 'exists:profile_gifts,id'
        ]);

        if($validator->fails()){
            return response(['status' => 'error', 'error' => $validator->errors()]);
        }

        $initiator_id = $data['initiator_id'];
        $desc = $data['desc'];
        $target = $data['target'];
        $id_gift = $data['id_gift'];

        $gift_type = ProfileGifts::find($id_gift);
        if ($gift_type->value('profile_id') != $initiator_id) {
            return response(['status' => 'error', 'error' => ['gift_id' => 'Invalid gift_id']]);
        }
        $gift_type_id = $gift_type->value('gift_id');

        if (array_key_exists('extra', $data)) {
            $extra = $data['extra'];
        }
        else {
            $extra = '{}';
        }

        $gift_type->delete();

        $field = DonatedGiftModel::create([
            'init' => $initiator_id,
            'target' => $target,
            'id_gift' => $gift_type_id,
            'desc' => $desc,
            'extra' => $extra
        ]);

        return response(['status' => 'ok', 'response' => $field], 200);
    }


    public function profile_gifts(Request $request) {   
        $data = $request->all();
        $validator = Validator::make($data, [
            'profile_id' => 'exists:profiles,id'
        ]);

        if($validator->fails()){
            return response(['status' => 'error', 'error' => $validator->errors()]);
        }

        $my_id = $data['profile_id'];

        $all_my_gifts = ProfileGifts::where('profile_id', $my_id)->get(); 
        return response(['status' => 'ok', 'response' => ['gifts' => $all_my_gifts]]);
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
}
