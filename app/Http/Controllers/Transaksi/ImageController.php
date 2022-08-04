<?php

namespace App\Http\Controllers\Transaksi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Transaksi\Image;
use App\Model\Referensi\Folder;
use App\Http\Requests\Transaksi\StoreImage;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use ImageLib;

class ImageController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:read-uploads', ['only' => ['index']]);
        // $this->middleware('permission:create-uploads|update-uploads', ['only' => ['updateOrCreate', 'reorder', 'hide']]);
        // $this->middleware('permission:delete-uploads', ['only' => ['destroy', 'destroys']]);
    }

    public function index(Request $request)
    {
        $default = (object) ['sortBy' => 'id', 'sortDesc' => true];
        $fields = ['description', 'description_en', 'folder_id'];
        $data = Image::where(function ($query) use ($request, $fields) {
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

        if (!is_null($request->folder_id)) {
            if ($request->folder_id) {
                $data->where('folder_id', $request->folder_id);
            }
        }

        if (!is_null($request->type)) {
            if ($request->type == 0) {
                $data->where('type', $request->type);
            } else {
                $data->where('type', $request->type);
            }
        }

        $data = $data->with('folder')->withCount('detailimages', 'gallery', 'berita', 'slider', 'investor', 'vacancy', 'brand')->paginate($request->itemsPerPage);

        return response()->json([
            'data' => $data,

        ]);
    }
    public function onlyImage(Request $request)
    {
        $default = (object) ['sortBy' => 'id', 'sortDesc' => false];
        $fields = ['image', 'description', 'description_en', 'folder_id'];
        $data = Image::where(function ($query) use ($request, $fields) {
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

        if (!is_null($request->folder_id)) {
            if ($request->folder_id) {
                $data->where('folder_id', $request->folder_id);
            }
        }

        $data = $data->with('folder')->where('type', 0)->paginate($request->itemsPerPage);

        return response()->json([
            'data' => $data,

        ]);
    }

    public function updateOrCreate(StoreImage $request)
    {
        $request->validated();
        $folder = Folder::where('id', $request->folder_id)->pluck('nama_folder')->first();
        if (strlen($request->image) > 100) {
            $file = $request->image;
            $check = Image::where('id', $request->id)->pluck('image')->first();
            $id = Image::orderBy('id', 'desc')->pluck('id')->first();
            list($type, $file) = explode(';', $file);
            list(, $data) = explode(':', $type);
            list($data, $ext) = explode('/', $data);
            list(, $file) = explode(',', $file);

            if ($check) {
                Storage::disk('galeri_path')->delete('/' . substr($check, 8));
            }

            $file = base64_decode($file);
            $name = '/file/' . $folder . '/' . str_slug($request->description) . '_' . time();

            $filename = $name . '.' . $ext;
            Storage::disk('galeri_path')->put($filename, $file);

            if ($request->type == 0) {
                $xsmallthumbnail = $name . '_xsmall' . '.' . $ext;
                $smallthumbnail = $name . '_small' . '.' . $ext;
                $mediumthumbnail = $name . '_medium' . '.' . $ext;
                $largethumbnail = $name . '_large' . '.' . $ext;
                $xlargethumbnail = $name . '_xlarge' . '.' . $ext;


                Storage::disk('galeri_path')->put($xsmallthumbnail, $file);
                Storage::disk('galeri_path')->put($smallthumbnail, $file);
                Storage::disk('galeri_path')->put($mediumthumbnail, $file);
                Storage::disk('galeri_path')->put($largethumbnail, $file);
                Storage::disk('galeri_path')->put($xlargethumbnail, $file);

                $this->createThumbnail(storage_path('app/public/' . $xsmallthumbnail), 80, 50);
                $this->createThumbnail(storage_path('app/public/' . $smallthumbnail), 150, 93);
                $this->createThumbnail(storage_path('app/public/' . $mediumthumbnail), 300, 185);
                $this->createThumbnail(storage_path('app/public/' . $largethumbnail), 550, 340);
                $this->createThumbnail(storage_path('app/public/' . $xlargethumbnail), 950, 740);
            }


            $data = array_merge($request->only('image'), ['jenis' => $request->jenis, 'type' => $request->type, 'image' => 'storage' . $filename, 'description' => $request->description, 'folder_id' => $request->folder_id]);
        } else {
            $data = $request->all();
        }

        $bank = Image::updateOrCreate($request->only('id'), $data);

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
            'folder' => $folder
        ]);
    }

    public function destroy(Request $request)
    {
        $id = Image::where('id', $request->id)->pluck('image')->first();
        $data = Image::where('id', $request->id)->first();
        $arr = ['xsmall', 'small', 'medium', 'large', 'xlarge'];
        $path = null;
        try {
            if (!$data) {
                $status = false;
            } else {
                $destroy =  Image::destroy($request->id);
                Storage::disk('galeri_path')->delete(substr($id, 8));
                $path = pathinfo(substr($id, 8));
                $dirPath = $path['dirname'];
                $trimPath = $path['filename'];
                $extPath = $path['extension'];


                foreach ($arr as $key => $value) {
                    Storage::disk('galeri_path')->delete($dirPath . '/' . $trimPath . '_' . $value . '.' . $extPath);
                }

                $status = true;
            }
        } catch (\Exception $e) {
            $status = false;
        }

        return response()->json([
            'status' => $status,
            'path1' => $path,
        ]);
    }

    public function destroys(Request $request)
    {
        $success = 0;
        $fail = 0;

        foreach ($request->item as $key => $value) {
            $data = Image::where('id', $value['id'])->first();
            $id = Image::where('id', $value['id'])->pluck('image')->first();

            if (!$data) {
                $status = false;
                $fail++;
            } else {
                $image = Image::destroy($value['id']);
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
    public function ajax(Request $request)
    {
        $data = Image::all();

        return response()->json([
            'data' => $data
        ]);
    }
    public function createThumbnail($path, $width, $height)
    {
        $img = ImageLib::make($path)->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save($path);
    }
    public function upload(Request $request)
    {
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

        $id = Image::orderBy('id', 'DESC')->pluck('id')->first();

        $id = $id + 1;

        if ($request->embed) {
            $type = 2;
            $data = ['id' => $id, 'image' => $request->embed, 'type' => $type, 'folder_id' => '4', 'description' => time()];
        } else {
            $name = '/file/upload/' . time() . '_' . pathinfo($request->file->getClientOriginalName(), PATHINFO_FILENAME);
            $imageName = $name . '.' . $request->file->getClientOriginalExtension();

            if (in_array($request->file->getClientOriginalExtension(), $supported_image)) {
                $type = 1;
            } else {
                $type = 0;
            }

            Storage::disk('galeri_path')->put($imageName, file_get_contents($request->file));

            if ($type == 0) {
                $xsmallthumbnail = $name  . '_xsmall' . '.' . $request->file->getClientOriginalExtension();
                $smallthumbnail = $name  . '_small' . '.' . $request->file->getClientOriginalExtension();
                $mediumthumbnail = $name  . '_medium' . '.' . $request->file->getClientOriginalExtension();
                $largethumbnail = $name  . '_large' . '.' . $request->file->getClientOriginalExtension();
                $xlargethumbnail = $name  . '_xlarge' . '.' . $request->file->getClientOriginalExtension();

                Storage::disk('galeri_path')->put($xsmallthumbnail, file_get_contents($request->file));
                Storage::disk('galeri_path')->put($smallthumbnail, file_get_contents($request->file));
                Storage::disk('galeri_path')->put($mediumthumbnail, file_get_contents($request->file));
                Storage::disk('galeri_path')->put($largethumbnail, file_get_contents($request->file));
                Storage::disk('galeri_path')->put($xlargethumbnail, file_get_contents($request->file));

                $this->createThumbnail(storage_path('app/public/' . $xsmallthumbnail), 80, 50);
                $this->createThumbnail(storage_path('app/public/' . $smallthumbnail), 150, 93);
                $this->createThumbnail(storage_path('app/public/' . $mediumthumbnail), 300, 185);
                $this->createThumbnail(storage_path('app/public/' . $largethumbnail), 550, 340);
                $this->createThumbnail(storage_path('app/public/' . $xlargethumbnail), 950, 740);
            }


            $data = array_merge($request->only('image'), ['id' => $id, 'image' => 'storage' . $imageName, 'type' => $type, 'folder_id' => '4', 'description' => time()]);
        }

        $bank = Image::insert($data);

        if ($bank) {
            $status = true;
        } else {
            $status = false;
        }

        return response()->json([
            'status' => $status
        ]);
    }
}
