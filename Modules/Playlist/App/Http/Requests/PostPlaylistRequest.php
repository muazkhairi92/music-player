<?php

namespace Modules\Playlist\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostPlaylistRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name'=> 'required|string|max:50',
            'list' => 'required|array',
            'list.*.song_id' => 'required|exists:songs,id',
            'list.*.order_number' => 'required|integer',        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
