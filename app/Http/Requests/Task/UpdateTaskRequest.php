<?php
/**
 * Invoice Ninja (https://invoiceninja.com).
 *
 * @link https://github.com/invoiceninja/invoiceninja source repository
 *
 * @copyright Copyright (c) 2020. Invoice Ninja LLC (https://invoiceninja.com)
 *
 * @license https://opensource.org/licenses/AAL
 */

namespace App\Http\Requests\Task;

use App\Http\Requests\Request;
use App\Http\ValidationRules\IsDeletedRule;
use App\Http\ValidationRules\ValidTaskGroupSettingsRule;
use App\Utils\Traits\ChecksEntityStatus;
use App\Utils\Traits\MakesHash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends Request
{
    use MakesHash;
    use ChecksEntityStatus;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return auth()->user()->can('edit', $this->task);
    }

    public function rules()
    {
        $rules = [];
        /* Ensure we have a client name, and that all emails are unique*/

        if ($this->input('number')) {
            $rules['number'] = 'unique:tasks,number,'.$this->id.',id,company_id,'.$this->taskss->company_id;
        }

        return $this->globalRules($rules);
    }

    // public function messages()
    // {
    //     return [
    //         'unique' => ctrans('validation.unique', ['attribute' => 'email']),
    //         'email' => ctrans('validation.email', ['attribute' => 'email']),
    //         'name.required' => ctrans('validation.required', ['attribute' => 'name']),
    //         'required' => ctrans('validation.required', ['attribute' => 'email']),
    //     ];
    // }

    protected function prepareForValidation()
    {

        $input = $this->decodePrimaryKeys($this->all()); 

        $this->replace($input);
    }
}
