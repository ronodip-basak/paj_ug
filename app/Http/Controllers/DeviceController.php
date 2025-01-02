<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeviceRequest;
use App\Http\Resources\DeviceResponse;
use App\Models\Device;
use App\Services\DeviceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeviceController extends Controller
{
    private DeviceService $deviceService;

    public function __construct()
    {
        $this->deviceService = new DeviceService;
    }

    public function view(Device $device){
        return new DeviceResponse($device);
    }

    public function index(Request $req){
        $req->validate([
            'page' => 'nullable|numeric|min:1',
            'per_page' => 'nullable|numeric|min:1'
        ]);
        return DeviceResponse::collection($this->deviceService->getDevicesOfUser(Auth::user(), [
            'page' => $req->page ?? 1,
            'per_page' => $req->per_page ?? 15
        ]));
    }

    public function store(DeviceRequest $req){
        return response(new DeviceResponse($this->deviceService->createDeviceAndAttachToUser($req, $req->user())), 201);
    }
}
