<?php

namespace App\Http\Controllers\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Referensi\Upload;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:file', ['only' => ['index']]);
        $this->middleware('permission:create-upload|update-upload', ['only' => ['updateOrCreate', 'hide']]);
        $this->middleware('permission:delete-upload', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $default = (object) ['sortBy' => 'id', 'sortDesc' => false];
        $fields = ['judul', 'jenis', 'keterangan'];
        $data = Upload::where(function ($query) use ($request, $fields) {
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

    public function updateOrCreate(Request $request)
    {

        // if ($request->id == null || $request->id == '') {
        //     $file = $request->file;
        //     list($type, $file) = explode(';', $file);
        //     list(, $data) = explode(':', $type);
        //     list($data, $ext) = explode('/', $data);

        //     list(, $file) = explode(',', $file);

        //     $file = base64_decode($file);
        //     $filename = $request->folder . '/' . str_slug($request->judul) . '.' . $ext;
        //     Storage::disk('public-path')->put($filename, $file);

        //     $data = array_merge($request->only('judul'), ['jenis' => $ext, 'file_url' => '/imagestore/' . $filename, 'keterangan' => $request->keterangan, 'folder' => $request->folder]);

        //     $upload = Upload::create($data);
        // } else if ($request->file) {
        //     $upload = Upload::where('id', $request->id)->update($request->all());
        // }

        if (strlen($request->file) > 100) {
            $file = $request->file;
            $check = Upload::where('id', $request->id)->pluck('file_url')->first();
            $id = Upload::orderBy('id', 'desc')->pluck('id')->first();

            list($type, $file) = explode(';', $file);
            list(, $data) = explode(':', $type);
            list($data, $ext) = explode('/', $data);

            list(, $file) = explode(',', $file);

            $file = base64_decode($file);
            $filename = $request->folder . '/' . str_slug($request->judul) . '.' . $ext;

            if ($check) {
                Storage::disk('galeri-path')->delete('/' . substr($check, 12));
            }
            $oke = Storage::disk('galeri-path')->put($filename, $file);
            $data = array_merge($request->only('judul'), ['jenis' => $ext, 'file_url' => '/imagestore/' . $filename, 'keterangan' => $request->keterangan, 'folder' => $request->folder]);
        } else {
            $data = $request->all();
        }


        $bank = Upload::updateOrCreate($request->only('id'), $data);

        if ($bank) {
            $status = true;
            $response = $bank->wasRecentlyCreated;
        } else {
            $status = false;
            $response = null;
        }

        return response()->json([
            'status' => $status,
            'response' => $response,
            'oke' => $oke
        ]);
    }


    public function destroy(Request $request)
    {
        $id = Upload::where('id', $request->id)->pluck('file_url')->first();
        $Uploads = Upload::where('id',  $request->id)->first();

        $disk = Storage::disk('public-path')->delete(substr($id, 12));
        $Uploads->delete();
        $status = true;

        return response()->json([
            'status' => $status
        ]);
    }

    public function hide(Request $request)
    {

        try {

            $Upload = Upload::findOrFail($request->id);

            $Upload->update([
                'ishide' => $Upload->ishide == 1 ? 0 : 1
            ]);

            if ($Upload) {
                $status = true;
            } else {
                $status = false;
            }
        } catch (\Exception $e) {
            $status = false;
        }

        return response()->json([
            'status' => $status
        ]);
    }
}
