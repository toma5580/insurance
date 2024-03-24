<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CustomField;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class CompanyController extends Controller {
    /*
    |--------------------------------------------------------------------------
    | Company Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the deletion and update of existing companies.
    | Why don't you explore it?
    |
    */

    /**
     * Create a new user controller instance.
     * 
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('localize_auth');
    }

    /**
     * Add/Edit Custom Fields
     *
     * @param  Illuminate\Support\Collection  $custom_fields
     * @param  Illuminate\Support\Collection  $collection
     * @return void
     */
    protected function addCustomFields(Collection $custom_fields, Collection $models) {
        $models->each(function($model) use($custom_fields) {
            $custom_fields->each(function($custom_field) use($model) {
                try {
                    $existing_custom_field = $model->customFields()->where('uuid', $custom_field['uuid'])->firstOrFail();
                    $existing_custom_field->label = $custom_field['label'];
                    if($existing_custom_field->type === $custom_field['type']) {
                        $existing_custom_field->value = isset($custom_field['default']) ? (is_array($custom_field['default']) ? (in_array(json_decode($existing_custom_field->value)->choice, $custom_field['default']['choices'], true) ? json_encode(array(
                            'choices'   => $custom_field['default']['choices'],
                            'choice'    => json_decode($existing_custom_field->value)->choice
                        )) : json_encode($custom_field['default'])) : $existing_custom_field->value) : null;
                    }else {
                        $existing_custom_field->type = $custom_field['type'];
                        $existing_custom_field->value = isset($custom_field['default']) ? (is_array($custom_field['default']) ? json_encode($custom_field['default']) : $custom_field['default']) : null;
                    }
                    $existing_custom_field->save();
                }catch(ModelNotFoundException $e) {
                    $cf = new CustomField(array(
                        'label' => $custom_field['label'],
                        'type'  => $custom_field['type'],
                        'uuid'  => $custom_field['uuid'],
                        'value' => isset($custom_field['default']) ? (is_array($custom_field['default']) ? json_encode($custom_field['default']) : $custom_field['default']) : null
                    ));
                    $cf->model()->associate($model);
                    $cf->save();
                }
            });
            $model->customFields()->whereNotIn('uuid', $custom_fields->map(function($custom_field) {
                return $custom_field['uuid'];
            })->all())->delete();
        });
    }

    /**
     * Delete a company
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function delete(Company $company) {
        $company->delete();
        return redirect()->back()->with('info', trans('companies.message.info.delete'));
    }

    /**
     * Edit a company
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Company $company) {
        $this->validate($request, array(
            'address'                   => 'max:256|string',
            'aft_api_key'               => 'max:64|required_if:text_provider,aft|string',
            'aft_username'              => 'max:64|required_if:text_provider,aft|string',
            'currency_code'             => 'in:' . collect(config('insura.currencies.list'))->map(function($currency) {
                return $currency['code'];
            })->implode(',') . '|string|required',
            'custom_fields'             => 'array|sometimes',
            'email'                     => 'email',
            'email_signature'           => 'string',
            'name'                      => 'max:64|min:3|required',
            'phone'                     => 'max:32',
            'product_categories'        => 'string',
            'product_sub_categories'    => 'string',
            'text_provider'             => 'in:aft,twilio|string',
            'text_signature'            => 'max:32|string',
            'twilio_auth_token'         => 'max:64|required_if:text_provider,twilio|string',
            'twilio_number'             => 'max:32|required_if:text_provider,twilio|string',
            'twilio_sid'                => 'max:64|required_if:text_provider,twilio|string'
        ));
        $custom_fields_metadata = collect(array());
        if(isset($request->custom_fields)) {
            foreach($request->custom_fields as $custom_field) {
                $custom_field['uuid'] = isset($custom_field['uuid']) ? $custom_field['uuid'] : uniqid('ur41-n5u1-');
                if($custom_field['type'] === 'select') {
                    $custom_field['default']['choices'] = explode(',', str_replace(array(', ', "\r\n", "\n"), ',', $custom_field['default']['choices']));
                    $custom_field['default']['choice'] = $custom_field['default']['choices'][0];
                }
                $custom_fields_metadata->push($custom_field);
            }
        }

        $company->address                   = $request->address ?: null;
        $company->aft_api_key               = $request->aft_api_key ?: null;
        $company->aft_username              = $request->aft_username ?: null;
        $company->currency_code             = $request->currency_code;
        $company->custom_fields_metadata    = $custom_fields_metadata->toJson();
        $company->email                     = $request->email ?: null;
        $company->email_signature           = str_replace("\n", '<br/>', $request->email_signature) ?: null;
        $company->name                      = $request->name;
        $company->phone                     = $request->phone ?: null;
        $company->product_categories        = $request->product_categories ?: null;
        $company->product_sub_categories    = $request->product_sub_categories ?: null;
        $company->text_provider             = $request->text_provider ?: null;
        $company->text_signature            = $request->text_signature ?: null;
        $company->twilio_auth_token         = $request->twilio_auth_token ?: null;
        $company->twilio_number             = $request->twilio_number ?: null;
        $company->twilio_sid                = $request->twilio_sid ?: null;
        $company->save();
        $this->addCustomFields($custom_fields_metadata->where('model', 'client'), $company->clients);
        $this->addCustomFields($custom_fields_metadata->where('model', 'policy'), $company->policies);

        return redirect()->back()->with('success', trans('settings.message.success.company.edit'))->with('tab', 'company');
    }

    /**
     * Get all companies
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll(Request $request) {
        $companies = Company::where('id', '!=', $request->user()->company->id)->get();
        return view('super.companies', array(
            'companies' => $companies
        ));
    }
}
