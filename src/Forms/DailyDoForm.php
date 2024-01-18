<?php

namespace Skillcraft\DailyDo\Forms;

use Skillcraft\DailyDo\Models\DailyDo;
use Botble\Base\Forms\FormAbstract;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\DatePickerField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Skillcraft\DailyDo\Http\Requests\DailyDoRequest;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\FieldOptions\DatePickerFieldOption;

class DailyDoForm extends FormAbstract
{
    public function buildForm(): void
    {
        $this
            ->setupModel(new DailyDo)
            ->setValidatorClass(DailyDoRequest::class)
            ->withCustomFields()
            ->add('title', 'text', [
                'label' => trans('core/base::forms.title'),
                'label_attr' => ['class' => 'control-label required'],
                'attr' => [
                    'placeholder' => trans('core/base::forms.title'),
                    'data-counter' => 120,
                ],
            ])
            ->add(
                'description',
                TextareaField::class,
                TextareaFieldOption::make()
                    ->label(trans('core/base::forms.description'))
                    ->placeholder(trans('core/base::forms.description_placeholder'))
                    ->toArray()
            )
            ->add(
                'due_date',
                DatePickerField::class,
                DatePickerFieldOption::make()
                ->label(trans('plugins/sc-daily-do::daily-do.forms.due_date'))
                ->placeholder(trans('core/base::forms.due_date_placeholder'))
                ->toArray()
            )
            ->add(
                'is_completed',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                ->label(trans('plugins/sc-daily-do::daily-do.forms.is_completed'))
                ->value(0)
                ->toArray()
            )
            ->setBreakFieldPoint('due_date');
    }
}
