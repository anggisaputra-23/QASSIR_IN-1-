<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRCodeController extends Controller
{
    public function index()
    {
        // Gunakan IP Wi-Fi yang benar (cek ipconfig)
        $ip = '10.10.12.57'; // IP laptop kamu
        $port = '8000';      // Port Laravel default

        // URL Self Order
        $url = "http://$ip:$port/selforder";

        return view('admin.qrcode', compact('url'));
    }
}
