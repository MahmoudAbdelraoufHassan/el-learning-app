<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'username' => $this->username,
            'email' => $this->email,
            'courses' => $this->courses->map(function ($course) {
             return [
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
                'published_date' => $course->created_at,
             ];
        }),
        ];
        
    }
}
