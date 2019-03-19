<?php
return [
    'email_verification_subject' => 'Verify your email address',
    'email_verification_msg'     => <<<'TEXT'
                                    Thanks for creating an account.
                                    Please follow the link below to verify your email address
                                    <a href=":confirmation_url">:confirmation_url</a> .<br/>
TEXT
    ,
    'role_email_verification_subject' => 'Verify your email address',
    'role_email_verification_msg'     => <<<'TEXT'
                                            Dear User,
                                            <br />
                                            You have been added as <strong>:roles </strong>by <strong>:merchant</strong>.
                                            <br />
                                            <a href=":confirmation">Click here to verify your role</a> <br/>
                                            and if you have already verified your role then please login here : <br/>
                                            <a href=":login">Login Here</a> <br/>,
TEXT
    ,
    'password_reset_subject' => 'Reset Password',
    'password_reset_msg'     => <<<'TEXT'
                                    You are receiving this email because we received a password reset request for your account.
                                    Please follow the link below to reset your password
                                    <a href=":password_reset_url">:password_reset_url</a> .<br/>
TEXT
    ,
    'user_email_verification'           =>  'Verify your email address',
    'user_email_verification_message'   => <<<'TEXT'
                                            Thanks for signing up on Scubaya.com, now verify your email address by
                                            clicking <a href=":verification_url">Click Here</a> for smooth experience.
                                              
TEXT
    ,
    'edit_booking_subject'  =>  <<<'TEXT'
                                    Edit :item_type Booking
TEXT
    ,
    'edit_course_booking_message'  =>  <<<'TEXT'
                                        <strong>:user</strong> has requested to change <strong>no of persons</strong> to <strong>:no_of_persons</strong> for <strong>:course</strong>.                              
TEXT
    ,
    'edit_product_booking_message'  =>  <<<'TEXT'
                                        <strong>:user</strong> has requested to change <strong>quantity</strong> to <strong>:quantity</strong> for <strong>:product</strong>.
TEXT
    ,
    'edit_hotel_booking_message'    =>  <<<'TEXT'
                                        <strong>:user</strong> has requested to change <strong>no of persons</strong> to <strong>:no_of_persons</strong> for <strong>:room_tariff </strong> of :hotel.

TEXT
    ,
    'update_booking_subject'        =>  <<<'TEXT'
                                        Updated :item_type Booking
TEXT
    ,
    'update_course_booking_request_message' =>  <<<'TEXT'
                                        Your request to change <strong> no of persons </strong> to <strong>:no_of_persons</strong> for <strong>:course</strong> has been confirmed.
TEXT
    ,
    'update_product_booking_request_message' =>  <<<'TEXT'
                                        Your request to change <strong> quantity </strong> to <strong>:quantity</strong> for <strong>:product</strong> has been confirmed.
TEXT
    ,
    'update_hotel_booking_request_message' =>  <<<'TEXT'
                                        Your request to change <strong> no of persons </strong> to <strong>:no_of_persons</strong> for <strong>:room_tariff</strong> of :hotel has been confirmed.
TEXT
    ,
    'decline_booking_subject'        =>  <<<'TEXT'
                                        Decline :item_type Booking
TEXT
    ,
    'decline_course_booking_message' =>  <<<'TEXT'
                                         Your request to change <strong> no of persons </strong> to <strong>:no_of_persons</strong> for <strong>:course</strong> has been declined.
TEXT
    ,
    'decline_product_booking_message' =>  <<<'TEXT'
                                          Your request to change <strong> quantity </strong> to <strong>:quantity</strong> for <strong>:product</strong> has been declined.
TEXT
    ,
    'decline_hotel_booking_message' =>  <<<'TEXT'
                                        Your request to change <strong> no of persons </strong> to <strong>:no_of_persons</strong> for <strong>:room_tariff</strong> of :hotel has been declined.
TEXT
    ,
    'update_course_booking_message' =>  <<<'TEXT'
                                        Your <strong>:course</strong> booking for <strong>:no_of_persons</strong> number of persons has been updated.
TEXT
    ,
    'update_product_booking_message' =>  <<<'TEXT'
                                        Your <strong>:product</strong> booking for <strong>:quantity</strong> quantity has been updated.
TEXT
    ,
    'update_hotel_room_booking_message' =>  <<<'TEXT'
                                            Your <strong>:room</strong> booking for <strong>:hotel</strong> to change number of persons to :persons has been updated.
TEXT
    ,
    'user_query_subject' =>  'User Contact Request'
    ,
    'user_query_message' =>  <<<'TEXT'
                                You have a contact request on Scubaya.com. Below is a link to claim your account now.
                                <a href=":merchant_login_url"><button class="btn btn-primary" name="login">Click here to claim</button></a>
TEXT
    ,
    'account_disabled'  =>  'Your account is disabled'
    ,
    'account_disabled_message'  =>  <<<'TEXT'
                                    Your account is currently disabled, please contact us for more information.
TEXT

];
