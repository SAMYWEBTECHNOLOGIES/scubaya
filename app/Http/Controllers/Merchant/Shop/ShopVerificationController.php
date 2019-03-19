<?php

namespace App\Http\Controllers\Merchant\Shop;

use App\Scubaya\model\MerchantUsersRoles;
use App\Scubaya\model\Shops;
use App\Scubaya\model\WebsiteDetails;
use App\Scubaya\model\WebsiteDocumentsMapper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ShopVerificationController extends Controller
{
    private $authUserId ;

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            if(Auth::user()->is_merchant_user) {
                $this->authUserId   =   MerchantUsersRoles::where('user_id', Auth::id())->value('merchant_id');
            } else {
                $this->authUserId   =   Auth::id();
            }

            return $next($request);
        });
    }

    protected function _prepareWebsiteDetails($request)
    {
        $detail =   new \stdClass();

        $detail->first_name         =   $request->verification['first_name'];
        $detail->last_name          =   $request->verification['last_name'];
        $detail->phone_no           =   $request->verification['phone_no'];
        $detail->email              =   $request->verification['email'];
        $detail->address            =   $request->verification['address'];
        $detail->street             =   $request->verification['street'];
        $detail->house_no           =   $request->verification['house_number'];
        $detail->house_no_extension =   $request->verification['house_number_extension'];
        $detail->city               =   $request->verification['city'];
        $detail->state              =   $request->verification['state'];
        $detail->country            =   $request->verification['country'];
        $detail->zip_code           =   $request->verification['postal_code'];
        $detail->company_name       =   $request->verification['company_name'];
        $detail->legal_id_no        =   $request->verification['legal_id_number'];
        $detail->vat_no             =   $request->verification['vat_number'];

        return $detail;
    }

    public function create(Request $request)
    {
        $shop   =   Shops::find($request->website_id);

        $validator = Validator::make($request->all(), [
            'verification.first_name' => 'required',
            'verification.last_name'  => 'required',
            'verification.phone_no'   => 'numeric',
            'verification.email'         => 'required|email',
            'verification.passport'             => 'required|mimes:jpg,png,jpeg,pdf|max:2048',
            'verification.company_legal_doc'    => 'required|mimes:jpg,png,jpeg,pdf|max:2048',
            'verification.company_bank_details' => 'required|mimes:jpg,png,jpeg,pdf|max:2048',

        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'verificationError')
                ->withInput()
                ->with('errorInModalId', $request->website_id)
                ->with('shop', $shop);
        }

        // save website details
        $detail =   $this->_prepareWebsiteDetails($request);

        $detail->website_type   =   SHOP;
        $detail->website_id     =   $request->website_id;
        $detail->merchant_key   =   $this->authUserId;

        $detail =   WebsiteDetails::saveDetails($detail);

        // save documents for verification
        $this->_saveDocuments($detail);

        return Redirect::to(route('scubaya::merchant::shop::shops', [Auth::id()]));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'verification.first_name' => 'required',
            'verification.last_name'  => 'required',
            'verification.email'      => 'required|email',
            'verification.phone_no'   => 'numeric',
            'verification.passport'             => 'sometimes|required|mimes:jpg,png,jpeg,pdf|max:2048',
            'verification.company_legal_doc'    => 'sometimes|required|mimes:jpg,png,jpeg,pdf|max:2048',
            'verification.company_bank_details' => 'sometimes|required|mimes:jpg,png,jpeg,pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'verificationError')
                ->with('errorInModalId', $request->detail_id)
                ->withInput();
        }

        // update website details
        $detail =   $this->_prepareWebsiteDetails($request);
        $detail =   WebsiteDetails::updateDetails($request->detail_id, $detail);

        $websiteDocuments  =   WebsiteDocumentsMapper::where('website_detail_id', $detail->id)->first();

        $documents         =   $this->_updateRequestHasNewDocuments($request, $detail, $websiteDocuments);

        if($documents){
            /* update in database */
            WebsiteDocumentsMapper::updateDocuments($documents, $websiteDocuments);
        }

        return Redirect::to(route('scubaya::merchant::settings::account_verification', [Auth::id()]));
    }

    public function delete(Request $request)
    {
        WebsiteDetails::destroy($request->detail_id);

        WebsiteDocumentsMapper::where('website_detail_id', $request->detail_id)->delete();

        $path =  public_path().'/assets/images/scubaya/merchant/website/documents/shop-'.$request->detail_id;
        File::deleteDirectory($path);

        return Redirect::to(route('scubaya::merchant::settings::account_verification', [Auth::id()]));
    }

    protected function _saveDocuments($detail)
    {
        $documents  =   [
            'website_id'        =>  $detail->website_id,
            'website_detail_id' =>  $detail->id,
            'passport_or_id'    =>  $this->_storeDocumentInLocalDirectory(Input::file('verification.passport'), $detail),
            'legal_doc'         =>  $this->_storeDocumentInLocalDirectory(Input::file('verification.company_legal_doc'), $detail),
            'bank_details'      =>  $this->_storeDocumentInLocalDirectory(Input::file('verification.company_bank_details'), $detail),
            'status'            =>  'pending'
        ];

        WebsiteDocumentsMapper::saveDocuments($documents);
    }

    /*
     *  check update request has new document or not
     *  if it has then remove the previous doc and
     *  store the new one in directory
     */
    protected function _updateRequestHasNewDocuments($request, $details, $documents)
    {
        $Documents  =   array();

        if(!empty($request->file('verification.passport'))){
            $this->_removeDocumentFromLocalDirectory($details, $documents->passport_or_id);
            $this->_storeDocumentInLocalDirectory($request->file('verification.passport'), $details);

            $Documents['passport_or_id']        =   $request->file('verification.passport');
        }

        if(!empty($request->file('verification.company_legal_doc'))){
            $this->_removeDocumentFromLocalDirectory($details, $documents->legal_doc);
            $this->_storeDocumentInLocalDirectory($request->file('verification.company_legal_doc'), $details);

            $Documents['legal_doc']     =   $request->file('verification.company_legal_doc');
        }

        if(!empty($request->file('verification.company_bank_details'))){
            $this->_removeDocumentFromLocalDirectory($details, $documents->bank_details);
            $this->_storeDocumentInLocalDirectory($request->file('verification.company_bank_details'), $details);

            $Documents['bank_details']  =   $request->file('verification.company_bank_details');
        }

        return $Documents;
    }

    /*
     * store the documents in local directory
     * and return file name to store in database
    */
    protected function _storeDocumentInLocalDirectory($file, $details)
    {
        $path     =   public_path(). '/assets/images/scubaya/website/documents/shop-'.$details->id.'/';
        File::makeDirectory($path, 0777, true, true);

        $filename = str_replace(' ', '-', $file->getClientOriginalName());
        $file->move($path, ($details->id.'-'.$filename));

        return  $filename;
    }

    /* remove document from local directory */
    protected function _removeDocumentFromLocalDirectory($details, $file)
    {
        $path = public_path(). '/assets/images/scubaya/website/documents/shop-'.$details->id.'/'.$details->id.'-'.$file;
        File::delete($path);
    }
}
