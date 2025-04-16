<?php

namespace App\Modules\Rooms\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Modules\Devices\Resources\DevicesResource;

class SimpleRoomsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'created_at'        => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at'        => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
