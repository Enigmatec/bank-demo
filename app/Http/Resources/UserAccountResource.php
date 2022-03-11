<?php

namespace App\Http\Resources;

use App\Http\Resources\AccountTypeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'account_no' => $this->account_no,
            'account_type' => $this->accountTypes->name,
            'balance' => $this->balance
        ];
    }
}
