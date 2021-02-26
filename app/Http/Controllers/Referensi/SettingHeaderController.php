<?php

namespace App\Http\Controllers\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Referensi\SettingHeader;
use App\Http\Requests\Referensi\StoreSettingHeader;

class SettingHeaderController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:setting-header', ['only' => ['index']]);
        $this->middleware('permission:create-setting-header|update-setting-header', ['only' => ['updateOrCreate', 'hide']]);
        $this->middleware('permission:delete-setting-header', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        try {
            $data = SettingHeader::first();
        } catch (\Exception $e) {
            $data = [];
        }
        return response()->json([
            'data' => $data
        ]);
    }

    public function updateOrCreate(Request $request)
    {
        $data = SettingHeader::where('id',1)->update(['foto' => $request->image_url]);

        if($data){
            $status = true;

        }else{
            $status = false;
        }

        return response()->json([
            'status' => $status
        ]);
    }
}
