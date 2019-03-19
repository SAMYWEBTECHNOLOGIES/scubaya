<?php

namespace App\Http\Controllers\Front;

use App\Scubaya\model\Courses;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $courses =  Courses::all(['id','course_name','location'])->toArray();
        $courses_f = json_encode($this->_formatCourseData($courses));
        return view('front.home.courses')->with('courses', $courses_f);
    }

    public function _formatCourseData($data){
        $result = array();
        $final_result = array();
        foreach($data as  $info){
            $result['id'] = $info['id'];
            $result['course_name'] = $info['course_name'];
            $location_data = (array)json_decode($info['location']);
            $result['location_address'] = $location_data['address'];

            array_push($final_result,$result);
        }
        return $final_result;
    }
}
