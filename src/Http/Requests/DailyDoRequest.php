<?php

namespace Skillcraft\DailyDo\Http\Requests;

use Botble\Base\Rules\OnOffRule;
use Botble\Support\Http\Requests\Request;

class DailyDoRequest extends Request
{
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:1000',
            'due_date' => 'required|date',
            'is_completed' => new OnOffRule(),
        ];
    }
}
