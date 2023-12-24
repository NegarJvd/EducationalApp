<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

/**
 * @property mixed id
 * @property mixed number
 * @property mixed cluster
 * @property mixed cover_image
 * @property mixed video_file
 *
 */
class StepResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'number' => $this->number,
            'description' => $this->cluster->description,
            'cover_path' => $this->cover_image,
            'video_path' => $this->video_file,
        ];
    }
}
