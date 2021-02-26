<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Transaksi\Image;
use App\Model\Referensi\GalleryKategori;
use App\Model\Referensi\Kategori;
use App\Model\Referensi\Page;
use App\Model\Referensi\Blok;
use App\Model\Transaksi\Berita;
use App\Model\Referensi\Folder;
use Illuminate\Support\Facades\Storage;

class AjaxWebController extends Controller
{
    public function image()
    {
        $data = Image::all();

        return response()->json([
            'data' => $data
        ]);
    }

    public function gambarDetail(Request $request)
    {
        if ($request->folder_id == '' || $request->folder_id == null) {
            $data = Image::orderBy($request->field, $request->order)->paginate($request->size);
        } else {
            $data = Image::where('folder_id', $request->folder_id)->orderBy($request->field, $request->order)->paginate($request->size);
        }

        return response()->json([
            'data' => $data
        ]);
    }

    public function uploadImage(Request $request)
    {
        $imageName = '/file/upload/' . time() . '_' . pathinfo($request->file->getClientOriginalName(), PATHINFO_FILENAME) . '.' . $request->file->getClientOriginalExtension();

        Storage::disk('galeri_path')->put($imageName, file_get_contents($request->file));

        $supported_image = array(
            'webm',
            'mpg',
            'mp2',
            'mpeg',
            'mpe',
            'mpv',
            'mp4',
            'm4p',
            'm4v',
            'avi',
            'wmv',
            'mov',
            'qt',
            'flv',
            'swf',
            'avchd',
        );

        $ex = strtolower(pathinfo($request->file->getClientOriginalName(), PATHINFO_EXTENSION));



        if (in_array($ex, $supported_image)) {
            $type = 1;
        } else {
            $type = 0;
        }

        $data = array_merge($request->only('image'), ['image' => '/storage/' . $imageName, 'folder_id' => '4', 'type' => $type, 'description' => time()]);

        $bank = Image::insert($data);

        if ($bank) {
            $status = true;
        } else {
            $status = false;
        }

        return response()->json([
            'status' => $status,
            'get' => $request->file->getClientOriginalName()
        ]);
    }


    public function kategoriAlbum()
    {
        $data = GalleryKategori::all();

        return response()->json([
            'data' => $data
        ]);
    }
    public function kategori()
    {
        $data = Kategori::all();

        return response()->json([
            'data' => $data
        ]);
    }

    public function halamanId(Request $request)
    {
        $data = Page::where('id', $request->id)->get();

        return response()->json([
            'data' => $data
        ]);
    }
    public function beritaId(Request $request)
    {
        $data = Berita::where('id', $request->id)->get();

        return response()->json([
            'data' => $data
        ]);
    }
    public function blockId(Request $request)
    {
        $data = Blok::where('id', $request->id)->get();

        return response()->json([
            'data' => $data
        ]);
    }
}
