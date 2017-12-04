<?php

namespace App\Http\Requests;

use App\Reply;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreReply extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Gate::denies('store', new Reply)) {
            return abort(403, 'Wait a minute before posting another reply');
        } else {
            return true;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'body' => 'required|spamfree',
        ];
    }
}
