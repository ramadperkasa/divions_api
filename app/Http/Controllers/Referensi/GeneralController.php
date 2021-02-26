<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Referensi\General;
use Illuminate\Support\Str;
use App\Http\Requests\Referensi\StoreGeneral;


class GeneralController extends Controller
{
    // function __construct()
    // {
    //     $this->middleware('permission:read-gallery-kategori', ['only' => ['index']]);
    //     $this->middleware('permission:create-gallery-kategori|update-gallery-kategori', ['only' => ['updateOrCreate', 'hide', 'reorder']]);
    //     $this->middleware('permission:delete-gallery-kategori', ['only' => ['destroy', 'destroys']]);
    // }


    public function index(Request $request)
    {
        $data = General::where('ishide', 0)->orderBy('reorder', 'asc')->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function updateOrCreate(StoreGeneral $request)
    {
        $count = 0;
        $data = $request->all();

        foreach ($data as $index => $item) {
            if (is_array($item)) {
                if (array_key_exists("value", $item)) {
                    $checkGeneral = General::where('id', $item['id'])->update(['isi' => $item['value']]);
                } else {
                    $checkGeneral = General::where('id', $item['id'])->update(['isi' => '']);
                }
            }
        }

        // if ($checkGeneral) {
        //     $status = true;
        //     $response = $checkGeneral->wasRecentlyCreated;
        // } else {
        //     $status = false;
        //     $response = null;
        // }



        return response()->json([
            'response' => $request->all(),
            'count' => $count
        ]);
    }
}
