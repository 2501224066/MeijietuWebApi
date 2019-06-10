<?php


namespace App\Http\Requests;


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
                $rules['image']       = 'required';
                $rules['upload_type'] = 'required';
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'image.required'       => '图片不得为空',
            'upload_type.required' => '上传类型不得为空',
        ];
    }
}