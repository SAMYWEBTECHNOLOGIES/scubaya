<?php

namespace App\Http\Controllers\Merchant\Shop;

use App\Scubaya\Helpers\CompressImage;
use App\Scubaya\model\Affiliations;
use App\Scubaya\model\Boat;
use App\Scubaya\model\Courses;
use App\Scubaya\model\Instructor;
use App\Scubaya\model\ManageDiveCenter;
use App\Scubaya\model\MerchantUsersRoles;
use App\Scubaya\model\Products;
use App\Scubaya\model\RoomPricingSettings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;

class CourseController extends Controller
{
    private $authUserId;

    private $noOfCoursesPerPage   =   15;

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

    public function index(Request $request)
    {
        $courses        =   Courses::where('merchant_key', '=', $this->authUserId)
                                    ->where('shop_id', $request->shop_id)
                                    ->paginate($this->noOfCoursesPerPage);

        $sno            =   (($courses->currentPage() - 1) * $this->noOfCoursesPerPage) + 1;

        return view('merchant.shop.courses.index')
            ->with('courses', $courses)
            ->with('sno', $sno)
            ->with('shopId', $request->shop_id);
    }

    protected function _prepareData($request, $file, $galleryImageFiles)
    {
        $courses    =   new \stdClass();

        $courses->merchant_key          =   $this->authUserId;
        $courses->shop_id               =   $request->shop_id;
        $courses->dive_center           =   json_encode($request->get('dive_centers'));
        $courses->course_name           =   $request->get('course_name');
        $courses->affiliates            =   json_encode($request->get('course_affiliates'));
        $courses->instructors           =   json_encode($request->get('instructors'));
        $courses->boats                 =   json_encode($request->get('boats'));
        $courses->course_start_date     =   $request->get('course_start_date');
        $courses->course_end_date       =   $request->get('course_end_date');


        if($file){
            $courses->image             =   str_replace(' ', '-', $file->getClientOriginalName());
        }

        if($galleryImageFiles && count($galleryImageFiles) > 0){
            $images =   array();

            foreach($galleryImageFiles as $file){
                array_push($images, $file->getClientOriginalName());
            }
            $courses->gallery  =   json_encode($images);
        }

        $courses->course_days           =   json_encode([
            'no_of_days'        =>  $request->get('course_no_of_days'),
            'course_repeat'     =>  $request->get('course_repeat'),
            'course_start_day'  =>  json_encode($request->get('course_start_day'))
        ]);

        $courses->course_pricing        =   json_encode([
            'min_people'    =>  $request->get('min_people_for_course'),
            'max_people'    =>  $request->get('max_people_for_course'),
            'min_age'       =>  $request->get('course_min_age'),
            'price'         =>  $request->get('course_price')
        ]);

        $courses->location              =   json_encode([
            'address'       =>  $request->get('address'),
            'lat'           =>  $request->get('latitude'),
            'long'          =>  $request->get('longitude')
        ]);

        $courses->products      =     json_encode($request->get('product_in_course'));
        $courses->description   =     $request->get('course_description');
        $courses->cancellation_detail=$request->get('cancellation_detail');

        return $courses;
    }

    public function save(Request $request)
    {
        if($request->isMethod('post')){

            $this->validate($request, [
                /*'course_name'           =>  'required',
                'course_affiliates'     =>  'required|array|min:1',
                'course_no_of_days'     =>  'required',
                'course_start_date'     =>  'required',
                'course_end_date'       =>  'required',
                'course_start_day'      =>  'required|array|min:1',
                'min_people_for_course' =>  'required',
                'max_people_for_course' =>  'required',
                'course_min_age'        =>  'required',
                'course_price'          =>  'required',
                'cancellation_detail'   =>  'required',
                'address'               =>  'required',
                'latitude'              =>  'required',
                'longitude'             =>  'required',
                'course_image'          =>  'required|image|mimes:jpg,png,jpeg,gif,svg,bmp|max:2048',
                'gallery'               =>  'image_upload_count',
                'gallery.*'             =>  'image|mimes:jpeg,png,jpg,gif,svg|max:5048',*/
            ]);

            $file               =   $request->file('course_image');
            $galleryImageFiles  =   $request->file('gallery');

            $course     =   Courses::saveCourses($this->_prepareData($request, $file, $galleryImageFiles));

            // save course image
            if($file) {
                $this->_saveImageInLocalDirectory($file, $course);
            }

            //save images for gallery
            if($galleryImageFiles){
                $this->_saveImagesForGalleryInLocalDirectory($galleryImageFiles,$course);
            }

            return Redirect::to(route('scubaya::merchant::shop::courses', [Auth::id(), $request->shop_id]));
        }

        $courseAffiliations =   Affiliations::where('active', 1)->get();

        $diveCenters        =   ManageDiveCenter::where('merchant_key', $this->authUserId)
                                                ->where('status', PUBLISHED)
                                                ->get();

        $products           =   Products::where('merchant_key', $this->authUserId)
                                        ->where('shop_id', $request->shop_id)
                                        ->where('product_status', '=', 1)
                                        ->where('incl_in_course', '=',1)
                                        ->get();

        $merchantCurrency   =   RoomPricingSettings::where('merchant_primary_id', $this->authUserId)
                                        ->value('currency');

        return view('merchant.shop.courses.create')
            ->with('courseAffiliations', $courseAffiliations)
            ->with('diveCenters', $diveCenters)
            ->with('products', $products)
            ->with('merchantCurrency', json_decode($merchantCurrency))
            ->with('shopId', $request->shop_id);
    }

