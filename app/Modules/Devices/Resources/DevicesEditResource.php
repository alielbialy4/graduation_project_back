<?php

namespace App\Modules\Devices\Resources;

use Illuminate\Http\Request;
use App\Modules\Rooms\Resources\RoomsResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DevicesEditResource extends JsonResource
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
            'room_id'           => $this->room_id,
            // 'room'              => new RoomsResource($this->rooms),
            'created_at'        => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at'        => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
