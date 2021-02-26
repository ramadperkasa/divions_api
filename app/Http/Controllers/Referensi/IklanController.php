<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Referensi\StoreIklan;
use App\Model\Referensi\Iklan;
use App\Model\Transaksi\Image;
use Illuminate\Support\Facades\Storage;

class IklanController extends Controller
{
    function __construct()
    {
        // $this->middleware('permission:read-iklan', ['only' => ['index']]);
        // $this->middleware('permission:create-iklan|update-iklan', ['only' => ['updateOrCreate', 'hide', 'reorder']]);
        // $this->middleware('permission:delete-iklan', ['only' => ['destroy', 'destroys']]);
    }

    public function index(Request $request)
    {
        $default = (object) ['sortBy' => 'id', 'sortDesc' => false];
        $fields = ['nama', 'url', 'reorder'];
        $data = Iklan::where(function ($query) use ($request, $fields) {
            foreach ($fields as $item) {
                $query->orWhere($item, 'LIKE', "%" . $request->search . "%");
            }
        });
        if (!is_null($request->sortBy)) {
            if (count($request->sortBy) > 0) {
                for ($i = 0; $i < count($request->sortBy); $i++) {
                    $data = $data->orderBy($request->sortBy[$i], $request->sortDesc[$i] == 'false' ? 'asc' : 'desc');
                }
            }
        } else {
            $data = $data->orderBy($default->sortBy, $default->sortDesc ? 'desc' : 'asc');
        }
        $data = $data->paginate($request->itemsPerPage);

        return response()->json([
            'data' => $data
        ]);
    }

    public function updateOrCreate(StoreIklan $request)
    {
        $request->validated();
        if (strlen($request->foto_iklan) > 300) {
            $file = $request->foto_iklan;
            $check = Iklan::where('id', $request->id)->pluck('foto_iklan')->first();
            $id = Iklan::orderBy('id', 'desc')->pluck('id')->first();
            $reorder = Iklan::where('type', $request->type)->orderBy('reorder', 'desc')->pluck('reorder')->first();
            list($type, $file) = explode(';', $file);
            list(, $data) = explode(':', $type);
            list($data, $ext) = explode('/', $data);
            list(, $file) = explode(',', $file);

            $file = base64_decode($file);
            $filename = '/file/' . 'iklan' . '/' . str_slug($request->nama) . '_' . time() . '.' . $ext;


            if ($check) {
                Storage::disk('galeri_path')->delete('/' . substr($check, 8));
            }

            Storage::disk('galeri_path')->put($filename, $file);

            $data = array_merge($request->except('foto_iklan'), ['foto_iklan' => 'storage' . $filename, 'reorder' => $reorder + 1]);
        } else {
            $data = $request->all();
        }

        $Iklan = Iklan::updateOrCreate($request->only('id'), $data);

        if ($Iklan) {
            $status = true;
            $response = $Iklan->wasRecentlyCreated;
        } else {
            $status = false;
            $response = null;
        }

        return response()->json([
            'status' => $status,
            'response' => $response
        ]);
    }

    public function destroy(Request $request)
    {
        $data = Iklan::destroy($request->id);
        $id = Image::where('id', $request->id)->pluck('image')->first();

        if (!$data) {
            $status = false;
        } else {
            Storage::disk('galeri_path')->delete(substr($id, 8));
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
            $data = Iklan::destroy($value['id']);
            $id = Iklan::where('id', $value['id'])->pluck('foto_iklan')->first();

            if (!$data) {
                $status = false;
                $fail++;
            } else {
                Storage::disk('galeri_path')->delete(substr($id, 8));
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
        $hide = Iklan::where('id', $request->id)->pluck('ishide')->first();
        $bank = Iklan::where('id', $request->id)->update(['ishide' => $hide == 1 ? 0 : 1]);

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
        $bank = Iklan::where('id', $request->id)->update(['reorder' => $request->reorder]);

        if ($bank) {
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
        $data = Iklan::select('id as value', 'nama as text')->get();

        return response()->json([
            'data' => $data
        ]);
    }
}
