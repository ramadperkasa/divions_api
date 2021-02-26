<?php

namespace App\Http\Controllers\Transaksi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Transaksi\Gallery;
use App\Model\Transaksi\Image;
use App\Model\Transaksi\DetailGallery;
use App\Http\Requests\Transaksi\StoreGallery;
use Carbon\Carbon;

class GalleryController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:read-gallery', ['only' => ['index']]);
        $this->middleware('permission:create-gallery|update-gallery', ['only' => ['updateOrCreate', 'hide', 'reoder']]);
        $this->middleware('permission:delete-gallery', ['only' => ['destroy', 'destroys']]);
    }

    public function index(Request $request)
    {
        $default = (object) ['sortBy' => 'id', 'sortDesc' => false];
        $fields = ['id_kategori', 'image_id', 'tgl_publish', 'judul', 'judul_en'];
        $data = Gallery::where(function ($query) use ($request, $fields) {
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
        $data = $data->with('detailgallery')->withCount('detailgallery')->paginate($request->itemsPerPage);

        return response()->json([
            'data' => $data
        ]);
    }
    public function updateDetailGallery(Request $request)
    {
        $delete = DetailGallery::where('gallery_id', $request->id)->delete();
        if (count($request->image) > 0) {
            foreach ($request->image as $a) {
                $soalTemplateDetail = DetailGallery::create(['gallery_id' => $request->id, 'image_id' => $a]);
            }
            if ($soalTemplateDetail) {
                $status = true;
                $response = $soalTemplateDetail->wasRecentlyCreated;
            } else {
                $status = false;
                $response = null;
            }
        } else if ($delete) {
            $status = true;
        }
        return response()->json([
            'status' => $status
        ]);
    }
    public function updateOrCreate(StoreGallery $request)
    {
        $request->validated();
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
        if (!is_file($request->image_id)) {
            $type = 2;
        } else {
            if (in_array(strtolower(pathinfo($request->image, PATHINFO_EXTENSION)), $supported_image)) {
                $type = 1;
            } else {
                $type = 0;
            }
        }

        if ($request->id == null || $request->id == '') {
            $getId = Gallery::orderBy('id', 'DESC')->pluck('id')->first();

            $id = $getId + 1;
        } else {
            $id = $request->id;
        }

        $now = Carbon::now()->format('Y-m-d h:i:s');
        $image_id = Image::where('image', $request->image)->pluck('id')->first();
        $merge = array_merge(['tgl_publish' => $now, 'image_id' => $image_id], $request->except('tgl_publish'));
        $gallery = Gallery::updateOrCreate(['id' => $id], $merge);
        $delete = DetailGallery::where('gallery_id', $id)->delete();
        foreach ($request->images as $key => $value) {
            $images_id = Image::where('image', $value)->pluck('id')->first();                
            $getDetailId = DetailGallery::orderBy('id', 'DESC')->pluck('id')->first();
            $detailId = $getDetailId + 1;

            $mergeDetail = ['id' => $detailId, 'gallery_id' => $id, 'image_id' => $images_id];
            $galleryDetail = DetailGallery::insert($mergeDetail);
        }

        if ($gallery) {
            $status = true;
            $response = $gallery->wasRecentlyCreated;
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
        $data = Gallery::where('id', $request->id)->withCount('detailgallery')->first();

        $destroy =  Gallery::destroy($request->id);
        $destroyDetail =  DetailGallery::where('gallery_id', $request->id)->delete();
        $status = true;

        return response()->json([
            'status' => $status
        ]);
    }

    public function destroys(Request $request)
    {
        $success = 0;
        $fail = 0;

        foreach ($request->item as $key => $value) {
            $data = Gallery::where('id', $value['id'])->first();

            if (!$data) {
                $status = false;
                $fail++;
            } else {
                $galleryKategori = Gallery::destroy($value['id']);
                $galleryKategori = DetailGallery::where('gallery_id', $value['id'])->delete();
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
        $hide = Gallery::where('id', $request->id)->pluck('ishide')->first();
        $bank = Gallery::where('id', $request->id)->update(['ishide' => $hide == 1 ? 0 : 1]);

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
        $bank = Gallery::where('id', $request->id)->update(['reorder' => $request->reorder]);

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
        $data = Gallery::select('id as value', 'nama as text')->get();

        return response()->json([
            'data' => $data
        ]);
    }
    public function ajaxId(Request $request)
    {
        $data = Gallery::where('id', $request->id)->with('detailgallery')->get();

        return response()->json([
            'data' => $data
        ]);
    }
}
