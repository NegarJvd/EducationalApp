<?php

namespace App\Http\Resources;

use App\Components\Response;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed file_path
 * @property mixed id
 * @property mixed file_name
 * @property mixed size
 */
class ContentUploadResource extends JsonResource
{
    use Response;
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'file_path' => asset($this->file_path),
            'file_name' => $this->file_name,
            'size' => $this->size
        ];
    }
}
