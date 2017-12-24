<?php

namespace App\Http\Requests;

use App\Inspections\Spam;
use Illuminate\Foundation\Http\FormRequest;

class StoreThread extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @param Spam $spam
     * @return bool
     */
    public function authorize(Spam $spam)
    {
        $spam->check(request()->body);

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
            'title'      => 'required',
            'body'       => 'required',
            'channel_id' => 'required|exists:channels,id',
        ];
    }
}
