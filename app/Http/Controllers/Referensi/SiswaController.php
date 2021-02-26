<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Referensi\Siswa;
// use App\Http\Requests\StoreSiswa;

class SiswaController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:siswa', ['only' => ['index']]);
        $this->middleware('permission:create-siswa|update-siswa', ['only' => ['updateOrCreate', 'hide']]);
        $this->middleware('permission:delete-siswa', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        try {
            $data = Siswa::where(function ($query) use ($request) {
                $query->where('id', "LIKE", "%" . $request->search . "%")
                    ->orwhere('nama', "LIKE", "%" . $request->search . "%")
                    ->orwhere('tahun_ajaran', "LIKE", "%" . $request->search . "%")
                    ->orwhere('kelas', "LIKE", "%" . $request->search . "%");
            })
                ->paginate($request->size);
        } catch (\Exception $e) {
            $data = [];
        }
        return response()->json([
            'data' => $data
        ]);
    }

    public function updateOrCreate(Request $request)
    {   
            // $request->validated();

            $Siswa = Siswa::updateOrCreate($request->only('id'), $request->all());

            if ($Siswa) {
                $status = true;
                $response = $Siswa->wasRecentlyCreated;
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
        try {
            $Siswa = Siswa::destroy($request->id);

            if ($Siswa) {
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

    public function hide(Request $request)
    {
        try {
            $anggotaJenis = Siswa::findOrFail($request->jenis);

            $anggotaJenis->update([
                'ishide' => $anggotaJenis->ishide == 1 ? 0 : 1
            ]);

            if ($anggotaJenis) {
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
