<?php

return [
    
/* Merchant Email Actions */
    "merchant"  =>  array(
        'merchant_account_verification'     =>  array(
            'name'          =>  'Account Verification',
            'variables'     =>  'confirmation_url'
        ),
        'role_verification'                 =>  array(
            'name'          =>  'Role Verification',
            'variables'     =>  'roles, merchant, confirmation_url, login_url'
        ),
        'password_reset'                    =>  array(
            'name'          =>  'Password Reset',
            'variables'     =>  'password_reset_url'
        ),
        'edit_course_booking'               =>  array(
            'name'          =>  'Edit Course Booking',
            'variables'     =>  'user, no_of_persons, course'
        ),
        'edit_product_booking'              =>  array(
            'name'          =>  'Edit Product Booking',
            'variables'     =>  'user, quantity, product'
        ),
        'edit_hotel_booking'                =>  array(
            'name'          =>  'Edit Hotel Booking',
            'variables'     =>  'user, no_of_persons, room_tariff, hotel'
        ),
        'update_course_booking_request'     =>  array(
            'name'          =>  'Update Course Booking Request',
            'variables'     =>  'user, no_of_persons, course'
        ),
        'update_product_booking_request'    =>  array(
            'name'          =>  'Update Product Booking Request',
            'variables'     =>  'user, quantity, product'
        ),
        'update_hotel_booking_request'      =>  array(
            'name'          =>  'Update Hotel Booking Request',
            'variables'     =>  'user, no_of_persons, room_tariff, hotel'
        ),
        'decline_course_booking'            =>  array(
            'name'          =>  'Decline Course Booking',
            'variables'     =>  'user, no_of_persons, course'
        ),
        'decline_product_booking'           =>  array(
            'name'          =>  'Decline Product Booking',
            'variables'     =>  'user, quantity, product'
        ),
        'decline_hotel_booking'             =>  array(
            'name'          =>  'Decline Hotel Booking',
            'variables'     =>  'user, no_of_persons, room_tariff, hotel'
        ),
        'update_course_booking'             =>  array(
            'name'          =>  'Update Course Booking',
            'variables'     =>  'course, no_of_persons'
        ),
        'update_product_booking'            =>  array(
            'name'          =>  'Update Product Booking',
            'variables'     =>  'product, quantity'
        ),
        'update_hotel_room_booking'         =>  array(
            'name'          =>  'Update Hotel Room Booking',
            'variables'     =>  'room, hotel, persons'
        ),
        'user_query'                        =>  array(
            'name'          =>  'User Query',
            'variables'     =>  'login_url'
        )
    ),

/* Admin Email Actions */
    "admin"  =>  array(
        'instructor_confirmation'   =>  array(
            'name'          =>  'Instructor Confirmation',
            'variables'     =>   ''
        ),
        'user_confirmation'         =>  array(
            'name'          =>  'User Confirmation',
            'variables'     =>  ''
        ),
        'merchant_account_disabled' =>  array(
            'name'          =>  'Merchant Account Disabled',
            'variables'     =>  ''
        )
    ),

/* User Email Actions */
    "user"  =>  array(
        'user_account_verification'  =>  array(
            'name'          =>  'Account Verification',
            'variables'     =>  'verification_url'
        )
    ),
];
