<?php
/**
 * Created by PhpStorm.
 * User: prakhar
 * Date: 18/7/17
 * Time: 1:03 PM
 */
return [
    "merchants"         =>  'merchants',
    "admin"             =>  'ad',
    "user"              =>  'user',

    /* dive center affiliations */
    "affiliations"  =>  array('PADI','CMAS', 'SSI', 'SSA', 'NAUI', 'FFESSM', 'ANMP', 'BSAC', 'FEDAS'),

    "whitelist_domain"   =>  array(env('APP_URL')),

    "max_people_in_room"            => 100,
    "min_people_in_room"            => 100,
    'min_rooms_already_selected'    => 100,
    'max_rooms_already_selected'    => 100,
    "max_people_for_course"         => 100,
    "min_people_for_course"         => 100,
    "no_of_products_in_course"      => 100,
    "max_passengers_in_boat"        => 100,

    "menus"             =>  [
        'Bookings'  =>  ['All Bookings','New Booking','Icall Feed','Icall Import','Bookings Settings'],
        'Reports'   =>  ['Charts','Arriving Today','Departing Today','Cleaning Schedule','Percentage Booked','Report Settings'],
        'Invoices'  =>  ['All Invoices','SCBY Invoices','Guest Invoices','Open Invoices','Invoice Settings'],
        'Guest'     =>  ['All Guests','New Guest','Guest Types','Guest Settings'],
    ],

    "merchant_default_menus"    =>  array('settings::account_verification', 'settings::account_details', 'settings::account_configuration')
];
