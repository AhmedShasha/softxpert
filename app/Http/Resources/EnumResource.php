<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EnumResource extends JsonResource
{
    public function __construct($resource, protected string $enumClass)
    {
        parent::__construct($resource);
    }

    public function toArray($request)
    {
        return [
            'value' => $this->resource,
            'name'  => $this->enumClass::getStringValue($this->resource),
        ];
    }
}
