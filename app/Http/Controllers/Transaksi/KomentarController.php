<?php

namespace App\Http\Controllers\Transaksi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Transaksi\Komentar;

class KomentarController extends Controller
{
    public function index(Request $request)
    {
        $data = Komentar::where(function ($query) use ($request) {
            $query->orwhere('id', 'LIKE', "%" . $request->search . "%")
                ->orwhere('komentar_nama', 'LIKE', "%" . $request->search . "%")
                ->orwhere('komentar_email', 'LIKE', "%" . $request->search . "%")
                ->orwhere('komentar_konten', 'LIKE', "%" . $request->search . "%")
                ->orwhere('status_publish', 'LIKE', "%" . $request->search . "%");
        })->paginate($request->size);


        return response()->json([
            'data' => $data
        ]);
    }

    public function updateOrCreate(StoreKomentar $request)
    {
        try {
            $Komentar = Komentar::updateOrCreate(['id' => $request->id], $request->all());

            if ($image) {
                $status = true;
                $response = $image->wasRecentlyCreated;
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

    public function hide(Request $request)
    {
       $komentar = Komentar::where('id',$request->id)->update(['status_publish' => $request->status_publish == 0 ? 1 : 0]);

        return response()->json([
            'status' => true
        ]);
    }

    public function destroy(Request $request)
    {
        try {
            $destroy =  Komentar::destroy($request->id);
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
