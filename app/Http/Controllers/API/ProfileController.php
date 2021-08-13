<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\ProfileFields;
use App\Models\ProfileFieldsTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ProfileResource;
use App\Models\HashTagsProfilModel;
use App\Models\UploadPhotoModel;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function register_profile(Request $request)
    {
        $data = $request->all();
        $fields_types = ProfileFieldsTypes::all();
        $res = array();

        $validator = Validator::make($data, [
            'profile_id' => 'required|exists:profiles,id'
        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors()]);
        }

        $profile_id = $data['profile_id'];
        $check_registered = ProfileFields::where('profile_id', $profile_id)->get();
        if (count($check_registered) != 0) {
            return response(['status'=>'error', 'error' => ['profile_id' => 'Profile already registered']]);
        }

        foreach($fields_types as $field_type) {
            if (!array_key_exists($field_type['name'], $data) && !$field_type['default']) {
                return response(['status' => 'error', 'error' => [$field_type['name'] => 'Field has no default value']]);
            }
            if (!array_key_exists($field_type['name'], $data) && $field_type['default']) {
                $data[$field_type['name']] = $field_type['default'];
            }
            $res[$field_type['id']] = $data[$field_type['name']];
        }

        foreach($res as $key => $value) {
            $field = ProfileFields::create([
                'profile_id' => $profile_id,
                'field_type_id' => $key,
                'value' => $value
            ]);
        }

        return response(['status' => 'ok', 'response' => $res], 200);
    }

    public function get_profile(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'profile_id' => 'required|exists:profile_fields,profile_id'
        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors()]);
        }

        $profile_id = $data['profile_id'];
        $profile = Profile::where('id', $profile_id)->get();
        $fields = ProfileFields::where('profile_id', $profile_id)->get();

        return response(['status' => 'ok', 'response' => ['profile_id' => $profile_id, 'fields' => $fields]]);
    }

    public function get_recomendations(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'profile_id' => 'required|exists:profile_fields,profile_id'
        ]);

        
        if($validator->fails()){
            return response(['error' => $validator->errors()]);
        }

        $profile_id = $data['profile_id'];
        $age_pref = explode(':', ProfileFields::where('profile_id', $profile_id)->where('field_type_id', '5')->get()->first()->value);
        //return response(['status' => 'ok', 'response' => ['profile_id' => $profile_id, 'profiles' => $age_pref]]);
        
        $sex_pref = explode(',', ProfileFields::where('profile_id', $profile_id)->where('field_type_id', '6')->get()->first()->value);
        //return response(['status' => 'ok', 'response' => ['profile_id' => $profile_id, 'profiles' => $sex_pref]]);
        //return $sex_pref;
        //orWhere('value', $sex_pref)->
/*
        $recs_age = ProfileFields::
            where('field_type_id', '1')->
            whereBetween('value', $age_pref)->
            where('profile_id', '<>', $profile_id);

        $recs_sex = ProfileFields::
            where('field_type_id', '3')->
            where('value', $sex_pref)->
            where('profile_id', '<>', $profile_id)->
            union($recs_age)->
            groupBy('profile_id')->
            get();
        */
        /*
        $recs_age = ProfileFields::
            where(function ($q) use ($age_pref) {
                $q->where('field_type_id', '1')->
                    whereBetween('value', $age_pref);
            })->
            orWhere(function ($q) use ($sex_pref) {
                $q->where('field_type_id', '3')->
                    where('value', $sex_pref);
            })
            ->where('profile_id', '<>', $profile_id)->get();

        */
        /*
        $recs_age = ProfileFields::
    select('profile_id')->
    where(function ($q) use ($age_pref, $profile_id) {
        $q->where('field_type_id', '1')->
            whereBetween('value', $age_pref)->
            where('profile_id', '<>', $profile_id);
    })->
    where(function ($q) use ($sex_pref, $profile_id) {
        $q->where('field_type_id', '3')->
            where('value', $sex_pref)->
            where('profile_id', '<>', $profile_id);
    })
    ->distinct()
    ->get(['profile_id']);*/
    //toSql()
        //$all_recs = $recs_age->merge($recs_sex);
        //$sex_pref1 = $sex_pref[0];
        //return $sex_pref1;
        //$age_pref[0] = $age_pref[0];
        //$age_pref[1] = $age_pref[1];
        $results = DB::select( DB::raw("SELECT distinct p.* FROM 
            profiles p 
            JOIN profile_fields pf_age ON pf_age.`profile_id` = p.`id` and pf_age.`field_type_id` = 1 and pf_age.`value` BETWEEN :age_from AND :age_to
            JOIN profile_fields pf_sex ON pf_sex.`profile_id` = p.`id` and pf_sex.`field_type_id` = 3 and pf_sex.`value` = :sex
            WHERE p.`id` != $profile_id and  (pf_age.`id` IS NOT NULL and pf_sex.`id` IS NOT NULL)"), array(
            'age_from' => $age_pref[0],
            'age_to' => $age_pref[1],
            'sex' => $sex_pref[0],
        ));

        return response(['status' => 'ok', 'response' => ['profile_id' => $profile_id, 'profiles' => $results]]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'user_id' => 'required|max:255'
        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $profile = Profile::create($data);

        return response([ 'profile' => new ProfileResource($profile), 'message' => 'Created successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function show(Profile $profile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profile $profile)
    {
        $profile->update($request->all());

        return response([ 'profile' => new ProfileResource($profile), 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profile $profile)
    {
        $profile->delete();

        return response(['message' => 'Deleted']);
    }

    public function upload_photo(Request $request){
        $data = $request->all();

        $validator = Validator::make($data, [
            'photo' => 'required',
            'profile_id' => 'required|exists:profiles,id'
        ]);

        if($validator->fails()){
            return response(['status' => 'error', 'error' => $validator->errors()]);
        }

        $add_photo = UploadPhotoModel::create($data);
        return response(['status' => 'ok', 'response' => $add_photo], 200);
    }


    public function get_photo(Request $request){
        $data = $request->all();

        $validator = Validator::make($data, [
            'profile_id' => 'required|exists:profiles,id'
        ]);

        if($validator->fails()){
            return response(['status' => 'error', 'error' => $validator->errors()]);
        }

        $profile_id = $data['profile_id'];
        $add_photo = UploadPhotoModel::where('profile_id', $profile_id)->get();
        return response(['status' => 'ok', 'response' => $add_photo], 200);
    }

    public function add_hashtag(Request $request){
        $data = $request->all();

        $validator = Validator::make($data, [
            'hashtag' => 'required',
            'profile_id' => 'required|exists:profiles,id'
        ]);

        if($validator->fails()){
            return response(['status' => 'error', 'error' => $validator->errors()]);
        }

        $add_tag = HashTagsProfilModel::create($data);
        return response(['status' => 'ok', 'response' => $add_tag], 200);
    }
}
