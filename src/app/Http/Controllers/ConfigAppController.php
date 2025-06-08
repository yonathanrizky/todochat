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
            'openai_api_key' => env('OPENAI_API_KEY'),
            'open_ai_model' => env('OPENAI_MODEL'),
        ];
        return view('pages.config.index', ['type_menu' => 'config-app', 'data' => $data]);
    }

    public function store(Request $request)
    {
        $valid = [
            'appname' => 'required|max:255',
            'openai_api_key' => 'required',
            'open_ai_model' => 'required',
        ];

        $message = [
            'appname.max' => 'Maksimal judul 255 karakter',
            'appname.required' => 'Nama Aplikasi wajib diisi',
            'openai_api_key.required' => 'Open AI Key wajib diisi',
            'open_ai_model.required' => 'Open AI Model wajib diisi',
        ];
        $validated = $this->validate($request, $valid, $message);
        $this->setEnv('APP_NAME', str_replace(' ', '_', $request->appname));

        $this->setEnv('OPENAI_API_KEY', $request->openai_api_key);
        $this->setEnv('OPENAI_MODEL', $request->open_ai_model);

        if ($request->file)
        {
            File::delete('/public/img/' . env('IMAGE_LOGO'));

            $file = $request->file;
            $file_extension = $file->getClientOriginalExtension();

            $filename = uniqid() . '.' . $file_extension;
            $file->move('img', $filename);
            $this->setEnv('IMAGE_LOGO', $filename);
        }

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
