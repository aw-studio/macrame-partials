<?php

namespace Admin\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PartialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request                                        $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'template'   => $this->template,
            'attributes' => $this->attributes->toArray(),
        ];
    }
}
