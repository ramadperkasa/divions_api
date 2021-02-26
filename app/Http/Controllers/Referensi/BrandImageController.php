<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Referensi\BrandImage;
use App\Model\Referensi\BrandFolder;
use App\Http\Requests\Referensi\StoreBrandImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ImageLib;

class BrandImageController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:read-brand-uploads', ['only' => ['index']]);
        // $this->middleware('permission:create-brand-uploads|edit-brand-uploads', ['only' => ['updateOrCreate', 'reorder', 'hide']]);
        // $this->middleware('permission:delete-brand-uploads', ['only' => ['destroy', 'destroys']]);
    }

    public function index(Request $request)
    {
        $default = (object) ['sortBy' => 'id', 'sortDesc' => false];
        $fields = ['description', 'description_en', 'folder_id'];
        $data = BrandImage::where('brand_id', $request->brand_id)->where(function ($query) use ($request, $fields) {
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

        $data = $data->with('folder')->paginate($request->itemsPerPage);

        return response()->json([
            'data' => $data,

        ]);
    }
    public function onlyImage(Request $request)
    {
        $default = (object) ['sortBy' => 'id', 'sortDesc' => false];
        $fields = ['image', 'description', 'description_en', 'folder_id'];
        $data = BrandImage::where('brand_id', $request->brand_id)->where(function ($query) use ($request, $fields) {
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

    public function updateOrCreate(StoreBrandImage $request)
    {
        $request->validated();
        $folder = BrandFolder::where('brand_id', $request->brand_id)->where('id', $request->folder_id)->pluck('nama_folder')->first();
        $_id = $request->_id ? $request->_id : Str::uuid();
        if (strlen($request->image) > 100) {
            $file = $request->image;
            $check = BrandImage::where('brand_id', $request->brand_id)->where('id', $request->id)->pluck('image')->first();
            $id = BrandImage::where('brand_id', $request->brand_id)->orderBy('id', 'desc')->pluck('id')->first();
            list($type, $file) = explode(';', $file);
            list(, $data) = explode(':', $type);
            list($data, $ext) = explode('/', $data);
            list(, $file) = explode(',', $file);

            $file = base64_decode($file);

            if ($check) {
                Storage::disk('galeri_path')->delete('/' . substr($check, 8));
            }



            $filename = '/file/brand/' . $folder . '/' . str_slug($request->description) . '_' . time() . '.' . $ext;

            if ($request->type == 0) {
                $xsmallthumbnail = '/file/brand/' . $folder . '/' . str_slug($request->description) . '_' . time() . '_xsmall' . '.' . $ext;
                $smallthumbnail = '/file/brand/' . $folder . '/' . str_slug($request->description) . '_' . time() . '_small' . '.' . $ext;
                $mediumthumbnail = '/file/brand/' . $folder . '/' . str_slug($request->description) . '_' . time() . '_medium' . '.' . $ext;
                $largethumbnail = '/file/brand/' . $folder . '/' . str_slug($request->description) . '_' . time() . '_large' . '.' . $ext;
                $xlargethumbnail = '/file/brand/' . $folder . '/' . str_slug($request->description) . '_' . time() . '_xlarge' . '.' . $ext;

                Storage::disk('galeri_path')->put($filename, $file);
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

            $data = array_merge($request->only('image'), ['jenis' => $request->jenis, 'type' => $request->type, 'image' => 'storage' . $filename, 'description' => $request->description, 'folder_id' => $request->folder_id, '_id' => $_id, 'brand_id' => $request->brand_id]);
        } else {
            $data = array_merge($request->except('_id', 'brand_id'), ['_id' => $_id, 'brand_id' => $request->brand_id]);
        }

        $brands = BrandImage::updateOrCreate($request->only('id', 'brand_id'), $data);

        if ($brands) {
            $status = true;
            $response = $brands->wasRecentlyCreated;
        } else {
            $status = false;
            $response = null;
        }

        return response()->json([
            'data' => $data,
            'status' => $status,
            'response' => $response,
        ]);
    }

    public function destroy(Request $request)
    {
        $id = BrandImage::where('id', $request->id)->pluck('image')->first();
        $data = BrandImage::where('id', $request->id)->first();
        try {
            if (!$data) {
                $status = false;
            } else {
                $destroy =  BrandImage::destroy($request->id);
                Storage::disk('galeri_path')->delete(substr($id, 8));
                $status = true;
            }
        } catch (\Exception $e) {
            $status = false;
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
            $data = BrandImage::where('id', $value['id'])->first();
            $id = BrandImage::where('id', $value['id'])->pluck('image')->first();

            if (!$data) {
                $status = false;
                $fail++;
            } else {
                $image = BrandImage::destroy($value['id']);
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
        $data = BrandImage::where('brand_id', $request->brand_id)->all();

        return response()->json([
            'data' => $data
        ]);
    }
    public function upload(Request $request, $brand_id)
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

        $id = BrandImage::where('brand_id', $request->brand_id)->orderBy('id', 'DESC')->pluck('id')->first();

        $id = $id + 1;

        if ($request->embed) {
            $type = 2;
            $data = ['id' => $id, 'image' => $request->embed, 'type' => $type, 'folder_id' => '4', 'description' => time()];
        } else {
            $imageName = '/file/brand/upload/' . time() . '_' . pathinfo($request->file->getClientOriginalName(), PATHINFO_FILENAME) . '.' . $request->file->getClientOriginalExtension();
            if (in_array($request->file->getClientOriginalExtension(), $supported_image)) {
                $type = 1;
            } else {
                $type = 0;
            }
            Storage::disk('galeri_path')->put($imageName, file_get_contents($request->file));

            if ($type == 0) {
                $xsmallthumbnail = '/file/brand/upload/' . time() . '_' . pathinfo($request->file->getClientOriginalName(), PATHINFO_FILENAME)  . '_xsmall' . '.' . $request->file->getClientOriginalExtension();
                $smallthumbnail = '/file/brand/upload/' . time() . '_' . pathinfo($request->file->getClientOriginalName(), PATHINFO_FILENAME)  . '_small' . '.' . $request->file->getClientOriginalExtension();
                $mediumthumbnail = '/file/brand/upload/' . time() . '_' . pathinfo($request->file->getClientOriginalName(), PATHINFO_FILENAME)  . '_medium' . '.' . $request->file->getClientOriginalExtension();
                $largethumbnail = '/file/brand/upload/' . time() . '_' . pathinfo($request->file->getClientOriginalName(), PATHINFO_FILENAME)  . '_large' . '.' . $request->file->getClientOriginalExtension();
                $xlargethumbnail = '/file/brand/upload/' . time() . '_' . pathinfo($request->file->getClientOriginalName(), PATHINFO_FILENAME)  . '_xlarge' . '.' . $request->file->getClientOriginalExtension();

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

            $data = array_merge($request->only('image'), ['id' => $id, 'image' => 'storage' . $imageName, 'type' => $type, 'folder_id' => '4', 'description' => time(), '_id' => Str::uuid(), 'brand_id' => $brand_id]);
        }

        $brands = BrandImage::insert($data);

        if ($brands) {
            $status = true;
        } else {
            $status = false;
        }

        return response()->json([
            'status' => $status
        ]);
    }

    public function createThumbnail($path, $width, $height)
    {
        $img = ImageLib::make($path)->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save($path);
    }
}
