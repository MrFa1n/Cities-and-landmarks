<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\GiftModel;
use App\Models\ProfileFields;
use App\Models\ProfileFieldsTypes;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ProfileResource;

class GiftController extends Controller
{
    public function get_gift(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'initiator_id' => 'required|exists:profiles,id',
            'target_id' => 'exists:profiles,id'
        ]);

        if($validator->fails()){
            return response(['status' => 'error', 'error' => $validator->errors()]);
        }

        $initiator_id = $data['initiator_id'];
        if (array_key_exists('target_id', $data)) {
            $target_id = $data['target_id'];
            $mathes = GiftModel::where('initiator_id', $initiator_id)->where('target_id', $target_id)->get();
            return response(['status' => 'ok', 'response' => ['initiator_id' => $initiator_id, 'matches' => $mathes]]);
        }
        else {
            $mathes = GiftModel::where('initiator_id', $initiator_id)->get();
            return response(['status' => 'ok', 'response' => ['initiator_id' => $initiator_id, 'matches' => $mathes]]);
        }
    }

    public function give_a_gift(Request $request){
        $data = $request->all();
        $res = array();

        $validator = Validator::make($data, [
            'initiator_id' => 'required|exists:profiles,id',
            'target_id' => 'required|exists:profiles,id'
        ]);

        if($validator->fails()){
            return response(['status' => 'error', 'error' => $validator->errors()]);
        }

        $initiator_id = $data['initiator_id'];
        $target_id = $data['target_id'];

        if ($initiator_id == $target_id){
            return response(['status' => 'error', 'error' => ['target_id' => 'Cannot match yourself']]);
        }

        $check_exists = GiftModel::where('initiator_id', $initiator_id)->where('target_id', $target_id)->get();
        if (count($check_exists) != 0) {
            return response(['status'=>'error', 'error' => ['match_id' => 'Match already exists']]);
        }

        $check_exists = GiftModel::where('initiator_id', $target_id)->where('target_id', $initiator_id)->get();
        if (count($check_exists) != 0) {
            //
        }

        $field = GiftModel::create([
            'initiator_id' => $initiator_id,
            'target_id' => $target_id
        ]);

        return response(['status' => 'ok', 'response' => $field->id], 200);
    }
}
