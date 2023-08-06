<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EncryptController extends Controller
{
    public function index()
    {
        return view('encrypt.index');
    }

    public function encrypt(Request $request)
    {
        $encrypt = encrypt($request->text);

        return redirect()->route('encrypt.index')->with('encrypt', $encrypt);
    }

    public function decrypt(Request $request)
    {
        $decrypt = decrypt($request->text);

        return redirect()->route('encrypt.index')->with('decrypt', $decrypt);
    }
}
