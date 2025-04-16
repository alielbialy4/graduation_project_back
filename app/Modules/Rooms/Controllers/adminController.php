<?php

namespace App\Modules\Rooms\Controllers;

use App\Modules\Rooms\Models\Rooms;
use App\Http\Controllers\Controller;
use App\Modules\Rooms\Requests\StoreRequest;
use App\Modules\Rooms\Services\RoomsService;
use App\Http\Controllers\AdminBaseController;
use App\Modules\Rooms\Requests\UpdateRequest;
use App\Modules\Rooms\Resources\RoomsResource;

class AdminController extends Controller
{
    // public function __construct()
    // {
    //     $this->service       = new RoomsService();
    //     $this->StoreRequest  = new StoreRequest();
    //     $this->UpdateRequest = new UpdateRequest();
    // }

    // index
    public function index()
    {
        $rooms = Rooms::all();
        return $this->sendResponse(RoomsResource::collection($rooms), 'Rooms retrieved successfully');
    }

}
