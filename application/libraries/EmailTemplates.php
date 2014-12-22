<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class EmailTemplates
    {

        public function registerEmail($full_name, $confirmation_link)
        {
            $str = '<body style="margin: 0;padding: 0; font-family: helvetica;width: 86%;">
                            <div style="display: inline-block;width: 100%; padding: 10px 20px;">
                                <h1 style="margin: 0;">' . SITE_NAME . '</h1>
                                <h3>Confirm your email address</h3>
                                <p>Welcome ' . $full_name . ' !</p>
                                <p>You recently signed up on ' . SITE_NAME . '.</p>
                                <p>Please confirm the email address you signed up with by clicking on the button below.</p>
                                <p><a href="' . $confirmation_link . '" style="padding: 10px 30px; border: 0;background: #06ABF3;color: #FFFFFF;text-decoration: none;display: inline-block;">Confirm Email Address</a></p>
                                <br/>
                                <p style="font-size: 12px;margin: 0;">Or alternatively, you can copy and paste this url in your browser:</p>
                                <p style="font-size: 12px;margin: 0;font-weight: bold;">' . $confirmation_link . '</p>
                                <br/>
                                <p>Warm Regards,</p>
                                <p>' . SITE_NAME . ' Team</p>
                                <p style="margin: 0;"><a href="' . base_url() . '">' . base_url() . '</a></p>
                                <br/>
                                <div style="text-align: center; font-size: 12px;width: 100%;">
                                    <p style="margin: 0;">&copy; ' . date("Y") . '. All Rights Reserved.</p>
                                    <p style="margin: 0;">Your opinion is important to us! Send your ideas and feedback to:</p>
                                    <p style="margin: 0;"><a href="' . base_url('contact') . '">' . base_url('contact') . '</a></p>
                                    <br/>
                                    <p>You can also follow us on:</p>
                                    <p style="margin: 0;">
                                        <a href="' . FACEBOOK_SOCIAL_LINK . '" title="' . SITE_NAME . '">Facebook</a>&nbsp;and&nbsp;
                                        <a href="' . TWITTER_SOCIAL_LINK . '" title="' . SITE_NAME . '">Twitter</a>
                                    </p>
                                </div>
                            </div>
                        </body>';

            return $str;
        }

        public function forgotPassword($full_name, $newPassword)
        {
            $str = '<body style="margin: 0;padding: 0; font-family: helvetica;width: 86%;">
                            <div style="display: inline-block;width: 100%; padding: 10px 20px;">
                                <h1 style="margin: 0;">' . SITE_NAME . '</h1>
                                <h3>Your new password</h3>
                                <p>Hey, ' . $full_name . ' !</p>
                                <p>Looks like you have forgotten your password. We have generated a new password for you.</p>
                                <p>You can login with the new password provided below. Do not forget to change your password once you login.</p>
                                <br/>
                                <p>Your new password is: <strong>' . $newPassword . '</strong></p>
                                <br/>
                                <p>Warm Regards,</p>
                                <p>' . SITE_NAME . ' Team</p>
                                <p style="margin: 0;"><a href="' . base_url() . '">' . base_url() . '</a></p>
                                <br/>
                                <div style="text-align: center; font-size: 12px;width: 100%;">
                                    <p style="margin: 0;">&copy; ' . date("Y") . '. All Rights Reserved.</p>
                                    <p style="margin: 0;">Your opinion is important to us! Send your ideas and feedback to:</p>
                                    <p style="margin: 0;"><a href="' . base_url('contact') .'">' . base_url('contact') . '</a></p>
                                    <br/>
                                    <p>You can also follow us on:</p>
                                    <p style="margin: 0;">
                                        <a href="' . FACEBOOK_SOCIAL_LINK . '" title="' . SITE_NAME . '">Facebook</a>&nbsp;and&nbsp;
                                        <a href="' . TWITTER_SOCIAL_LINK . '" title="' . SITE_NAME . '">Twitter</a>
                                    </p>
                                </div>
                            </div>
                        </body>';
        }

        public function contactUsEmail($full_name, $request_id)
        {
            $str = '<body style="margin: 0;padding: 0; font-family: helvetica;width: 86%;">
                            <div style="display: inline-block;width: 100%; padding: 10px 20px;">
                                <h1 style="margin: 0;">' . SITE_NAME . '</h1>
                                <h3>We have received your request</h3>
                                <p>Hello ' . $full_name . ' !</p>
                                <p>Your request will be processed soon. The request ID generated is <strong>' . $request_id . '</strong>.</p>
                                <p>Please refer to the request ID provided above if needed.</p>
                                <p style="font-size: 12px;margin: 0;">Or alternatively, you can contact us on: ' . SITE_EMAIL . '</p>
                                <br/>
                                <p>Warm Regards,</p>
                                <p>' . SITE_NAME . ' Team</p>
                                <p style="margin: 0;"><a href="' . base_url() . '">' . base_url() . '</a></p>
                                <br/>
                                <div style="text-align: center; font-size: 12px;width: 100%;">
                                    <p style="margin: 0;">&copy; ' . date("Y") . '. All Rights Reserved.</p>
                                    <p style="margin: 0;">Your opinion is important to us! Send your ideas and feedback to:</p>
                                    <p style="margin: 0;"><a href="' . base_url('contact') . '">' . base_url('contact') . '</a></p>
                                    <br/>
                                    <p>You can also follow us on:</p>
                                    <p style="margin: 0;">
                                        <a href="' . FACEBOOK_SOCIAL_LINK . '" title="' . SITE_NAME . '">Facebook</a>&nbsp;and&nbsp;
                                        <a href="' . TWITTER_SOCIAL_LINK . '" title="' . SITE_NAME . '">Twitter</a>
                                    </p>
                                </div>
                            </div>
                        </body>';

            return $str;
        }

        public function newConnectRequestEmail($receiver_full_name, $sender_full_name)
        {
            $str = '<body style="margin: 0;padding: 0; font-family: helvetica;width: 86%;">
                            <div style="display: inline-block;width: 100%; padding: 10px 20px;">
                                <h1 style="margin: 0;">' . SITE_NAME . '</h1>
                                <h3>You have got a new connect request</h3>
                                <p>Hello ' . $receiver_full_name . ' !</p>
                                <p>You have got a new connect request from <strong>' . $sender_full_name . '</strong></p>
                                <p>Connect with ' . $sender_full_name . ' to share your experiences and know each other. Plan a trip together, include some more friends with you. Have a great trip.</p>
                                <p>If you are not already logged in, you can do so by clicking <a href="' . base_url('login') . '" style="padding: 10px 30px; border: 0;background: #06ABF3;color: #FFFFFF;text-decoration: none;display: inline-block;">Login</a></p>
                                <br/>
                                <p>Warm Regards,</p>
                                <p>' . SITE_NAME . ' Team</p>
                                <p style="margin: 0;"><a href="' . base_url() . '">' . base_url() . '</a></p>
                                <br/>
                                <div style="text-align: center; font-size: 12px;width: 100%;">
                                    <p style="margin: 0;">&copy; ' . date("Y") . '. All Rights Reserved.</p>
                                    <p style="margin: 0;">Your opinion is important to us! Send your ideas and feedback to:</p>
                                    <p style="margin: 0;"><a href="' . base_url('contact') . '">' . base_url('contact') . '</a></p>
                                    <br/>
                                    <p>You can also follow us on:</p>
                                    <p style="margin: 0;">
                                        <a href="' . FACEBOOK_SOCIAL_LINK . '" title="' . SITE_NAME . '">Facebook</a>&nbsp;and&nbsp;
                                        <a href="' . TWITTER_SOCIAL_LINK . '" title="' . SITE_NAME . '">Twitter</a>
                                    </p>
                                </div>
                            </div>
                        </body>';

            return $str;
        }

        public function newPersonInterestedInMyTripEmail($receiver_full_name, $sender_full_name)
        {
            $str = '<body style="margin: 0;padding: 0; font-family: helvetica;width: 86%;">
                            <div style="display: inline-block;width: 100%; padding: 10px 20px;">
                                <h1 style="margin: 0;">' . SITE_NAME . '</h1>
                                <h3>Your trip is getting popular</h3>
                                <p>Hello ' . $receiver_full_name . ' !</p>
                                <p>' . $sender_full_name . ', whose thoughts are just like you have showed interest in your trip. You both might have loads of fun together on this trip of yours.</p>
                                <p>Login now and connect with ' . $sender_full_name . ' to share your experiences and know each other. Be an explorer of the world together.</p>
                                <p><a href="' . base_url('login') . '" style="padding: 10px 30px; border: 0;background: #06ABF3;color: #FFFFFF;text-decoration: none;display: inline-block;">Login</a></p>
                                <br/>
                                <p>Warm Regards,</p>
                                <p>' . SITE_NAME . ' Team</p>
                                <p style="margin: 0;"><a href="' . base_url() . '">' . base_url() . '</a></p>
                                <br/>
                                <div style="text-align: center; font-size: 12px;width: 100%;">
                                    <p style="margin: 0;">&copy; ' . date("Y") . '. All Rights Reserved.</p>
                                    <p style="margin: 0;">Your opinion is important to us! Send your ideas and feedback to:</p>
                                    <p style="margin: 0;"><a href="' . base_url('contact') . '">' . base_url('contact') . '</a></p>
                                    <br/>
                                    <p>You can also follow us on:</p>
                                    <p style="margin: 0;">
                                        <a href="' . FACEBOOK_SOCIAL_LINK . '" title="' . SITE_NAME . '">Facebook</a>&nbsp;and&nbsp;
                                        <a href="' . TWITTER_SOCIAL_LINK . '" title="' . SITE_NAME . '">Twitter</a>
                                    </p>
                                </div>
                            </div>
                        </body>';

            return $str;
        }

    }