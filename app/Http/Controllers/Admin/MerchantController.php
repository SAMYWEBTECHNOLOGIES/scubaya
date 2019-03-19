<?php

namespace App\Http\Controllers\Admin;

use App\Scubaya\Helpers\SendMailHelper;
use App\Scubaya\model\Hotel;
use App\Scubaya\model\ManageDiveCenter;
use App\Scubaya\model\Merchant;
use App\Scubaya\model\MerchantDetails;
use App\Scubaya\model\MerchantDocumentsMapper;
use App\Scubaya\model\MerchantPriceSettings;
use App\Scubaya\model\Shops;
use App\Scubaya\model\User;
use App\Scubaya\model\WebsiteDocumentsMapper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MerchantController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function merchant(Request $request)
    {
        $merchantPricingSettings    =   MerchantPriceSettings::where('merchant_key',$request->id)->first();

        $accountDetails             =   User::where('users.id',$request->id)
                                            ->join('merchants','users.id','=','merchants.merchant_key')
                                            ->join('merchant_details','users.id','=','merchant_details.merchant_primary_id')
                                            ->where('merchant_details.id', $request->detail_id)
                                            ->select('users.email','merchants.*','merchant_details.*')
                                            ->first();

        $mainAccountDocuments       =   MerchantDetails::join('merchants_x_merchants_documents', 'merchant_details.id', '=', 'merchants_x_merchants_documents.merchant_detail_id')
                                            ->where('merchant_details.merchant_primary_id', $request->id)
                                            ->where('merchant_details.id', $request->detail_id)
                                            ->select(
                                                'merchants_x_merchants_documents.passport_or_id as passport',
                                                'merchants_x_merchants_documents.company_legal_doc',
                                                'merchants_x_merchants_documents.company_bank_details',
                                                'merchant_details.merchant_primary_id',
                                                'merchants_x_merchants_documents.merchant_detail_id',
                                                'merchants_x_merchants_documents.id'
                                            )
                                            ->first();

        // fetch sub account details
        $shops       =  Shops::where('merchant_key', $request->id)->get();

        $hotels      =  Hotel::where('merchant_primary_id', $request->id)->get();

        $diveCenters =  ManageDiveCenter::where('merchant_key', $request->id)->get();

        $websites    =  [
            'shops'     =>  $shops,
            'hotels'    =>  $hotels,
            'centers'   =>  $diveCenters
        ];

        return view('admin.merchants.merchant',[
            'account_details'           =>  $accountDetails,
            'mainAccountDocuments'      =>  $mainAccountDocuments,
            'websites'                  =>  $websites,
            'merchant_pricing_settings' =>  $merchantPricingSettings,
            'id'                        =>  $request->id
        ]);
    }

    public function updateMainAccountStatus(Request $request)
    {
        $isRejected     =   false;

        $merchantDocs   =   MerchantDocumentsMapper::find($request->id);

        if($request->get('passport')) {
            $Passport   =   (array)json_decode($merchantDocs->passport_or_id);

            $Passport['status']           = $request->passport['status'];
            $Passport['rejection_reason'] = $request->passport['rejection_reason'];
            $Passport['show_in_merchant'] = $request->passport['show_passport'];

            $merchantDocs->passport_or_id   =   json_encode($Passport);

            if($request->passport['status'] == 'rejected') {
                $isRejected =   true;
            }
        }

        if($request->get('legalDoc')) {
            $LegalDoc   =   (array)json_decode($merchantDocs->company_legal_doc);

            $LegalDoc['status']           = $request->legalDoc['status'];
            $LegalDoc['rejection_reason'] = $request->legalDoc['rejection_reason'];
            $LegalDoc['show_in_merchant'] = $request->legalDoc['show_legal_doc'];

            $merchantDocs->company_legal_doc   =   json_encode($LegalDoc);

            if($request->legalDoc['status'] == 'rejected') {
                $isRejected =   true;
            }
        }

        if($request->get('bankDetail')) {
            $BankDetail   =   (array)json_decode($merchantDocs->company_bank_details);

            $BankDetail['status']           = $request->bankDetail['status'];
            $BankDetail['rejection_reason'] = $request->bankDetail['rejection_reason'];
            $BankDetail['show_in_merchant'] = $request->bankDetail['show_bank_detail'];

            $merchantDocs->company_bank_details   =   json_encode($BankDetail);

            if($request->bankDetail['status'] == 'rejected') {
                $isRejected =   true;
            }
        }

        $merchantDocs->update();

        // if any one of the all docs gets rejected then
        // main account status should put back to the pending state
        if( $isRejected ) {
            MerchantDetails::where('id', $merchantDocs->merchant_detail_id)->update([
                'status'    =>  'pending'
            ]);
        }

        return redirect()->back();
    }

    public function websiteAccountStatus(Request $request)
    {
        $merchant_documents  =   WebsiteDocumentsMapper::find($request->document_id);

        if($merchant_documents->status == 'rejected') {
            return response(0);
        }

        /*change the status of sub account */
        $merchant_documents->status     =   $request->value;
        $merchant_documents->is_active  =   $request->isActive;

        $merchant_documents->update();

        return response(1);
    }

    public function merchantPricingSettings(Request $request)
    {
        $this->validate($request,[
            'commission_dive_center'    =>  'sometimes|integer',
            'commission_dive_hotel'     =>  'sometimes|integer',
            'commission_dive_shop'      =>  'sometimes|integer',
            'commission_percentage'     =>  'sometimes|integer'
        ]);

        $data   =   [
            'merchant_key'                  =>  $request->id,
            'active_commission'             =>  $request->active_commission,
            'charge_commission_merchant'    =>  $request->charge_commission_merchant,
            'auto_block'                    =>  $request->auto_block,
            'website_level'                 =>  $request->website_level,
            'unpaid_invoices'               =>  $request->unpaid_invoices,
            'charge_commission_shop'        =>  $request->charge_commission_shop,
            'commission_dive_center'        =>  $request->commission_dive_center,
            'commission_dive_hotel'         =>  $request->commission_dive_hotel,
            'commission_dive_shop'          =>  $request->commission_dive_shop,
            'commission_percentage'         =>  $request->commission_percentage
        ];

        MerchantPriceSettings::updateOrCreate(['merchant_key'=>$request->id],$data);

        return redirect()->back();
    }

    public function prepareData($request)
    {
        $merchantData   =   [
            'UID'               =>  Merchant::merchantId(),
            'first_name'        =>  $request->get('first_name'),
            'last_name'         =>  $request->get('last_name'),
            'email'             =>  $request->get('merchant_email'),
            'password'          =>  Hash::make($request->get('merchant_password')),
            'is_merchant'       =>  IS,
            'account_status'    =>  MERCHANT_STATUS_APPROVED,
            'confirmed'         =>  1
        ];

        return $merchantData;
    }

    public function _prepareMerchantDetails($merchantId)
    {
        $merchant               =   new \stdClass();

        $merchant->merchant_key =   $merchantId;

        return $merchant;
    }

    public function createMerchant(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'merchant_email' => 'required|email|unique:users,email,NULL,id,is_merchant,'.IS,
        ]);

        if ($validator->fails()){
            return redirect()->back()
                ->withErrors($validator,'add_merchant')
                ->withInput();
        }

        // save merchant
        $merchantData   =   $this->prepareData($request);
        $user           =   User::saveUser($merchantData);

        // save merchant profile details
        Merchant::saveMerchant($this->_prepareMerchantDetails($user->id));

        $request->session()->flash('success','Merchant with email- '.$request->merchant_email.' created successfully.');
        return redirect()->route('scubaya::admin::merchants_accounts');
    }

    public function updateMerchantDetails(Request $request,$merchantKey, $detailId)
    {
        if($request->isMethod('post')) {

            // update merchant configuration
            $merchant = Merchant::where('merchant_key', '=', $merchantKey)->first();

            $merchant->company_name         = $request->company_name;
            $merchant->vat_number           = $request->vat_number;
            $merchant->chamber_of_commerce  = $request->chamber_of_commerce;
            $merchant->street               = $request->street;
            $merchant->town                 = $request->town;
            $merchant->region               = $request->region;
            $merchant->country              = $request->country;
            $merchant->postcode             = $request->postcode;
            $merchant->telephone            = $request->telephone;
            $merchant->longitude            = $request->longitude;
            $merchant->latitude             = $request->latitude;

            $merchant->update();

            // update merchant detail
            $merchantDetail =   MerchantDetails::where('id', $detailId)->first();

            $merchantDetail->status         =   $request->get('status');
            $merchantDetail->screening      =   $request->get('screening');
            $merchantDetail->contact_module =   $request->get('show_contact_module');

            $merchantDetail->update();

            // update email of merchant
            User::where('id', $merchantKey)->update(['email' => $request->email]);

            // if merchant account status is disabled then
            // send an email to the merchant
            if($request->get('status') == MERCHANT_STATUS_DISABLED) {
                $this->sendMailForDisabledAccount($merchantKey);
            }
        }

        return redirect()->back();
    }

    public function sendMailForDisabledAccount($merchantId)
    {
        $email      =   User::where('id', $merchantId)->where('is_merchant', IS)->value('email');

        $sender     =   env('MAIL_FROM_ADDRESS');
        $template   =   'email.default';
        $subject    =   trans('email.account_disabled');
        $message    =   trans('email.account_disabled_message');

        $mail_helper    =   new SendMailHelper($sender, $email, $template, $subject, $message);
        $mail_helper->sendMail();
    }
}
