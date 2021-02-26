<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Referensi\BrandKontak;
use App\Http\Requests\Referensi\StoreBrandKontak;
use Illuminate\Support\Str;

class BrandKontakController extends Controller
{
    // function __construct()
    // {
    //     $this->middleware('permission:read-kontak', ['only' => ['index']]);
    //     $this->middleware('permission:create-kontak|update-kontak', ['only' => ['updateOrCreate', 'reorder', 'hide']]);
    //     $this->middleware('permission:delete-kontak', ['only' => ['destroy',  'destroys']]);
    // }

    public function index(Request $request)
    {

        $data = BrandKontak::where('brand_id', $request->brand_id)->where(function ($query) use ($request) {
            $query->where('id', "LIKE", "%" . $request->search . "%")
                ->orwhere('isi', "LIKE", "%" . $request->search . "%")
                ->orwhere('jenis', "LIKE", "%" . $request->search . "%");
        })
            ->paginate($request->size);

        return response()->json([
            'data' => $data
        ]);
    }

    public function updateOrCreate(StoreBrandKontak $request)
    {

        $request->validated();
        $v = $request->jenis;

        switch ($v) {
            case 1:
                $icon = 'fa fa-twitter';
                $nama_jenis = 'Twitter';
                break;
            case 2:
                $icon = 'fab fa-instagram';
                $nama_jenis = 'Instagram';
                break;
            case 3:
                $icon = 'fab fa-youtube';
                $nama_jenis = 'Youtube';
                break;
            case 4:
                $icon = 'fa fa-phone';
                $nama_jenis = 'Telepon';
                break;
            case 5:
                $icon = 'fa fa-mobile';
                $nama_jenis = 'Handphone';
                break;
            case 6:
                $icon = 'fa fa-map-marker';
                $nama_jenis = 'Lokasi';
                break;
            case 7:
                $icon = 'fa fa-envelope';
                $nama_jenis = 'Email';
                break;
            case 8:
                $icon = 'fab fa-whatsapp';
                $nama_jenis = 'WhatsApp';
                break;
            case 9:
                $icon = 'fa fa-question';
                $nama_jenis = 'Others';
                break;
            case 0:
                $icon = 'fab fa-facebook-f';
                $nama_jenis = 'Facebook';
                break;
            case 10:
                $icon = 'fa fa-linkedin';
                $nama_jenis = 'Linkedin';
                break;
            default:
                $icon = '';
                $nama_jenis = '';
                break;
        }
        $_id = $request->_id ? $request->_id : Str::uuid();
        $merge = array_merge($request->all(), ['icon' => $icon, 'nama_jenis' => $nama_jenis, '_id' => $_id, 'brand_id' => $request->brand_id]);
        $Contact = BrandKontak::updateOrCreate($request->only('id', 'brand_id'), $merge);

        if ($Contact) {
            $status = true;
            $response = $Contact->wasRecentlyCreated;
        } else {
            $status = false;
            $response = null;
        }

        return response()->json([
            'status' => $status,
            'status' => $icon
        ]);
    }
    public function destroy(Request $request)
    {
        $data = BrandKontak::destroy($request->id);

        if (!$data) {
            $status = false;
        } else {
            $status = true;
        }
        return response()->json([
            'status' => $status
        ]);
    }

    public function destroys(Request $request)
    {
        $success = 0;
        $fail = 0;

        foreach ($request->item as $key => $value) {
            $data = BrandKontak::destroy($value['id']);

            if (!$data) {
                $status = false;
                $fail++;
            } else {
                $status = true;
                $success++;
            }
        }

        return response()->json([
            'status' => $status,
            'success' => $success,
            'fail' => $fail
        ]);
    }

    public function hide(Request $request)
    {
        $hide = BrandKontak::where('brand_id', $request->brand_id)->where('id', $request->id)->pluck('ishide')->first();
        $bank = BrandKontak::where('brand_id', $request->brand_id)->where('id', $request->id)->update(['ishide' => $hide == 1 ? 0 : 1]);

        if ($bank) {
            $status = true;
        } else {
            $status = false;
        }

        return response()->json([
            'hide' => $hide,
            'status' => $status,
        ]);
    }

    public function reorder(Request $request)
    {
        $folder = BrandKontak::where('brand_id', $request->brand_id)->where('id', $request->id)->update(['reorder' => $request->reorder]);

        if ($folder) {
            $status = true;
        } else {
            $status = false;
        }


        return response()->json([
            'status' => $status
        ]);
    }
    public function ajax(Request $request)
    {
        $data = BrandKontak::where('brand_id', $request->brand_id)->select('id as value', 'nama_folder as text')->where('isedit', 0)->get();

        return response()->json([
            'data' => $data
        ]);
    }
}
