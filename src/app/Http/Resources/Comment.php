<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Comment extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = $this->user;

        return [
            'id' => $this->id,
            'body' => $this->body,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
        return parent::toArray($request);
    }
}
