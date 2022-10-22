<?php

namespace Waterhole\Forms\Fields;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Waterhole\Forms\Field;
use Waterhole\Models\User;

class UserWebsite extends Field
{
    public function __construct(public ?User $user)
    {
    }

    public function render(): string
    {
        return <<<'blade'
            <x-waterhole::field name="website" :label="__('waterhole::user.website-label')">
                <input
                    id="{{ $component->id }}"
                    type="text"
                    name="website"
                    value="{{ old('website', $user?->website) }}"
                    class="input block"
                    maxlength="100"
                >
            </x-waterhole::field>
        blade;
    }

    public function validating(Validator $validator): void
    {
        $validator->addRules(['website' => ['nullable', 'string', 'max:100']]);
    }

    public function saving(FormRequest $request): void
    {
        $this->user->website = $request->validated('website');
    }
}
