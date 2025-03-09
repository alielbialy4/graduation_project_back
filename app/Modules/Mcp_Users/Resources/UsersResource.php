<?php

namespace App\Modules\Mcp_Users\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UsersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'email'       => $this->email,
            'guard'       => $this->guard,
            'username'    => $this->username,
            'image'       => $this->image ? asset($this->image) : null,
            'token'       => $this->token,
            'is_designer' => $this->is_designer,
            'bio'         => $this->bio,
            'balance'     => $this->balance(),
            'is_active'   => $this->is_active,
            'verified_at' => $this->verified_at,
            'created_at'  => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at'  => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
