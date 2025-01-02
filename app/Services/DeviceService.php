<?php

namespace App\Services;

use App\Http\Requests\DeviceRequest;
use App\Models\Device;
use App\Models\User;
use App\Repositories\DeviceRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class DeviceService {
    private DeviceRepository $deviceRepository;
    public function __construct()
    {
        $this->deviceRepository = new DeviceRepository;
    }

    

    public function getDevicesOfUser(User $user, array $meta = [
        'page' => 1,
        'per_page' => 15
    ]) : LengthAwarePaginator{
        return $this->deviceRepository->getDevicesForUser($user, true, $meta['page'], $meta['per_page']);
    }
    public function createUserAndAttachToUser(DeviceRequest $payload, User $user): Device {
        $data = $payload->validated();
        $data['device_unique_id'] = $data['imei'];
        unset ($data['imei']);
        return $this->deviceRepository->createDeviceAndAttachToUser($data, $user);
    }
}