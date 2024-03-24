<?php

use App\Models\Company;
use App\Models\CustomField;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Add custom fields to a given model
 * 
 * @param \Illuminate\Database\Eloquent\Model $model
 * @param \Illuminate\Support\Collection $collection
 * @return void
 */
function addCustomFieldsToModel(Model $model, Collection $custom_fields) {
    $custom_fields->each(function($custom_field) use ($model) {
        $cf = new CustomField(array(
            'label' => $custom_field->label,
            'type'  => $custom_field->type,
            'uuid'  => $custom_field->uuid,
            'value' => isset($custom_field->default) ? (is_object($custom_field->default) ? json_encode($custom_field->default) : $custom_field->default) : null
        ));
        $cf->model()->associate($model);
        $cf->save();
    });
}

foreach(Company::all()->all() as $company) {
    $custom_fields_metadata = collect(json_decode($company->custom_fields_metadata));
    $custom_fields_metadata->each(function($custom_field_metadata, $key) use($company, $custom_fields_metadata) {
        $custom_field_metadata->uuid = uniqid('ur41-n5u1-');
        $custom_fields_metadata->put($key, $custom_field_metadata);
        $company->clients->each(function($client) use($custom_fields_metadata) {
            $client->customFields()->delete();
            addCustomFieldsToModel($client, $custom_fields_metadata->where('model', 'client'));
        });
        $company->policies->each(function($policy) use($custom_fields_metadata) {
            $policy->customFields()->delete();
            addCustomFieldsToModel($policy, $custom_fields_metadata->where('model', 'policy'));
        });
    });
    $company->custom_fields_metadata = $custom_fields_metadata->toJson();
}
