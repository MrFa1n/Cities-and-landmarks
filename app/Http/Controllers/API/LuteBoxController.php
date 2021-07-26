<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\LuteBoxModel;
use App\Models\GiftModel;
use App\Models\ProfileGifts;
use App\Models\DonatedGiftModel;
use App\Models\LBTierPriceModel;

class LuteBoxController extends Controller
{
    public function roll(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, [
            'initiator_id' => 'required|exists:profiles,id',
            'box_id' => 'exists:lutebox_models,id',
        ]);

        if($validator->fails()){
            return response(['status' => 'error', 'error' => $validator->errors()]);
        }
    }
}
