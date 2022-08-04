<?php

namespace App\Http\Controllers\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Referensi\StoreBlokTemplate;
use App\Model\Referensi\BlokTemplate;
use App\Model\Referensi\BlokTemplateDetail;
use App\Model\Referensi\BlokTemplateDetailContent;

class BlokTemplateController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:read-setting-beranda', ['only' => ['index']]);
        $this->middleware('permission:create-setting-beranda|update-setting-beranda', ['only' => ['updateOrCreate', 'hide', 'reorder']]);
        $this->middleware('permission:delete-setting-beranda', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {

        $default = (object) ['sortBy' => 'id', 'sortDesc' => false];
        $fields = ['nama'];
        $data = BlokTemplate::where(function ($query) use ($request, $fields) {
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
        $data = $data->with('blokTemplateDetail.blokTemplateDetailContent.blok')->paginate($request->itemsPerPage);

        return response()->json([
            'data' => $data
        ]);
    }

    public function updateOrCreate(StoreBlokTemplate $request)
    {
        BlokTemplateDetail::where('block_template_id', $request->id)->delete();
        BlokTemplateDetailContent::where('block_template_id', $request->id)->delete();

        if ($request->id == null) {
            $id = BlokTemplate::orderBy('id', 'DESC')->pluck('id')->first();

            $getId = $id + 1;
        } else {
            $getId = $request->id;
        }


        if ($request->duplicate) {
            $merge = array_merge($request->all(), ['id' => $getId]);
            $blokTemplate = BlokTemplate::updateOrCreate(['id' => $getId], $merge);
            foreach ($request->blok_template_detail as $key => $value) {
                $mainMerge = array_merge($value, ['block_template_id' => $getId, 'id' => $key + 1]);
                BlokTemplateDetail::updateOrCreate(['id' => $value['id'], 'block_template_id' => $getId], $mainMerge);
                foreach ($value['blok_template_detail_content'] as $key => $val) {
                    BlokTemplateDetailContent::insert(['id' => $key + 1, 'block_template_id' => $getId, 'col' => $val['col'], 'ishide' => $val['ishide'], 'block_id' => $val['block_id'], 'block_template_detail_id' => $val['block_template_detail_id'], 'reorder' => $val['reorder']]);
                }
            }
        } else {
            $merge = array_merge($request->all(), ['id' => $getId]);

            $blokTemplate = BlokTemplate::updateOrCreate(['id' => $getId], $merge);

            // foreach ($request->header as $key => $value) {
            //     if ($value['komponen'][0]['block_id']) {
            //         BlokTemplateDetail::insert(['block_template_id' => $getId, 'id' => $key + 1, 'posisi' =>  0, 'isContainer' => $value['isContainer'], 'reorder' => $value['reorder'], 'ishide' => $value['ishide'], 'col' => $value['col']]);
            //         foreach ($value['komponen'] as $index => $isi) {
            //             if ($isi['block_id']) {
            //                 $komponen = array_merge($isi, ['block_template_id' => $getId, 'id' => $index + 1, 'block_template_detail_id' => $key + 1]);
            //                 BlokTemplateDetailContent::insert($komponen);
            //             }
            //         }
            //     }
            // }
            // foreach ($request->leftSidebar as $key => $value) {
            //     if ($value['komponen'][0]['block_id']) {
            //         BlokTemplateDetail::insert(['block_template_id' => $getId, 'id' => $key + 1, 'posisi' =>  1, 'isContainer' => $value['isContainer'], 'reorder' => $value['reorder'], 'ishide' => $value['ishide'], 'col' => $value['col']]);
            //         foreach ($value['komponen'] as $index => $isi) {
            //             if ($isi['block_id']) {
            //                 $komponen = array_merge($isi, ['block_template_id' => $getId, 'id' => $index + 1, 'block_template_detail_id' => $key + 1]);
            //                 BlokTemplateDetailContent::insert($komponen);
            //             }
            //         }
            //     }
            // }

            foreach ($request->mainContent as $key => $value) {
                if ($value['komponen'][0]['block_id']) {
                    BlokTemplateDetail::insert(['block_template_id' => $getId, 'id' => $key + 1, 'posisi' =>  2, 'isContainer' => $value['isContainer'], 'reorder' => $value['reorder'], 'ishide' => $value['ishide'], 'col' => $value['col']]);
                    foreach ($value['komponen'] as $index => $isi) {
                        if ($isi['block_id']) {
                            $komponen = array_merge($isi, ['block_template_id' => $getId, 'id' => $index + 1, 'block_template_detail_id' => $key + 1]);
                            BlokTemplateDetailContent::insert($komponen);
                        }
                    }
                }
            }

            // foreach ($request->rightSidebar as $key => $value) {
            //     if ($value['komponen'][0]['block_id']) {
            //         BlokTemplateDetail::insert(['block_template_id' => $getId, 'id' => $key + 1, 'posisi' =>  3, 'isContainer' => $value['isContainer'], 'reorder' => $value['reorder'], 'ishide' => $value['ishide'], 'col' => $value['col']]);
            //         foreach ($value['komponen'] as $index => $isi) {
            //             if ($isi['block_id']) {
            //                 $komponen = array_merge($isi, ['block_template_id' => $getId, 'id' => $index + 1, 'block_template_detail_id' => $key + 1]);
            //                 BlokTemplateDetailContent::insert($komponen);
            //             }
            //         }
            //     }
            // }
            // foreach ($request->footer as $key => $value) {
            //     if ($value['komponen'][0]['block_id']) {
            //         BlokTemplateDetail::insert(['block_template_id' => $getId, 'id' => $key + 1, 'posisi' =>  4, 'isContainer' => $value['isContainer'], 'reorder' => $value['reorder'], 'ishide' => $value['ishide'], 'col' => $value['col']]);
            //         foreach ($value['komponen'] as $index => $isi) {
            //             if ($isi['block_id']) {
            //                 $komponen = array_merge($isi, ['block_template_id' => $getId, 'id' => $index + 1, 'block_template_detail_id' => $key + 1]);
            //                 BlokTemplateDetailContent::insert($komponen);
            //             }
            //         }
            //     }
            // }
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
        $blokTemplate = BlokTemplate::destroy($request->id);
        $blokTemplateDetail = BlokTemplateDetail::where('block_template_id', $request->id)->delete();
        $blokTemplateDetail = BlokTemplateDetailContent::where('block_template_id', $request->id)->delete();
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
            $blokTemplate = BlokTemplate::destroy($value['id']);
            $blokTemplateDetail = BlokTemplateDetail::where('block_template_id', $value['id'])->delete();
            $blokTemplateDetailContent = BlokTemplateDetailContent::where('block_template_id', $value['id'])->delete();

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
        $hide = BlokTemplate::where('id', $request->id)->pluck('ishide')->first();
        $value = BlokTemplate::where('id', $request->id)->update(['ishide' => $hide == 1 ? 0 : 1]);

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
        $value = BlokTemplate::where('id', $request->id)->update(['reorder' => $request->reorder]);

        if ($value) {
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
        $data = BlokTemplate::select('id as value', 'nama as text')->get();

        return response()->json([
            'data' => $data
        ]);
    }
}
