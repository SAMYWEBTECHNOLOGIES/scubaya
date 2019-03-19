<?php
return [
    'dive_log_verification'  =>  <<<'TEXT'
                                    Your dive buddy :name has requested to verify his dive.
                                    <br/>
                                    <button type="button"
                                            data-@#74=":log_id"
                                            class="btn btn-primary verify-dive-button"
                                            data-toggle="modal"
                                            data-target="#myModal">Verify</button>
                                    <button class="btn unverify-button">No Idea!</button>  
TEXT
    ,
    'role_verification'     =>  <<<'TEXT'
                                    You are invited to become :roles of :merchant.
                                    <br/>
                                    <a href=":url">
                                        <button type="button" class="btn btn-primary">Click here to verify</button>
                                    </a> 
TEXT
    ,

    'user_contact_query'    =>  'You have one contact request from diver :diver. '
];
