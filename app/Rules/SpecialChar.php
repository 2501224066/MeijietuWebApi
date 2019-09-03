<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class SpecialChar implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $pregs = '/select|insert|update|CR|document|LF|eval|delete|script|alert|\/\*|\#|\--|\ --|\*|\-|\+|\=|\~|\*@|\*!|\$|\%|\^|\&|\(|\)|\.\.\/|\.\/|union|into|load_file|outfile/';
        return ! preg_match($pregs, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '非法：不得传入特殊字符，例如 * # - ~ . 等等';
    }
}
