<?php
/* Role Id's */
define('MERCHANT', 1);
define('ADMIN', 2);
define('USER', 3);
define('MERCHANT_USER_ROLE', 11);
define('DIVE_MASTER', 111);
define('DIVE_GUIDE', 112);

define('MERCHANT_STATUS_NEW',           'new');
define('MERCHANT_STATUS_PENDING',       'pending');
define('MERCHANT_STATUS_APPROVED',      'approved');
define('MERCHANT_STATUS_REJECTED',      'rejected');
define('MERCHANT_STATUS_IN_PROCESS',    'in_process');
define('MERCHANT_STATUS_DISABLED',      'disabled');

define('MAX_NIGHTS', 365);

/*rating constants*/
define('MERCHANT_RATING_BAD','bad');
define('MERCHANT_RATING_GOOD','good');
define('MERCHANT_RATING_UNKNOWN','unknown');

/*screening constants*/
define('MERCHANT_SCREENING_COMPLETED','completed');
define('MERCHANT_SCREENING_PENDING','pending');

/* Product type */
define('RENTAL_PRODUCT', 1);
define('SELL_PRODUCT', 2);

/*user status*/
define('USER_STATUS_PENDING','pending');
define('USER_STATUS_APPROVED','approved');

/*User Privacy Settings Constants*/
define('PUBLICC','public');
define('ONLY_ME','only_me');
define('FRIENDS','friends');

/* Website type */
define('SHOP', 1);
define('DIVE_CENTER', 2);
define('LIVEBOARD', 3);
define('HOTEL', 4);
define('DESTINATION', 5);

/*checkout status*/
define('CHECKOUT_PENDING', 'pending');
define('CHECKOUT_COMPLETED','completed');
define('DIVE_CENTER_COURSE_PENDING', 'pending');

/* booking request */
define('NEW_BOOKING_REQUEST', 'new');
define('PENDING_BOOKING_REQUEST', 'pending');
define('CONFIRMED_BOOKING_REQUEST', 'confirmed');
define('COMPLETED_BOOKING_REQUEST', 'completed');
define('CANCELLED_BOOKING_REQUEST', 'cancelled');
define('EXPIRED_BOOKING_REQUEST', 'expired');

/* edit booking request */
define('CONFIRMED_EDIT_BOOKING_REQUEST', 'confirmed');
define('DECLINED_EDIT_BOOKING_REQUEST', 'declined');

define('PUBLISHED', 1);
define('UNPUBLISHED', 0);

define('IS', 1);
define('IS_NOT', 0);