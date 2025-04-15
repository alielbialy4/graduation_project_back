<?php

namespace App\Modules\Rooms\Services;

use App\Services\Store;
use App\Modules\Rooms\Models\Rooms;
use App\Modules\Rooms\Resources\RoomsResource;
use App\Modules\Rooms\Resources\RoomsEditResource;

class RoomsService extends Store
{
    protected $error;
    protected $success;
    protected $saved;
    protected $updated;

    public function __construct()
    {
        $this->resource = RoomsResource::class;
        //set messages
        $this->error   = 'there is no Rooms';
        $this->success = 'All Rooms retrieved successfully';
        $this->saved   = 'Room created successfully';
        $this->updated = 'Room updated successfully';

        parent::__construct(new Rooms());
    }

    public function GetAll()
    {
        return $this->Get(
            ["rooms.id", "name" , "rooms.created_at", "rooms.updated_at"],
            []
        );
    }

    public function storeData()
    {
        $this->store(["name"],
            [],
            "",
            ''
        );

        return $this->saved;
    }

    public function showData(int $id)
    {

        if (isset($id) && is_int($id)) {
            $data = Rooms::where('id', $id)->first();
            if ($data != null)
                $this->data = RoomsEditResource::make($data);
        }
        return __('room retrieved successfully');
    }

    public function edit(int $id)
    {

        if (isset($id) && is_int($id)) {
            $data = Rooms::where('id', $id)->first();
            if ($data != null)
                $this->data = RoomsEditResource::make($data);
        }
        return __('room retrieved successfully');
    }

    public function updateData(int $id)
    {

        $this->update(
            ['name'],
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
