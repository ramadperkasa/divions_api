<?php

namespace App\Http\Controllers\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Referensi\StoreBrandBlockTemplate;
use App\Model\Referensi\BrandBlockTemplate;
use App\Model\Referensi\BrandBlockTemplateDetail;
use Illuminate\Support\Str;

class BrandBlockTemplateController extends Controller
{
    function __construct()
    {
        // $this->middleware('permission:read-setting-beranda', ['only' => ['index']]);
        // $this->middleware('permission:create-setting-beranda|update-setting-beranda', ['only' => ['updateOrCreate', 'hide', 'reorder']]);
        // $this->middleware('permission:delete-setting-beranda', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {

        $default = (object) ['sortBy' => 'id', 'sortDesc' => false];
        $fields = ['nama'];
        $data = BrandBlockTemplate::where('brand_id', $request->brand_id)->where(function ($query) use ($request, $fields) {
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
        $data = $data->with('blokTemplateDetail.blok')->paginate($request->itemsPerPage);

        return response()->json([
            'data' => $data
        ]);
    }

    public function updateOrCreate(StoreBrandBlockTemplate $request)
    {
        $del = BrandBlockTemplateDetail::where('brand_id', $request->brand_id)->where('block_template_id', $request->id)->delete();

        if ($request->id == null) {
            $id = BrandBlockTemplate::orderBy('id', 'DESC')->pluck('id')->first();

            $getId = $id + 1;
        } else {
            $getId = $request->id;
        }

        $isActive = BrandBlockTemplate::where('brand_id', $request->brand_id)->where('is_active', 1)->exists();
        $active = $isActive ? 0 : 1;
        $_id = $request->_id ? $request->_id : Str::uuid();

        if ($request->duplicate) {
            $merge = array_merge($request->all(), ['id' => $getId, '_id' => Str::uuid()]);

            $blokTemplate = BrandBlockTemplate::updateOrCreate(['id' => $getId], $merge);

            foreach ($request->blok_template_detail as $key => $value) {
                $mainMerge = array_merge($value, ['block_template_id' => $getId, 'id' => $key + 1]);

                $del = BrandBlockTemplateDetail::updateOrCreate(['id' => $value['id'], 'block_template_id' => $getId, 'block_id' => $request->block_id, 'brand_id' => $value['brand_id']], $mainMerge);
            }
        } else {
            $merge = array_merge($request->all(), ['id' => $getId, '_id' => $_id, 'brand_id' => $request->brand_id, 'is_active' => $request->is_active ? $request->is_active : $active]);

            $blokTemplate = BrandBlockTemplate::updateOrCreate(['id' => $getId], $merge);

            foreach ($request->header as $key => $value) {
                if ($value['block_id'] && $value['col']) {
                    $mainMerge = array_merge($value, ['block_template_id' => $getId, 'id' => $key + 1, 'posisi' =>  0, '_id' => $_id, 'brand_id' => $request->brand_id]);
                    $del = BrandBlockTemplateDetail::updateOrCreate(['id' => $value['id']], $mainMerge);
                }
            }
            foreach ($request->leftSidebar as $key => $value) {
                if ($value['block_id'] && $value['col']) {
                    $mainMerge = array_merge($value, ['block_template_id' => $getId, 'id' => $key + 1, 'posisi' =>  1, '_id' => $_id, 'brand_id' => $request->brand_id]);
                    $del = BrandBlockTemplateDetail::updateOrCreate(['id' => $value['id']], $mainMerge);
                }
            }
            foreach ($request->mainContent as $key => $value) {
                if ($value['block_id'] && $value['col']) {
                    $mainMerge = array_merge($value, ['block_template_id' => $getId, 'id' => $key + 1, 'posisi' =>  2, '_id' => $_id, 'brand_id' => $request->brand_id]);
                    $del = BrandBlockTemplateDetail::insert($mainMerge);
                }
            }
            foreach ($request->rightSidebar as $key => $value) {
                if ($value['block_id'] && $value['col']) {
                    $mainMerge = array_merge($value, ['block_template_id' => $getId, 'id' => $key + 1, 'posisi' =>  3, '_id' => $_id, 'brand_id' => $request->brand_id]);
                    $del = BrandBlockTemplateDetail::updateOrCreate(['id' => $value['id']], $mainMerge);
                }
            }
            foreach ($request->footer as $key => $value) {
                if ($value['block_id'] && $value['col']) {
                    $mainMerge = array_merge($value, ['block_template_id' => $getId, 'id' => $key + 1, 'posisi' =>  4, '_id' => $_id, 'brand_id' => $request->brand_id]);
                    $del = BrandBlockTemplateDetail::updateOrCreate(['id' => $value['id']], $mainMerge);
                }
            }
        }

        if ($blokTemplate) {
            $status = true;
            $response = $blokTemplate->wasRecentlyCreated;
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
        $blokTemplate = BrandBlockTemplate::where('brand_id', $request->brand_id)->where('id', $request->id)->delete();
        $blokTemplateDetail = BrandBlockTemplateDetail::where('brand_id', $request->brand_id)->where('block_template_id', $request->id)->delete();
        if ($blokTemplate) {
            $status = true;
        } else {
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
            $blokTemplate = BrandBlockTemplate::where('brand_id', $request->brand_id)->where('id', $value['id'])->delete();
            $blokTemplateDetail = BrandBlockTemplateDetail::where('brand_id', $request->brand_id)->where('block_template_id', $value['id'])->delete();

            if ($blokTemplate) {
                $success++;
                $status = true;
            } else {
                $fail++;
                $status = false;
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
        $hide = BrandBlockTemplate::where('brand_id', $request->brand_id)->where('id', $request->id)->pluck('ishide')->first();
        $value = BrandBlockTemplate::where('brand_id', $request->brand_id)->where('id', $request->id)->update(['ishide' => $hide == 1 ? 0 : 1]);

        if ($value) {
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
        $value = BrandBlockTemplate::where('brand_id', $request->brand_id)->where('id', $request->id)->update(['reorder' => $request->reorder]);

        if ($value) {
            $status = true;
        } else {
            $status = false;
        }


        return response()->json([
            'status' => $status
        ]);
    }
    public function setIsActive(Request $request)
    {
        $hide = BrandBlockTemplate::where('id', $request->id)->where('brand_id', $request->brand_id)->where('is_active', $request->is_active)->pluck('is_active')->first();
        $switch = BrandBlockTemplate::where('id', $request->id)->where('brand_id', $request->brand_id)->where('is_active', 1)->update(['is_active' => 0]);
        $tahunajaran = BrandBlockTemplate::where('id', $request->id)->where('brand_id', $request->brand_id)->update(['is_active' => 1]);

        if ($tahunajaran) {
            $status = true;
        } else {
            $status = false;
        }

        return response()->json([
            'status' => $status,
        ]);
    }
    public function ajax(Request $request)
    {
        $data = BrandBlockTemplate::select('id as value', 'nama as text')->where('brand_id', $request->brand_id)->get();

        return response()->json([
            'data' => $data
        ]);
    }
}
