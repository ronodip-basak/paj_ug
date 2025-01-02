<?php

namespace App\Repositories;

use App\Models\Device;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class DeviceRepository
{
    
    public function getDevicesForUser(User $user, bool $paginate = true, int $page = 1, int $per_page = 15) : LengthAwarePaginator|Collection {
        $query = $user->devices();
        return $paginate ?
            $query->paginate(perPage: $per_page, page: $page) :
            $query->get();
    }
    
    public function createDevice(array $data): Device
    {
        return Device::create($data);
    }

    public function attachToUser(User $user, Device $device): Device
    {
        $device->users()->attach($user->id);
        return $device;
    }

    public function createDeviceAndAttachToUser(array $data, User $user): Device
    {
        try {
            DB::beginTransaction();
            $device = $this->createDevice($data);
            $device = $this->attachToUser($user, $device);
            DB::commit();

            return $device;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
