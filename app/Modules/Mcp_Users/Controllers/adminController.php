<?php

namespace App\Modules\Mcp_Users\Controllers;

use App\Bll\Utility;
use Illuminate\Support\Facades\DB;
use App\Modules\Mcp_Users\Models\Users;
use App\Http\Controllers\AdminBaseController;
use App\Modules\Mcp_Users\Models\WithdrawRequest;
use App\Modules\Mcp_Users\Resources\UsersResource;
use App\Modules\Transactions\Models\TransactionsGlobal;
use App\Modules\Mcp_Users\Requests\WithdrawAcceptRequest;
use App\Modules\Mcp_Users\Resources\WithdrawRequestResource;

class adminController extends AdminBaseController
{

    public function index()
    {
        if(!Utility::checkPermission('show_users')){
            return $this->sendError(__('api.You do not have permission to view users.'));
        }
        $limit = request()->input('limit', 10);
        $users = Users::query();
        if (request()->has('term') && request()->term != '') {
            $users->where('name', 'like', '%' . request()->term . '%')
                ->orWhere('email', 'like', '%' . request()->term . '%')
                ->orWhere('username', 'like', '%' . request()->term . '%');
        }

        // is_designer filter
        if (request()->has('is_designer') && request()->is_designer != '') {
            $users->where('is_designer', 1);
        }else{
            $users->where('is_designer', 0);
        }

        // is_active filter
        if (request()->has('is_active') && request()->is_active != '') {
            $users->where('is_active', request()->is_active);
        }

        $responseData = Utility::paginateData(UsersResource::class, $users, $limit);
        return $this->sendArrayResponse($responseData, __('api.Users retrieved successfully.'));
    }
    // show user
    public function show($id)
    {
        if(!Utility::checkPermission('show_users')){
            return $this->sendError(__('api.You do not have permission to view users.'));
        }
        $user = Users::where('id', $id)->first();
        if (!$user) {
            return $this->sendError(__('api.User not found.'));
        }
        return $this->sendResponse(new UsersResource($user), __('api.User retrieved successfully.'));
    }

    // block toggle
    public function blockToggle($id)
    {
        if(!Utility::checkPermission('update_user')){
            return $this->sendError(__('api.You do not have permission to update user.'));
        }
        $user = Users::where('id', $id)->first();
        if (!$user) {
            return $this->sendError(__('api.User not found.'));
        }
        $user->update(['is_active' => !$user->is_active]);
        return $this->sendResponse(new UsersResource($user), __('api.User updated successfully.'));
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

    // withdraw Requests
    public function withdrawRequests()
    {
        if(!Utility::checkPermission('show_withdraws')){
            return $this->sendError(__('api.You do not have permission to view withdraw requests.'));
        }
        $limit = request()->input('limit', 10);
        $status = ['pending', 'completed', 'rejected'];
        $withdrawRequests = WithdrawRequest::Query();
        if (request()->has('status') && in_array(request()->status, $status)) {
            $withdrawRequests->where('status', request()->status);
        }
        // search by term in user name
        if (request()->has('term') && request()->term != '') {
            $withdrawRequests->whereHas('user', function ($query) {
                $query->where('name', 'like', '%' . request()->term . '%')
                    ->orWhere('email', 'like', '%' . request()->term . '%')
                    ->orWhere('username', 'like', '%' . request()->term . '%');
            });
        }
        $responseData = Utility::paginateData(WithdrawRequestResource::class, $withdrawRequests, $limit);
        return $this->sendArrayResponse($responseData, __('api.Withdraw Requests retrieved successfully.'));
    }

    // show withdraw request
    public function withdrawRequestShow($id)
    {
        if(!Utility::checkPermission('show_withdraws')){
            return $this->sendError(__('api.You do not have permission to view withdraw requests.'));
        }
        $withdrawRequest = WithdrawRequest::where('id', $id)->first();
        if (!$withdrawRequest) {
            return $this->sendError(__('api.Withdraw Request not found.'));
        }
        return $this->sendResponse(new WithdrawRequestResource($withdrawRequest), __('api.Withdraw Request retrieved successfully.'));
    }

    // reject withdraw request
    public function rejectWithdrawRequest($id)
    {
        if(!Utility::checkPermission('update_withdraw')){
            return $this->sendError(__('api.You do not have permission to update withdraw requests.'));
        }
        $withdrawRequest = WithdrawRequest::where('id', $id)->first();
        if (!$withdrawRequest) {
            return $this->sendError(__('api.Withdraw Request not found.'));
        }
        $withdrawRequest->update(['status' => 'rejected']);
        return $this->sendResponse(new WithdrawRequestResource($withdrawRequest), __('api.Withdraw Request updated successfully.'));
    }
    // approve withdraw request
    public function approveWithdrawRequest(WithdrawAcceptRequest $request)
    {
        if(!Utility::checkPermission('update_withdraw')){
            return $this->sendError(__('api.You do not have permission to update withdraw requests.'));
        }
        DB::beginTransaction();
        try {
            $withdrawRequest = WithdrawRequest::where('id', $request->withdarw_request_id)->first();
            if (!$withdrawRequest) {
                return $this->sendError(__('api.Withdraw Request not found.'));
            }
            $user = $withdrawRequest->user;
            if (!$user) {
                return $this->sendError(__('api.User not found.'));
            }
            if ($user->balance() < $request->amount) {
                return $this->sendError(__('api.User balance is not enough.'));
            }
            $withdrawRequest->update([
                'status'   => 'completed',
            ]);
            TransactionsGlobal::create([
                'user_id' => $user->id,
                'amount'  => $request->amount,
                'type'    => 'withdraw',
                'status'  => 'completed',
            ]);
            $withdrawRequest->update(['status' => 'completed']);
            DB::commit();
            return $this->sendResponse(new WithdrawRequestResource($withdrawRequest), __('api.Withdraw Request updated successfully.'));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError(__('api.Something went wrong.'));
        }
    }
}
