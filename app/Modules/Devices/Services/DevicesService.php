<?php

namespace App\Modules\Devices\Services;

use App\Services\Store;
use App\Modules\Devices\Models\Devices;
use App\Modules\Devices\Resources\DevicesResource;
use App\Modules\Devices\Resources\DevicesEditResource;

class DevicesService extends Store
{
    protected $error;
    protected $success;
    protected $saved;
    protected $updated;

    public function __construct()
    {
        $this->resource = DevicesResource::class;
        //set messages
        $this->error   = 'there is no Devices';
        $this->success = 'All Devices retrieved successfully';
        $this->saved   = 'Device created successfully';
        $this->updated = 'Device updated successfully';

        parent::__construct(new Devices());
    }

    public function GetAll()
    {
        return $this->Get(
            ["devices.id", "name", "room_id", "devices.created_at", "devices.updated_at"],
            []
        );
    }

    public function storeData()
    {
        $this->store(["name" , 'room_id'],
            [],
            "",
            ''
        );

        return $this->saved;
    }

    public function showData(int $id)
    {

        if (isset($id) && is_int($id)) {
            $data = Devices::where('id', $id)->first();
            if ($data != null)
                $this->data = DevicesEditResource::make($data);
        }
        return __('room retrieved successfully');
    }

    public function edit(int $id)
    {

        if (isset($id) && is_int($id)) {
            $data = Devices::where('id', $id)->first();
            if ($data != null)
                $this->data = DevicesEditResource::make($data);
        }
        return __('room retrieved successfully');
    }

    public function updateData(int $id)
    {

        $this->update(
            ['name' , 'room_id'],
            [],
            "",
            $id,
            ""
        );

        return $this->updated;
    }

    public function deleteData(int $id)
    {
        parent::delete($id);
    }
}