    protected function _getInstructors($diveCenterId)
    {
        $instructors = Instructor::join('users','users.id','=','instructors.instructor_key')
            ->join('merchant_users_x_roles','instructors.instructor_key','=','merchant_users_x_roles.user_id')
            ->where('dive_center_id', $diveCenterId)
            ->get();

        return !empty($instructors) ? $instructors : null;
    }

    public function update(Request $request)
    {
        if($request->isMethod('post')) {

                $this->validate($request, [
                /*'course_name'           =>  'required',
                'course_affiliates'     =>  'required|array|min:1',
                'course_no_of_days'     =>  'required',
                'course_start_day'      =>  'required|array|min:1',
                'min_people_for_course' =>  'required',
                'max_people_for_course' =>  'required',
                'course_min_age'        =>  'required',
                'cancellation_detail'   =>  'required',
                'course_price'          =>  'required',
                'course_image'          =>  'image|mimes:jpg,png,jpeg,gif,svg,bmp|max:2048',
                'gallery'               =>  'image_upload_count',
                'gallery.*'             =>  'image|mimes:jpeg,png,jpg,gif,svg|max:5048'*/
            ]);

            $file               =   $request->file('course_image');
            $galleryImageFiles  =   $request->file('gallery');

            $course     =   Courses::updateCourses($request->course_id, $this->_prepareData($request, $file, $galleryImageFiles));

            // save product image
            if($file){
                $this->_removeImageFromLocalDirectory($course);
                $this->_saveImageInLocalDirectory($file, $course);
            }

            //save images for gallery
            if($galleryImageFiles){
                // firstly remove the existing images form gallery then save the new ones
                $this->_removeImagesFromGallery($course);
                $this->_saveImagesForGalleryInLocalDirectory($galleryImageFiles, $course);
            }

            return Redirect::to(route('scubaya::merchant::shop::courses', [Auth::id(), $request->shop_id]));
        }

        $course             =   Courses::find($request->course_id);

        $courseAffiliations =   Affiliations::where('active', 1)->get();

        $products           =   Products::where('merchant_key', $this->authUserId)
                                        ->where('shop_id', $request->shop_id)
                                        ->where('product_status', '=', 1)
                                        ->where('incl_in_course', '=',1)
                                        ->get();

        $merchantCurrency   =   RoomPricingSettings::where('merchant_primary_id', $this->authUserId)
                                        ->value('currency');

        $diveCenters        =   ManageDiveCenter::where('merchant_key', $this->authUserId)
                                        ->where('status', PUBLISHED)
                                        ->get();

        return view('merchant.shop.courses.edit')
            ->with('course', $course)
            ->with('courseAffiliations', $courseAffiliations)
            ->with('products', $products)
            ->with('diveCenters', $diveCenters)
            ->with('merchantCurrency', json_decode($merchantCurrency))
            ->with('diveCenterId', $request->center_id)
            ->with('shopId', $request->shop_id);
    }

    public function delete(Request $request)
    {
        $courses   =   Courses::find($request->course_id);

        $this->_removeImageFromLocalDirectory($courses);

        Courses::destroy($request->course_id);

        return Redirect::to(route('scubaya::merchant::shop::courses', [Auth::id(), $request->shop_id]));
    }

    public function getBoatsInstructors(Request $request)
    {
        $instructors        =   array();

        $boats              =   Boat::whereIn('dive_center_id', json_decode($request->centers))
                                ->where('is_boat_active', 1)
                                ->get(['name', 'id']);

        $instructorInfo     =   Instructor::join('users','users.id','=','instructors.instructor_key')
                                ->join('merchant_users_x_roles','instructors.instructor_key','=','merchant_users_x_roles.user_id')
                                ->whereIn('dive_center_id', json_decode($request->centers))
                                ->get(['instructors.id', 'users.first_name', 'users.last_name', 'merchant_users_x_roles.group_id']);

        if($instructorInfo) {
            foreach ($instructorInfo as $instructor) {
                $Instructor = (array)json_decode($instructor->group_id);

                foreach ($Instructor as $key => $value) {
                    if($value->confirmed) {
                        $instructors[]    =   $instructor;
                    }
                }
            }
        }

        return [
            'boats'         =>  count($boats) ? $boats : [],
            'instructors'   =>  count($instructors) ? $instructors : []
        ];
    }

    protected function _saveImageInLocalDirectory($file, $course)
    {
        $path     =   public_path(). '/assets/images/scubaya/shop/courses/'.$course->merchant_key.'/';
        File::makeDirectory($path, 0777, true, true);

        $filename   =   $course->id.'-'.$course->image;
        $compressImage = new CompressImage();
        $compressImage->compressImage($file,$path,str_replace(' ', '-', $filename));
    }

    protected function _removeImageFromLocalDirectory($course)
    {
        $path       =   public_path(). '/assets/images/scubaya/shop/courses/'.$course->merchant_key.'/'.$course->id.'*';
        $log_files  =   File::glob($path);
        File::delete($log_files);
    }

    protected function _saveImagesForGalleryInLocalDirectory($galleryImageFiles, $course)
    {
        $path     =   public_path(). '/assets/images/scubaya/shop/courses/gallery/'.$course->merchant_key.'/'.'course-'.$course->id;
        File::makeDirectory($path, 0777, true, true);

        $compressImage = new CompressImage();

        foreach($galleryImageFiles as $file){
            $filename   =   $file->getClientOriginalName();
            $compressImage->compressImage($file,$path,str_replace(' ', '-', $filename));
        }
    }

    /* delete images form gallery */
    protected function _removeImagesFromGallery($course)
    {
        $path     =   public_path(). '/assets/images/scubaya/shop/courses/gallery/'.$course->merchant_key.'/'.'course-'.$course->id;
        File::deleteDirectory($path);
    }
}
