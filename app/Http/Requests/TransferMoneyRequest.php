<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferMoneyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'account_type' => ['required', 'string', 'exists:account_types,name'],
            'destination_act_no' => ['required','digits:10', 'exists:user_accounts,account_no'],
            'amount' => ['required', 'numeric']
        ];
    }

    public function messages()
    {
        return [
            'destination_act_no.exists' => 'The Destination Account number does not exists',
        ];
    }
}
