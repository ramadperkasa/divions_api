<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Admin;
use Auth;
use Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function gantiPassword(Request $request)
    {
        $data = hash::check($request->oldPassword, Auth::user('admin')->password);

        if ($data) {
            if ($request->password == $request->cPassword) {
                $passwordCheck = Admin::where('id', Auth::user('admin')->id)->update([
                    'password' => Hash::make($request->password)
                ]);
                if ($passwordCheck) {
                    $data = "Password Berhasil dirubah";
                } else {
                    $data = "Terjadi Kesalahan";
                }
            } else {
                $data = "password Baru tidak cocok";
            }
        } else {
            $data = "password lama tidak cocok";
        }
        return response()->json([
            'status' => $data,
        ]);
    }

    public function kelola(Request $request)
    {
        if (strlen($request->foto) > 300) {
            $file = $request->foto;
            list($type, $file) = explode(';', $file);
            list(, $data) = explode(':', $type);
            list($data, $ext) = explode('/', $data);
            list(, $file) = explode(',', $file);

            $file = base64_decode($file);
            $filename = '/file/' . 'admin' . '/' . str_slug(Auth::user('admin')->id) . '_' . time() . '.' . $ext;

            Storage::disk('galeri_path')->put($filename, $file);

            $merge = array_merge($request->except('foto'), ['foto' => 'storage' . $filename]);
        } else {
            $merge = $request->all();
        }



        $data = Admin::where('id', Auth::user('admin')->id)->update($merge);

        return response()->json([
            'status' => $data ? 'Berhasil diperbaharui' : 'Gagal',
        ]);
    }
}
