<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            // 'creator' => new UserResource($this->user),
            'creator' => [
            'id' => $this->user->id,
            'username' => $this->user->username,
            'email' => $this->user->email  
        ],
        ];
    }
}
