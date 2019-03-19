<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Courses extends Model
{
    use Notifiable;

    protected $table        =   'courses';

    protected $fillable     =   [
        'course_name', 'affiliates', 'instructors', 'boats', 'course_start_date', 'course_end_date', 'course_days', 'course_pricing', 'location'
    ];

    public static function saveCourses($data)
    {
        $courses  =   new Courses();

        foreach($data as $key => $value){
            $courses->$key    =   $value;
        }

        $courses->save();

        return $courses;
    }

    public static function updateCourses($id, $data)
    {
        $courses   =   Courses::find($id);

        foreach($data as $key => $value){
            $courses->$key =   $value;
        }

        $courses->update();

        return $courses;
    }
}
