<?php

namespace App\Http\Controllers\Transaksi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Transaksi\Booking;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Transaksi\StoreBooking;
use Image;

class BookingController extends Controller
{
   
    function __construct()
    {
        $this->middleware('permission:booking', ['only' => ['index']]);
        $this->middleware('permission:create-booking|update-booking', ['only' => ['updateOrCreate', 'hide']]);
        $this->middleware('permission:delete-booking', ['only' => ['destroy']]);
        $this->middleware('permission:download-logo-booking', ['only' => ['download']]);
    }

    public function index(Request $request)
    {
            $data = Booking::where(function ($query) use ($request) {
                $query->where('id', 'LIKE', "%" . $request->search . "%")
                    ->orWhere('agenda_id', 'LIKE', "%" . $request->search . "%")
                    ->orWhere('jenis_kegiatan_id', 'LIKE', "%" . $request->search . "%")
                    ->orWhere('ruangan_id', 'LIKE', "%" . $request->search . "%")
                    ->orWhere('warna', 'LIKE', "%" . $request->search . "%")
                    ->orWhere('tgl', 'LIKE', "%" . $request->search . "%")
                    ->orWhere('jam_mulai', 'LIKE', "%" . $request->search . "%")
                    ->orWhere('jam_selesai', 'LIKE', "%" . $request->search . "%")
                    ->orWhere('no_nik_penanggung_jawab', 'LIKE', "%" . $request->search . "%")
                    ->orWhere('nama_penanggung_jawab', 'LIKE', "%" . $request->search . "%")
                    ->orWhere('email_penanggung_jawab', 'LIKE', "%" . $request->search . "%")
                    ->orWhere('alamat_penangung_jawab', 'LIKE', "%" . $request->search . "%")
                    ->orWhere('nama_organisasi', 'LIKE', "%" . $request->search . "%")
                    ->orWhere('judul_acara', 'LIKE', "%" . $request->search . "%")
                    ->orWhere('judul_acara_en', 'LIKE', "%" . $request->search . "%")
                    ->orWhere('foto', 'LIKE', "%" . $request->search . "%")
                    ->orWhere('nama_penceramah', 'LIKE', "%" . $request->search . "%")
                    ->orWhere('imam_id', 'LIKE', "%" . $request->search . "%")
                    ->orWhere('jumlah_jamaah', 'LIKE', "%" . $request->search . "%")
                    ->orWhere('infaq', 'LIKE', "%" . $request->search . "%")
                    ->orWhere('proposal_pengajuan', 'LIKE', "%" . $request->search . "%");
            })->with('jenisKegiatan','ruangan')->paginate($request->size);
        
        return response()->json([
            'data' => $data
        ]);
    }

    public function updateOrCreate(StoreBooking $request)
    {
        $request->validated();

        $rekeningBank = Booking::updateOrCreate($request->only('id'), $request->all());

        if ($rekeningBank) {
            $status = true;
            $response = $rekeningBank->wasRecentlyCreated;
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
        Booking::destroy($request->id);

        return response()->json([
            'status' => true
        ]);
    }

    public function hide(Request $request)
    {
        $Booking = Booking::findOrFail($request->id);

        $Booking->update([
            'ishide' => $Booking->ishide == 1 ? 0 : 1
        ]);

        return response()->json([
            'status' => true
        ]);
    }

}
