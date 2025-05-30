<?php

namespace App\Http\Controllers;

use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfigAppController extends Controller
{
    public function index()
    {
        $data = (object) [
            'appname' => env('APP_NAME'),
            'logo' => env('IMAGE_LOGO'),
            'host' => env('MAIL_HOST'),
            'port' => env('MAIL_PORT'),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'from_address' => env('MAIL_FROM_ADDRESS'),
            'from_name' => env('MAIL_FROM_NAME'),
            'email' => DB::table('config')->get()[0]->email
        ];
        return view('pages.config.index', ['type_menu' => 'config-app', 'data' => $data]);
    }

    public function store(Request $request)
    {
        $valid = [
            'appname' => 'required|max:255',
            'host' => 'required',
            'port' => 'required',
            'username' => 'required',
            'password' => 'required',
            'from_address' => 'required',
            'from_name' => 'required',
        ];

        $message = [
            'appname.max' => 'Maksimal judul 255 karakter',
            'appname.required' => 'Nama Aplikasi wajib diisi',
            'host.required' => 'SMTP Host wajib diisi',
            'port.required' => 'SMTP Port wajib diisi',
            'username.required' => 'SMTP Username wajib diisi',
            'password.required' => 'SMTP Password wajib diisi',
            'from_address.required' => 'SMTP Address wajib diisi',
            'from_name.required' => 'SMTP From Name wajib diisi',
        ];
        $validated = $this->validate($request, $valid, $message);
        $this->setEnv('APP_NAME', str_replace(' ', '_', $request->appname));

        $this->setEnv('MAIL_HOST', str_replace(' ', '_', $request->host));
        $this->setEnv('MAIL_PORT', str_replace(' ', '_', $request->port));
        $this->setEnv('MAIL_USERNAME', str_replace(' ', '_', $request->username));
        $this->setEnv('MAIL_PASSWORD', str_replace(' ', '_', $request->password));

        $this->setEnv('MAIL_FROM_ADDRESS', str_replace(' ', '_', $request->from_address));
        $this->setEnv('MAIL_FROM_NAME', str_replace(' ', '_', $request->from_name));
        if ($request->file)
        {
            File::delete('/public/img/' . env('IMAGE_LOGO'));

            $file = $request->file;
            $file_extension = $file->getClientOriginalExtension();

            $filename = uniqid() . '.' . $file_extension;
            $file->move('img', $filename);
            $this->setEnv('IMAGE_LOGO', $filename);
        }

        DB::table('config')->updateOrInsert(['id' => '1'], ['email' => $request->email]);

        $notification = [
            'message' => 'Data Berhasil Diubah',
            'alert-type' => 'success'
        ];

        return redirect()->route('config-app')->with($notification);
    }

    function setEnv($name, $value)
    {
        $path = base_path('.env');
        if (file_exists($path))
        {
            file_put_contents($path, str_replace(
                $name . '=' . env($name),
                $name . '=' . $value,
                file_get_contents($path)
            ));
        }
    }
}
