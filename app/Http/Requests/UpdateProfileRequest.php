<?php

namespace App\Http\Requests;

use App\Rules\CurrentPassword;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Stevebauman\Purify\Facades\Purify;

class UpdateProfileRequest extends FormRequest
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
        $profile = User::find(auth()->id());
        if($this->has('btnprofile')){
            $this->redirect = route('profile.manage');
            return [
                'name' => 'required',
                'email' => 'email|nullable|unique:users,email,'.$profile->id,
                'username' => 'required|unique:users,username,'.$profile->id,
                'address' => 'nullable',
                'description' => 'nullable',
            ];
        }elseif ($this->has('btnpass')){
            $this->redirect = route('profile.manage',['#ch_pwd']);
            return $passRules = [
                'old_password'=>['required','min:6', new CurrentPassword($profile->password)],
                'new_password'=>'required|min:6',
            ];
        }
        return [];
    }

     /**
     * Extend the default getValidatorInstance method
     * so description field can be escaped
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function getValidatorInstance()
    {
        $request = $this->toArray();
        if (array_key_exists('description', $request)) {
            $description = $request["description"];
            $escaped_description = Purify::clean($description);
            $this->merge(array('description' => $escaped_description));
        }
        return parent::getValidatorInstance();
    }
}
