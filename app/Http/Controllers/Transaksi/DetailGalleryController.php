<?php

namespace App\Http\Controllers\Transaksi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DetailGalleryController extends Controller
{
    public function index(Request $request)
    {
        try {
            $data = DetailGallery::where(function ($query) use ($request) {
                $query->orwhere('id', 'LIKE', "%" . $request->search . "%")
                    ->orwhere('gallery_id', 'LIKE', "%" . $request->search . "%")
                    ->orwhere('image_id', 'LIKE', "%" . $request->search . "%")
                    ->orWhereHas('image', function ($query) use ($request) {
                        $query->orwhere('id', 'LIKE', "%" . $request->search . "%")
                            ->orwhere('description', 'LIKE', "%" . $request->search . "%")
                            ->orwhere('description_en', 'LIKE', "%" . $request->search . "%");
                    });
            })->with('images')->paginate($request->size);
        } catch (\Exception $e) {
            $data = [];
        }

        return response()->json([
            'data' => $data
        ]);
    }

    public function updateOrCreate(StoreDetailGallery $request)
    {
        try {
            $detail = DetailGallery::updateOrCreate(['id' => $request->id], $request->all());

            if ($detail) {
                $status = true;
                $response = $detail->wasRecentlyCreated;
            } else {
                $status = false;
                $response = null;
            }
        } catch (\Exception $e) {
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
        try {
            $destroy =  DetailGallery::destroy($request->id);
            if ($destroy) {
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
