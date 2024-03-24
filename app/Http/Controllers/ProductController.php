<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Product;
use App\Pagination\SemanticUIPresenter;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ProductController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('localize_auth');
    }

    public function add(Request $request) {
        $company = null;
        try{
            $company = Company::findOrFail($request->company_id);
        }catch(ModelNotFoundException $e) {
            $company = $request->user()->company;
        }
        $this->validate($request, [
            'category'      => 'in:' . str_replace(', ', ',', $company->product_categories) . '|required|string',
            'company_id'    => 'exists:companies,id|sometimes',
            'insurer'       => 'max:128|required|string',
            'name'          => 'max:64|min:3|required',
            'sub_category'  => 'in:' . str_replace(', ', ',', $company->product_sub_categories) . '|required|string'
        ]);
        $company->products()->create(array(
            'category'      => $request->category,
            'insurer'       => $request->insurer,
            'name'          => $request->name,
            'sub_category'  => $request->sub_category
        ));
        return redirect()->back()->with('success', trans('products.message.success.added'));
    }

    public function delete(Request $request, Product $product) {
        $product->delete();
        return redirect()->back()->with('status', trans('products.message.info.deleted'));
    }
    
    public function edit(Request $request, Product $product) {
        $company = null;
        try{
            $company = Company::findOrFail($request->company_id);
        }catch(ModelNotFoundException $e) {
            $company = $product->company;
        }
        $this->validate($request, [
            'category'      => 'in:' . str_replace(', ', ',', $company->product_categories) . '|required|string',
            'company_id'    => 'exists:companies,id|integer|sometimes',
            'insurer'       => 'max:128|required|string',
            'name'          => 'max:64|min:3|required',
            'sub_category'  => 'in:' . str_replace(', ', ',', $company->product_sub_categories) . '|required|string'
        ]);
        $product->category      = $request->category;
        $product->insurer       = $request->insurer;
        $product->name          = $request->name;
        $product->sub_category  = $request->sub_category;
        if((!is_null($request->company_id) || !empty($request->company_id)) && $product->company->id != $request->company_id) {
            $new_company = Company::findOrFail($request->company_id);
            $product->company()->associate($company);
        }
        $product->save();

        return redirect()->back()->with('success', trans('products.message.success.edited'));
    }
    
    public function getAll(Request $request) {
        $user = $request->user();
        $view_data = array();
        if($user->role === 'super') {
            $view_data['companies'] = Company::all();
            $view_data['products'] = Product::paginate(15);
            $view_data['companies']->transform(function($company) {
                $company->product_categories = collect(explode(',', str_replace(', ', ',', $company->product_categories)))->reject(function($c) {
                    return empty($c);
                });
                $company->product_sub_categories = collect(explode(',', str_replace(', ', ',', $company->product_sub_categories)))->reject(function($sc) {
                    return empty($sc);
                });
                return $company;
            });
        }else {
            $view_data['company'] = $user->company;
            $view_data['products'] = $user->company->products()->paginate(15);
            $view_data['company']->product_categories = collect(explode(',', str_replace(', ', ',', $view_data['company']->product_categories)))->reject(function($c) {
                return empty($c);
            });
            $view_data['company']->product_sub_categories = collect(explode(',', str_replace(', ', ',', $view_data['company']->product_sub_categories)))->reject(function($sc) {
                return empty($sc);
            });
        }
        $view_data['products']->lastOnPreviousPage = ($view_data['products']->currentPage() - 1) * $view_data['products']->perPage();
        $view_data['presenter'] = new SemanticUIPresenter($view_data['products']);
        
        
        return view($user->role . '.products', $view_data);
    }
}
