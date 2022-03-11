<?php

namespace App\Http\Resources;

use App\Http\Resources\UserResource;
use App\Http\Resources\UserAccountResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionHistoryResource extends JsonResource
{
    public static $wrap = 'histories';

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
            'receiver_id' => $this->receiver->id,
            'transfer_amount' => $this->transfer_amount,
            'received_amount' => $this->received_amount,
            'created_at' => $this->created_at
        ];
    }
}
