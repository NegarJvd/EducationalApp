<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

/**
 * @property mixed id
 * @property mixed number
 * @property mixed description
 * @property mixed cover
 * @property mixed video
 *
 */
class StepResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'number' => $this->number,
            'description' => $this->description,
            'cover_path' => $this->cover->file_path,
            'video_path' => $this->video->file_path,
        ];
    }
}
