<?php

namespace App\Modules\Mcp_Users\Controllers;

use App\Bll\Utility;
use App\Modules\Mcp_Users\Models\Users;
use App\Http\Controllers\AdminBaseController;
use App\Modules\Mcp_Users\Resources\UsersResource;

class adminController extends AdminBaseController
{

    public function index()
    {
        $limit = request()->input('limit', 10);
        $users = Users::query();
        if (request()->has('term') && request()->term != '') {
            $users->where('first_name', 'like', '%' . request()->term . '%')
                ->orWhere('last_name', 'like', '%' . request()->term . '%')
                ->orWhere('email', 'like', '%' . request()->term . '%');
        }

        $responseData = Utility::paginateData(UsersResource::class, $users, $limit);
        return $this->sendArrayResponse($responseData, __('api.Users retrieved successfully.'));
    }
    // show user
    public function show($id)
    {
        $user = Users::where('id', $id)->first();
        if (!$user) {
            return $this->sendError(__('api.User not found.'));
        }
        return $this->sendResponse(new UsersResource($user), __('api.User retrieved successfully.'));
    }

    // delete user
    public function delete($id)
    {
        $user = Users::where('id', $id)->first();
        if (!$user) {
            return $this->sendError(__('api.User not found.'));
        }
        $user->delete();
        return $this->sendResponse([], __('api.User deleted successfully.'));
    }

}
