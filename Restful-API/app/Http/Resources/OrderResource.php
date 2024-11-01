<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            "id" => $this -> id,
            "user_id" => $this -> user_id,
            "menu_id" => $this -> menu_id,
            "quantity" => $this -> quantity,
            "total_price" => $this -> total_price,
            "payment_method" => $this -> payment_method
        ];
    }
}
