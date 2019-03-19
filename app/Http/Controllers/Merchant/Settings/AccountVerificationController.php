<?php

namespace App\Http\Controllers\Merchant\Settings;

use App\Scubaya\model\Instructor;
use App\Scubaya\model\MerchantDetails;
use App\Scubaya\model\MerchantDocumentsMapper;
use App\Scubaya\model\MerchantUsersRoles;
use App\Scubaya\model\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class AccountVerificationController extends Controller
{
    private $authUserId;

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

    /* prepare and return merchant sign up data before further processing */
    public function prepareData($request)
    {
        $merchantDetails = [
            'merchant_primary_id'   => $this->authUserId,
            'company_type'          => $request->get('company_type'),
            'company_id'            => $request->get('company_id'),
            'full_name'             => $request->get('representative_full_name'),
            'dob'                   => $request->get('merchant_dob'),
            'address'               => $request->get('street'),
            'city'                  => $request->get('merchant_city'),
            'postal_code'           => $request->get('merchant_postal_code'),
            'status'                => 'pending'
        ];

        return $merchantDetails;
    }

    /* return merchant account verification view with request data */
    public function accountVerification()
    {
        // fetch main account details
        $merchantDetails = MerchantDetails::join('merchants_x_merchants_documents', 'merchant_details.id', '=', 'merchants_x_merchants_documents.merchant_detail_id')
                                ->where('merchant_details.merchant_primary_id', $this->authUserId)
                                ->select('merchants_x_merchants_documents.*', 'merchant_details.*')
                                ->get();

        // fetch sub account details
        /*$shopAccountDetails = DB::table('website_details')
            ->select('website_details.*', 'shop_information.name', 'website_details_x_documents.passport_or_id'
                , 'website_details_x_documents.legal_doc', 'website_details_x_documents.bank_details', 'website_details_x_documents.status')
            ->join('shop_information', 'shop_information.id', '=', 'website_details.website_id')
            ->join('website_details_x_documents', 'website_details.id', '=', 'website_details_x_documents.website_detail_id')
            ->where('website_details.merchant_key', $this->authUserId)
            ->where('website_details.website_type', SHOP)
            ->get();

        $hotelAccountDetails = DB::table('website_details')
            ->select('website_details.*', 'hotels_general_information.name', 'website_details_x_documents.passport_or_id'
                , 'website_details_x_documents.legal_doc', 'website_details_x_documents.bank_details', 'website_details_x_documents.status')
            ->join('hotels_general_information', 'hotels_general_information.id', '=', 'website_details.website_id')
            ->join('website_details_x_documents', 'website_details.id', '=', 'website_details_x_documents.website_detail_id')
            ->where('website_details.merchant_key', $this->authUserId)
            ->where('website_details.website_type', HOTEL)
            ->get();

        $diveCenterAccountDetails = DB::table('website_details')
            ->select('website_details.*', 'manage_dive_centers.name', 'website_details_x_documents.passport_or_id'
                , 'website_details_x_documents.legal_doc', 'website_details_x_documents.bank_details', 'website_details_x_documents.status')
            ->join('manage_dive_centers', 'manage_dive_centers.id', '=', 'website_details.website_id')
            ->join('website_details_x_documents', 'website_details.id', '=', 'website_details_x_documents.website_detail_id')
            ->where('website_details.merchant_key', $this->authUserId)
            ->where('website_details.website_type', DIVE_CENTER)
            ->get();

        $accountDetails = [$shopAccountDetails, $hotelAccountDetails, $diveCenterAccountDetails];*/

        return view('merchant.settings.account_verification.index')
            ->with('merchantDetails', !empty($merchantDetails) ? $merchantDetails : null)
            //->with('accountDetails', !empty($accountDetails) ? $accountDetails : null)
            ->with('authId', $this->authUserId);
    }

    /* save merchant sign up information and documents  */
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_type'              => 'required',
            'company_id'                => 'required',
            'representative_full_name'  => 'required',
            'merchant_dob'              => 'required',
            'street'                    => 'required',
            'merchant_postal_code'      => 'required',
            'merchant_city'             => 'required',
            'passport'                  => 'required|bail|mimes:jpg,png,jpeg,pdf|max:2048',
            'company_legal_doc'         => 'required|bail|mimes:jpg,png,jpeg,pdf|max:2048',
            'company_bank_details'      => 'required|bail|mimes:jpg,png,jpeg,pdf|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->route('scubaya::merchant::settings::account_verification', [Auth::id()])
                ->withErrors($validator, 'saveRequestErrors')
                ->withInput();
        }

        // prepare data
        $merchantDetails = $this->prepareData($request);
        $merchantDetails = MerchantDetails::saveMerchantDetails($merchantDetails);

        /* upload documents */
        $this->_uploadDocuments($merchantDetails, $this->getMerchantId($merchantDetails->merchant_primary_id));

        return Redirect::to(route('scubaya::merchant::settings::account_verification', [$merchantDetails['merchant_primary_id']]));
    }

    /* prepare update request data before further processHing */
    public function prepareUpdateRequestData($request)
    {
        $merchantDetails = [
            'company_type'  => $request->get('company_type'),
            'company_id'    => $request->get('company_id'),
            'full_name'     => $request->get('representative_full_name'),
            'dob'           => $request->get('merchant_dob'),
            'address'       => $request->get('street'),
            'city'          => $request->get('merchant_city'),
            'postal_code'   => $request->get('merchant_postal_code'),
        ];

        return $merchantDetails;
    }

    /* update merchant sign up information and documents */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_type'              => 'required',
            'company_id'                => 'required',
            'representative_full_name'  => 'required',
            'merchant_dob'              => 'required',
            'street'                    => 'required',
            'merchant_postal_code'      => 'required',
            'merchant_city'             => 'required',
            'passport'                  => 'bail|mimes:jpg,png,jpeg,pdf|max:2048',
            'company_legal_doc'         => 'bail|mimes:jpg,png,jpeg,pdf|max:2048',
            'company_bank_details'      => 'bail|mimes:jpg,png,jpeg,pdf|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->route('scubaya::merchant::settings::account_verification', [Auth::id()])
                ->withErrors($validator, 'updateRequestErrors')
                ->withInput();
        }

        $merchant = MerchantDetails::find($request->merchant_detail_id);

        $merchantDocuments = MerchantDocumentsMapper::where('merchant_detail_id', $merchant->id)->first();

        if ($merchant) {
            // prepare data
            $merchantDetails = $this->prepareUpdateRequestData($request);
            $documents       = $this->_updateRequestHasNewDocuments($request, $merchant, $merchantDocuments);

            MerchantDetails::updateMerchantDetails($merchantDetails, $merchant);

            if ($documents) {
                /* update in database */
                MerchantDocumentsMapper::updateDocuments($documents, $merchantDocuments);
            }
        }

        return Redirect::to(route('scubaya::merchant::settings::account_verification', [Auth::id()]));
    }

    /* delete merchant sign up information and documents */
    public function delete(Request $request)
    {
        $merchant = MerchantDetails::where('id', $request->merchant_detail_id)->first();

        MerchantDocumentsMapper::where('merchant_detail_id', $request->merchant_detail_id)->delete();

        $path = public_path() . '/assets/images/scubaya/merchant/' . $this->getMerchantId($merchant->merchant_primary_id) . '-req' . $merchant['id'];
        File::deleteDirectory($path);

        $merchant->delete();

        $request->session()->flash('message', 'Request deleted successfully!');
        return Redirect::to(route('scubaya::merchant::settings::account_verification', [Auth::id()]));
    }

    /*
     * upload and save the necessary documents of merchant
     *  i.e. passport , company legal document, company bank details
    */
    protected function _uploadDocuments($merchantDetails, $mUID)
    {
        $documents = [
            'merchant_detail_id'    =>  $merchantDetails['id'],
            'merchant_primary_id'   =>  $merchantDetails['merchant_primary_id'],
            'passport_or_id'        =>  json_encode([
                'passport'          =>  $this->_storeImageInLocalDirectory(Input::file('passport'), $merchantDetails, $mUID),
                'status'            =>  'pending',
                'rejection_reason'  =>  null,
                'show_in_merchant'  =>  0
            ]),
            'company_legal_doc'     =>  json_encode([
                'legal_doc'         =>  $this->_storeImageInLocalDirectory(Input::file('company_legal_doc'), $merchantDetails, $mUID),
                'status'            =>  'pending',
                'rejection_reason'  =>  null,
                'show_in_merchant'  =>  0
            ]),
            'company_bank_details'  =>  json_encode([
                'bank_detail'       =>  $this->_storeImageInLocalDirectory(Input::file('company_bank_details'), $merchantDetails, $mUID),
                'status'            =>  'pending',
                'rejection_reason'  =>  null,
                'show_in_merchant'  =>  0
            ]),
            //'status'                => MERCHANT_STATUS_PENDING,
            'upload_hits'           => 0
        ];

        MerchantDocumentsMapper::saveDocuments($documents);
    }

    /*
     * store the documents in local directory
     * and return file name to store in database
    */
    protected function _storeImageInLocalDirectory($file, $merchantDetails, $mUID)
    {
        $path = public_path() . '/assets/images/scubaya/merchant/' . $mUID . '-req' . $merchantDetails['id'];
        File::makeDirectory($path, 0777, true, true);

        $filename = str_replace(' ', '-', $file->getClientOriginalName());
        $file->move($path, $filename);

        return $filename;
    }

    /* remove file from local directory */
    protected function _removeImageFromLocalDirectory($merchantDetails, $file, $mUID)
    {
        $path = public_path() . '/assets/images/scubaya/merchant/' . $mUID . '-req' . $merchantDetails['id'] . '/' . $file;
        File::delete($path);
    }

    /*
     *  check update request has new document or not
     *  if it has then remove the previous doc and
     *  store the new one in directory
     */
    protected function _updateRequestHasNewDocuments($request, $merchantDetails, $merchantDocuments)
    {
        $documents = array();

        $mUID   =   $this->getMerchantId($merchantDetails->merchant_primary_id);

        if (!empty($request->file('passport'))) {

            $passport   =   json_decode($merchantDocuments->passport_or_id);

            $this->_removeImageFromLocalDirectory($merchantDetails, $passport->passport, $mUID);
            $this->_storeImageInLocalDirectory($request->file('passport'), $merchantDetails, $mUID);

            $documents['passport_or_id'] = json_encode([
                'passport'          =>  ($request->file('passport'))->getClientOriginalName(),
                'status'            =>  'pending',
                'rejection_reason'  =>  null,
                'show_in_merchant'  =>  0
            ]);
        }

        if (!empty($request->file('company_legal_doc'))) {

            $legalDoc   =   json_decode($merchantDocuments->company_legal_doc);

            $this->_removeImageFromLocalDirectory($merchantDetails, $legalDoc->legal_doc, $mUID);
            $this->_storeImageInLocalDirectory($request->file('company_legal_doc'), $merchantDetails, $mUID);

            $documents['company_legal_doc'] = json_encode([
                'legal_doc'         =>  ($request->file('company_legal_doc'))->getClientOriginalName(),
                'status'            =>  'pending',
                'rejection_reason'  =>  null,
                'show_in_merchant'  =>  0
            ]);
        }

        if (!empty($request->file('company_bank_details'))) {

            $bankDetail =   json_decode($merchantDocuments->company_bank_details);

            $this->_removeImageFromLocalDirectory($merchantDetails, $bankDetail->bank_detail, $mUID);
            $this->_storeImageInLocalDirectory($request->file('company_bank_details'), $merchantDetails, $mUID);

            $documents['company_bank_details'] = json_encode([
                'bank_detail'       =>  ($request->file('company_bank_details'))->getClientOriginalName(),
                'status'            =>  'pending',
                'rejection_reason'  =>  null,
                'show_in_merchant'  =>  0
            ]);
        }

        return $documents;
    }

    public function accountVerificationInstructor()
    {
        return view('merchant.settings.account_verification_instructor.index');
    }

    public function saveAccountDetailsInstructor(Request $request)
    {
        $rules = [
            'first_name'            => 'required',
            'last_name'             => 'required',
            'dob'                   => 'required|date',
            'nationality'           => 'required',
            //'email'               => 'required'/*|email|unique:merchant.merchants*/,
            'certifications.*'      => 'required|filled',
            'years_of_experience'   => 'required|numeric',
            'total_number_dives'    => 'required|numeric',
            'spoken_languages'      => 'required',
            'phone'                 => 'required|numeric',
            'short_story'           => 'required',
            'pricing'               => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->except('password'));
        }

        $dataInstructor = $this->_prepareForInstructorTable($request);
        $dataInstructor['merchant_primary_id'] = $this->authUserId;
        Instructor::saveInstructor($dataInstructor);

        $request->session()->flash('success', 'Instructor created successfully.');
        return redirect()->route('scubaya::merchant::instructor', [Auth::id()]);
    }

    /*prepare data for instructor table*/
    protected function _prepareForInstructorTable($request)
    {
        $data = [
            'dob'               => date('Y-m-d', strtotime(str_replace('/', '-', $request->dob))),
            'nationality'       => $request->nationality,
            'certifications'    => json_encode(array_chunk($request->certifications, 4)),
            'years_experience'  => $request->years_of_experience,
            'total_dives'       => $request->total_number_dives,
            'spoken_languages'  => $request->spoken_languages,
            'facebook'          => $request->facebook,
            'twitter'           => $request->twitter,
            'instagram'         => $request->instagram,
            'phone'             => $request->phone,
            'own_website'       => $request->own_website,
            'short_story'       => $request->short_story,
            'pricing'           => $request->pricing,
        ];

        return $data;
    }

    public function getMerchantId($mid)
    {
        return User::where('id', $mid)->value('UID');
    }
}
