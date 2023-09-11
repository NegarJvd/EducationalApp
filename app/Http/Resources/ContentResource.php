<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @property mixed id
 *@property mixed name
 * @property mixed cover_image
 *
 */
class ContentResource extends JsonResource
{
    public function toArray(Request $request)
    {
        $user = Auth::user();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'cover_path' => $this->cover_image,
            'is_active' => in_array($this->id, $user->contents()->pluck('id')->toArray()) ? 1 : 0
        ];
    }
}
