<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Merchant\Settings\AccountVerificationController;
use App\Scubaya\model\Affiliations;
use App\Scubaya\model\Currency;
use App\Scubaya\model\Instructor;
use App\Scubaya\model\Group;
use App\Scubaya\model\Merchant;
use App\Scubaya\model\MerchantDetails;
use App\Scubaya\model\MerchantDocumentsMapper;
use App\Scubaya\model\MerchantPolicies;
use App\Scubaya\model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \App\Scubaya\model\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    private $paginate_no    =   10;
    protected static $message;

    public function __construct()
    {
        $this->middleware('auth');
        static::$message    =   [
            'required'               =>          'The :attribute field is required for creating instructor.',
            'email.required'         =>          'Email field is necessary for creating instructor.',
            'certifications.*'       =>          'The certification needs to be completely filled.',
        ];
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function addAdmin(Request $request)
    {
        $this->validate($request,[
           'name'       =>      'required',
           'email'      =>      'required|email|unique:users,email,NULL,id,is_admin,'.IS,
           'password'   =>      'required|min:6|confirmed',
        ]);

        $dataUser       =   $this->_prepareDataUserTable($request);
        $userId         =   User::saveUser($dataUser);

        $dataAdmin      =   $this->_prepareDataAdminTable($request,$userId->id);
        Admin::saveAdmin($dataAdmin);

        $request->session()->flash('success','Admin is created successfully');
        return redirect()->route('scubaya::admin::manage_admins');
    }

    public function manageAdmins()
    {
        $query                      =    Admin::query();
        // pagination
        $admins                     =    $query->where('users.is_admin',IS)
                                               ->join('users','admins.admin_key','users.id')
                                               ->paginate($this->paginate_no);

        $sno                        =    (($admins->CurrentPage() - 1) * $this->paginate_no) + 1;

        return view('admin.manage_admins',['admins'=>$admins,'sno'=>$sno]);
    }

    public function blockAdmin()
    {
        $id         =   Input::get('AdminId');
        $state      =   Admin::where('admin_key',$id)->first();

        Admin::where('admin_key',$id)->update(['block'=>!$state->block]);
        return response()->json(['status' => 'Admin deleted successfully.']);
    }

    public function deleteAdmin($id)
    {
        User::destroy($id);

        Admin::where('admin_key',$id)->delete();
        return redirect()->route('scubaya::admin::manage_admins');
    }

    public function merchantsAccounts(Request $request)
    {
        $query                      =   Merchant::query();

        $rating                     =   $request->rating;
        $screening                  =   $request->screening;
        $status                     =   $request->status;

        if(!(empty($rating) && empty($screening) && empty($status))){
            if(!empty($rating)){
                $query->where('merchant_details.rating',$rating);
            }

            if(!empty($screening)){
                $query->where('merchant_details.screening',$screening);
            }

            if(!empty($status)){
                $query->where('merchant_details.status',$status);
            }
        }

        $data           =    $query->leftJoin('merchant_details','merchants.merchant_key','merchant_details.merchant_primary_id')
                                   ->join('users','merchants.merchant_key','=','users.id')
                                   ->select('users.email','merchants.*','merchant_details.*')
                                   ->paginate($this->paginate_no);

        $sno            =    (($data->CurrentPage() - 1) * $this->paginate_no) + 1;

        $groups         =    Group::where('parent_id',0)->get();

        return view('admin.merchants_accounts')
            ->with('data',$data)
            ->with('groups',$groups)
            ->with('sno',$sno);
    }

    public function deleteMerchant($id)
    {
        Merchant::where('merchant_key',$id)->delete();
        MerchantDetails::where('merchant_primary_id',$id)->delete();
        User::destroy($id);

        return redirect()->route('scubaya::admin::merchants_accounts');
    }

    public function addMerchant(Request $request)
    {
        if($request->isMethod('post')){
            $validator = Validator::make($request->all(), [
                'merchant_email' => 'required|email|unique:users,email,NULL,id,is_merchant,'.IS,
            ]);

            if ($validator->fails()){
                return redirect()->back()
                    ->withErrors($validator,'add_merchant')
                    ->withInput();
            }

            // save merchant
            $merchantData   =   $this->prepareMerchantData($request);
            $user           =   User::saveUser($merchantData);

            // save merchant profile details
            Merchant::saveMerchant($this->_prepareMerchantDetails($user->id));

            $merchant_details_data   =   [
                'merchant_primary_id'       =>  $user->id,
                'company_type'              =>  $request->company_name,
                'address'                   =>  $request->address,
                'city'                      =>  $request->city,
            ];

            MerchantDetails::saveMerchantDetails($merchant_details_data);

            $request->session()->flash('success','Merchant with email- '.$request->merchant_email.' created successfully.');
            return redirect()->route('scubaya::admin::add_merchant');
        }
        return view('admin.add_merchant');
    }

    public function prepareMerchantData($request)
    {
        $merchantData   =   [
            'UID'               =>  Merchant::merchantId(),
            'first_name'        =>  'scubaya',
            'last_name'         =>  'merchant',
            'email'             =>  $request->get('merchant_email'),
            'password'          =>  Hash::make('password'),
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

    public function userGroups()
    {
        //query to get the data
        $query                      =    Group::query();
        //paginate
        $data                       =    $query->paginate($this->paginate_no);

        $sno                        =    (($data->CurrentPage() - 1) * $this->paginate_no) + 1;

        $groups                     =    Group::where('parent_id',0)->get();

        $menus                      =    DB::table('merchant_menus')->where('parent_id',0)->get();
        return view('admin.user_groups')
            ->with('groups',$groups)
            ->with('data',$data)
            ->with('menus',$menus)
            ->with('sno',$sno);
    }

    public function createGroup(Request $request)
    {
        /* $validator  =   Validator::make($request->all(),[
            'group_name' => 'required|unique:groups,name',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator, 'create');
        }*/

        $data   =   [
            'name'              =>  $request->group_name,
            'parent_id'         =>  $request->group,
            'menu_ids'          =>  json_encode($request->menus)
        ];

        Group::addGroup($data);

        $request->session()->flash('success','Group created successfully.');
        return redirect()->route('scubaya::admin::user_groups');
    }

    public function deleteGroup($id)
    {
        $group  =   new Group();
        $group->updateGroupIdInMerchant($id)

                ->destroy($id);
        $group->where('parent_id',$id)->update(['parent_id'=>0]);

        return redirect()->route('scubaya::admin::user_groups');
    }

    public function editGroup(Request $request)
    {
        $id     =   $request->id;
        $group  =   Group::where('id',$id)->first();

        $check  =   ($request->group_name    ==  $group->name);

        if(!$check){
            $this->validate($request,[
                'group_name' => 'required|unique:groups,name,'.$request->old_group_name,
            ]);
        }
        $data   =   [
            'name'          =>  $request->group_name,
            'menu_ids'      =>  ($request->menus) ? json_encode($request->menus) : null
        ];

        if(!($request->edit_group_options == $group->id)){
            $data['parent_id']      =  $request->edit_group_options;
        }

        $request->session()->flash('success',$request->group_name.' Updated successfully.');

        Group::where('id',$id)->update($data);
        return redirect()->route('scubaya::admin::user_groups');
    }

    public function menus()
    {
        $query                      =   Menu::query();
        //paginate
        $data                       =   $query->paginate($this->paginate_no);

        $sno                        =   (($data->CurrentPage() - 1) * $this->paginate_no) + 1;
        $menuData                   =   Menu::where('parent_id',0)->get();
        $groups                     =   Group::where('parent_id',0)->get();
        return view('admin.menus',
            [
                'menuData'  =>  $menuData,
                'data'      =>  $data,
                'groups'    =>  $groups,
                'sno'       =>  $sno,
            ]);
    }

    public function createMenu(Request $request)
    {
        $validator  =   Validator::make($request->all(),[
            'menu_name'      =>  'required|unique:menus_merchant,name',
            'menu'           =>  'required',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator,'create');
        }

        $data   =   [
            'name'              =>  $request->menu_name,
            'parent_id'         =>  $request->menu,
            'link'              =>  $request->link,
            'group_ids'         =>  json_encode($request->group)
        ];

        $group_ids  =   $request->group;

        Menu::addMenu($data,$group_ids);

        $request->session()->flash('success','Menu created successfully.');
        return redirect()->route('scubaya::admin::menus');
    }

    public function deleteMenu($id)
    {
        $menu   =   new Menu();
        $menu->updateMenuIdsInGroupDelete($id)->destroy($id);
        $menu->where('parent_id',$id)->delete();

        return redirect()->route('scubaya::admin::menus');
    }

    public function editMenu(Request $request)
    {
        $id     =   $request->id;
        $menu   =   Menu::where('id',$id)->first();

        $check  =   $request->menu_name    ==  $menu->name;

        if(!$check){
            $validate   =   Validator::make($request->all(),[
                'menu_name' => 'required|unique:admin.menus_merchant,name',
            ]);
            if($validate->fails()){
                return redirect()->back()->withErrors($validate,'edit');
            }
        }

        $data   =   [
            'name'          =>  $request->menu_name,
            'link'          =>  $request->menu_link,
            'group_ids'     =>  json_encode($request->edit_group_options)
        ];

        if(!($request->edit_menu_options == $menu->id)){
            $data['parent_id']      =  $request->edit_menu_options;
        }

        Menu::where('id',$id)->update($data);
        Menu::updateMenuIdsInGroup($request->edit_group_options,$id);
        return redirect()->route('scubaya::admin::menus');
    }

    public function merchantPolicies()
    {
        $data   =   MerchantPolicies::all();
        return view('admin.merchant_policies',['data'=>$data]);
    }

    public function createMerchantPolicy(Request $request)
    {
        $validate   =   Validator::make($request->all(),[
            'policy_name'           =>  'required',
            'merchant_select'       =>  'required'
        ]);
        if($validate->fails()){
            return redirect()->back()->withErrors($validate,'create');
        }

        $data   =   [
            /*'merchant_primary_id'   =>  Auth::guard('merchant')->user()->id,*/
            'name'                  =>  $request->policy_name,
            'published'             =>  $request->get('published',0),
            'merchant'              =>  $request->merchant_select,
        ];

        MerchantPolicies::savePolicy($data);
        $request->session()->flash('success','Policy created successfully.');
        return redirect()->route('scubaya::admin::merchants_policies');
    }

    public function deleteMerchantPolicy($id)
    {
        MerchantPolicies::destroy($id);
        return redirect()->route('scubaya::admin::merchants_policies');
    }

    public function editMerchantPolicy(Request $request)
    {
        $id                 =   $request->id;
        $merchant_policy    =   MerchantPolicies::where('id',$id)->first();

        $check              =   $request->policy_name    ==  $merchant_policy->name;

        if(!$check){
            $validate   =   Validator::make($request->all(),[
                'policy_name' => 'sometimes|required',
            ]);
            if($validate->fails()){
                return redirect()->back()->withErrors($validate,'edit');
            }
        }

        $data   =   [
            /*'merchant_primary_id'   =>  Auth::guard('merchant')->user()->id,*/
            'name'                  =>  $request->policy_name,
            'published'             =>  $request->get('published',0),
            'merchant'  =>  $request->merchant_select
        ];

        MerchantPolicies::where('id',$id)->update($data);
        $request->session()->flash('success','Policy updated successfully.');
        return redirect()->route('scubaya::admin::merchants_policies');
    }

    public function currencies()
    {
        $currencies     =   Currency::all();
        return view('admin.currencies',['currencies'    =>  $currencies]);
    }

    public function createCurrency(Request $request)
    {
        $this->validate($request,[
           'currency'   =>  'required|unique:currencies,name'
        ]);

        $data   =   [
          'name'    =>  $request->currency,
        ];
        /*save the currency*/
        Currency::saveCurrency($data);

        $request->session()->flash('success','Currency created successfully.');
        return redirect()->route('scubaya::admin::currencies');
    }

    public function deleteCurrency(Request $request)
    {
        Currency::destroy($request->id);
        return redirect()->route('scubaya::admin::currencies');
    }

    public function createInstructor(Request $request)
    {
        if($request->isMethod('post')){
            $instructor         =   new Instructor();
            $rules              =   [
                'first_name'                        =>      'required',
                'last_name'                         =>      'required',
                'dob'                               =>      'required|date',
                'nationality'                       =>      'required',
                'email'                             =>      'required|email|unique:users',
                'certifications.*'                  =>      'required|filled',
                'years_of_experience'               =>      'required|numeric',
                'total_number_dives'                =>      'required|numeric',
                'spoken_languages'                  =>      'required',
                'phone'                             =>      'required|numeric',
                'short_story'                       =>      'required',
                'pricing'                           =>      'required',
                'connect_to_merchant'               =>      'required',
            ];

            $validator              =   Validator::make($request->all(),$rules,static::$message);
            if($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput($request->except('password'));
            }

            /*prepare data for save in database*/
            $dataMerchant                               =   $this->_prepareForMerchantTable($request);

            $dataMerchant['confirmation_code']          =   str_random(30);
            $dataMerchant['account_status']             =   MERCHANT_STATUS_PENDING;
            $dataMerchant['is_merchant']                =   IS;

            //saving instructor in merchant table and send confirmation mail
            $user                                       =   User::saveInstructorMerchant($dataMerchant)->sendConfirmationMail(route('scubaya::merchant::verify_instructor', ['__id__','__confirmation_code__']));

            $data_merchant          =   [
                'merchant_key'      =>  $user->id,
                'role_id'           =>  INSTRUCTOR,
            ];
            /*save instructor in merchant table*/
            $merchant               =   Merchant::saveMerchant($data_merchant);

            // saving data in instructor table
            $dataInstructor                             =   $this->_prepareForInstructorTable($request);
            $dataInstructor['merchant_primary_id']      =   $merchant->id;
            $dataInstructor['merchant_ids']             =   json_encode($request->connect_to_merchant);
            $instructor->saveInstructor($dataInstructor)->insertInstructorId($request);

            $request->session()->flash('success','Instructor created successfully.');
            return redirect()->route('scubaya::admin::instructors');
        }
        $query          =   Merchant::query();
        $merchants      =   $query->where('merchants.is_merchant',IS)
                                  ->join('users','merchants.merchant_key','=','users.id')
                                  ->select('users.email','merchants.id')
                                  ->get();
        return view('admin.create_instructor',['merchants'  =>  $merchants]);
    }

    public function deleteInstructor(Request $request)
    {
        $merchant_primary_id    =   Instructor::find($request->id)->merchant_primary_id;

        Instructor::destroy($request->id);
        Merchant::destroy($merchant_primary_id);

        $instructor             =   new Instructor();
        $instructor_field       =   $instructor->find($request->id,['merchant_primary_id','merchant_ids']);
        /*remove from instructor tabel*/
        $instructor->destroy($request->id);
        /*remove from merchant table*/
        Merchant::destroy($instructor_field->merchant_primary_id);
        /*remove ids from merchant from instructor_ids column*/
        $instructor->removeInstructorIdsFromMerchant($instructor_field->merchant_ids,$request->id);

        $request->session()->flash('success','Instructed deleted successfully');
        return redirect()->route('scubaya::admin::instructors');
    }

    public function instructors()
    {
        $query                      =     Merchant::query();
        // pagination
        $instructors                =    $query->where('merchants.is_merchant_user',IS)
                                               ->join('users','merchants.merchant_key','users.id')
                                               ->select('users.first_name','users.last_name','users.confirmed','users.email','merchants.id')
                                               ->paginate(10);

        // get the current page no of pagination so that we can send correct $sno
        $get_current_page           =    $instructors->CurrentPage();
        $sno                        =    (($get_current_page - 1) * 10) + 1;

        return view('admin.instructors.index',['instructors'=>$instructors,'sno'=>$sno]);
    }

    public function affiliations()
    {
        $affiliations   =   Affiliations::all();
        return view('admin.affliations',['affiliations'=>$affiliations]);
    }

    public function createAffiliation(Request $request)
    {
        $validate   =   Validator::make($request->all(),[
            'affiliation_name'  =>  'required|unique:merchant.affiliations,affiliation'
        ]);

        if($validate->fails()){
            return redirect()->back()->withErrors($validate,'create');
        }

        $data       =   [
            'affiliation'   =>  $request->affiliation_name,
        ];
        Affiliations::saveAffiliation($data);

        $request->session()->flash('success','Affiliation created successfully');
        return redirect()->route('scubaya::admin::affiliations');
    }

    public function deleteAffiliation(Request $request)
    {
        Affiliations::destroy($request->id);
        return redirect()->route('scubaya::admin::affiliations');
    }

    public function editAffiliation(Request $request)
    {
        $validate   =   Validator::make($request->all(),[
            'edit_affiliation_name'  =>  'required|unique:merchant.affiliations,affiliation,'.$request->old_affiliation_name
        ]);

        if($validate->fails()){
            return redirect()->back()->withErrors($validate,'edit')->withInput();
        }

        $data       =   [
            'affiliation'   =>  $request->edit_affiliation_name,
        ];

        Affiliations::where('id',$request->id)->update($data);

        $request->session()->flash('success','Affiliation updated successfully');
        return redirect()->route('scubaya::admin::affiliations');
    }

    public function _prepareDataAdminTable($request,$admin_key)
    {
        $data   =   [
            'name'          =>  $request->name,
            'title'         =>  $request->title,
            'admin_key'     =>  $admin_key,
        ];
        return $data;
    }

    protected function _prepareDataUserTable($request)
    {
        $data   =   [
            'UID'       =>  Admin::adminId(),
            'email'     =>  $request->email,
            'password'  =>  bcrypt($request->password),
            'is_admin'  =>  IS,
        ];
        return $data;
    }

    /*prepare data for instructor table*/
    protected function _prepareForInstructorTable($request)
    {
        $data                   =   [
            'dob'                           =>      date('Y-m-d',strtotime(str_replace('/','-',$request->dob))),
            'nationality'                   =>      $request->nationality,
            'certifications'                =>      json_encode(array_chunk($request->certifications,4)),
            'years_experience'              =>      $request->years_of_experience,
            'total_dives'                   =>      $request->total_number_dives,
            'spoken_languages'              =>      $request->spoken_languages,
            'facebook'                      =>      $request->facebook,
            'twitter'                       =>      $request->twitter,
            'instagram'                     =>      $request->instagram,
            'phone'                         =>      $request->phone,
            'own_website'                   =>      $request->own_website,
            'short_story'                   =>      $request->short_story,
            'pricing'                       =>      $request->pricing,
        ];

        return $data;
    }

    protected function _prepareForMerchantTable($request)
    {
        $data                   =   [
            'UID'               =>      User::userId(),
            'first_name'        =>      $request->first_name,
            'last_name'         =>      $request->last_name,
            'email'             =>      $request->email,
        ];

        return $data;
    }
}
