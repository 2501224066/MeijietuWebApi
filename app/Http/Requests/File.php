<?php


namespace App\Http\Requests;


use App\Rules\SpecialChar;

class File extends Base
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

    public function rules()
    {
        $rules = [];

        switch ($this->getFunName()) {
            // 图片上传
            case 'uploadImg':
                $rules['image']       = ['required', new SpecialChar];
                $rules['upload_type'] = ['required', new SpecialChar];
                break;

            // 文件上传
            case 'uploadFile':
                $rules['file']        = ['required', new SpecialChar];
                $rules['upload_type'] = ['required', new SpecialChar];
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'image.required'       => '图片不得为空',
            'file.required'        => '文件不得为空',
            'upload_type.required' => '上传类型不得为空',
        ];
    }
}