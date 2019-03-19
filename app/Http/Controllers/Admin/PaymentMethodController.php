<?php

namespace App\Http\Controllers\Admin;

use App\Scubaya\Helpers\CompressImage;
use App\Scubaya\model\PaymentMethod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PaymentMethodController extends Controller
{
    private $noOfPaymentMethodPerPage    =   15;

    public function __construct()
    {
        $this->middleware('auth');
    }

    // To show all speciality
    public function index()
    {
        $paymentMethods     =   PaymentMethod::paginate($this->noOfPaymentMethodPerPage);

        $sno                =   (($paymentMethods->currentPage() - 1) * $this->noOfPaymentMethodPerPage) + 1;

        return view('admin.manage.payment_method.index')
                ->with('paymentMethods', $paymentMethods)
                ->with('sno', $sno);
    }

    // prepare payment method to store in database
    protected function _preparePaymentMethod($request, $icon)
    {
        $paymentMethod   =   new \stdClass();

        $paymentMethod->name         =   $request->get('name');

        if($icon) {
            $paymentMethod->icon     =   str_replace(" ", "-", $icon->getClientOriginalName());
        }

        return $paymentMethod;
    }

    // To create  payment method
    public function create(Request $request)
    {
        if($request->isMethod('post')) {
            $messages = [
                'name.unique' => 'This payment method is already created. Add new one.',
            ];

            $validator   =   Validator::make($request->all(), [
                'name'   =>  'required|unique:payment_methods,name',
                'icon'   =>  'bail|required|image|mimes:jpg,png,jpeg,gif,svg,bmp|max:2048',
            ], $messages);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $icon         =   $request->file('icon');

            $paymentMethod   =   PaymentMethod::savePaymentMethod($this->_preparePaymentMethod($request, $icon));

            // save icon to local storage
            if($icon) {
                $this->_saveIconToLocalDirectory($icon, $paymentMethod);
            }

            return Redirect::to(route('scubaya::admin::manage::payment_method::index'));
        }

        return view('admin.manage.payment_method.create');
    }

    // to update payment method
    public function update(Request $request)
    {
        if($request->isMethod('post')) {
            $messages = [
                'name.unique' => 'The payment method is already created.',
            ];

            $validator   =   Validator::make($request->all(), [
                'name'   =>  ['required', Rule::unique('payment_methods')->ignore($request->id)],
                'icon'   =>  'sometimes|bail|required|image|mimes:jpg,png,jpeg,gif,svg,bmp|max:2048',
            ], $messages);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $icon            =   $request->file('icon');

            $paymentMethod   =   PaymentMethod::updatePaymentMethod($request->id, $this->_preparePaymentMethod($request, $icon));

            // save icon to local storage
            if($icon) {
                $this->_removeIconFromLocalDirectory($paymentMethod->id);
                $this->_saveIconToLocalDirectory($icon, $paymentMethod);
            }

            return Redirect::to(route('scubaya::admin::manage::payment_method::index'));
        }

        $paymentMethod   =   PaymentMethod::findOrFail($request->id);

        return view('admin.manage.payment_method.edit')
            ->with('paymentMethod', !empty($paymentMethod) ? $paymentMethod : null);
    }

    // to delete payment method
    public function delete(Request $request)
    {
        PaymentMethod::destroy($request->id);

        $this->_removeIconFromLocalDirectory($request->id);

        return Redirect::to(route('scubaya::admin::manage::payment_method::index'));
    }

    // save icon to local directory speciality
    protected function _saveIconToLocalDirectory($icon, $paymentMethod)
    {
        $path     =   public_path(). '/assets/images/scubaya/payment_methods/';
        File::makeDirectory($path, 0777, true, true);

        $filename       =   $paymentMethod->id.'-'.$paymentMethod->icon;
        $compressImage  =   new CompressImage();

        $compressImage->compressImage($icon,$path,$filename);
    }

    // delete icon from directory speciality
    protected function _removeIconFromLocalDirectory($id)
    {
        $path       =   public_path(). '/assets/images/scubaya/payment_methods/'.$id.'*';
        $log_files  =   File::glob($path);
        File::delete($log_files);
    }
}
