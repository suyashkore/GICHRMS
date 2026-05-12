<?php

defined('BASEPATH') or exit('No direct script access allowed');

use Sonata\GoogleAuthenticator\GoogleAuthenticator;

class UserApp_Model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
        //$this->load->model('user_autologin');
        //$this->autologin();
    }
    
    public function Login($username, $password)
    {
         
        if ((!empty($username)) and (!empty($password))) 
        {
            $table = db_prefix() . 'staff';
            $this->db->where('email', $username);
            $user = $this->db->get($table)->row();
            if ($user) {
                // Email is okey lets check the password now
                if (!app_hasher()->CheckPassword($password, $user->password)) {
                        $response=array("status"=>false,"message"=>"You have Enter Wrong Password","user_data"=>null);
                        return $response;
                } else {
                     if ($user->active == 0) {
                            $response=array("status"=>false,"message"=>"Your Account InActive","user_data"=>null);
                            return $response;
                     } else{
                            if($user->app_access == "Y")
                                {
                                    
                                    
                                    $token = bin2hex(random_bytes(16));
                                    $this->db->where('staffid', $user->staffid);
                                    $this->db->set('login_tokan',$token);
                                    $this->db->update('tblstaff');
                                    
                                    $user_data=array(
                                        "userId"=> $user->staffid,
                                        "name"=> $user->firstname.' '.$user->lastname,
                                        "email"=> $user->email,
                                        "mobile"=> $user->phonenumber,
                                        "status"=> "Active",
                                        "SubActGroupID"=> $user->SubActGroupID,
                                        "admin"=> $user->admin,
                                        "app_access"=> $user->app_access,
                                        "login_tokan"=>$token,
                                        "Type"=>"Staff",
                                        "profile_image"=>$user->profile_image
                                    );
                                    $response=array("status"=>true,"message"=>"You have logged in successfully","user_data"=>$user_data);
                                    return $response;
                                }else {
                                    $response=array("status"=>false,"message"=>"You are Not Authirized to Login Hare..!","user_data"=>null);
                                    return $response;
                                }
                            }
                        }
            } else {
                $response=array("status"=>false,"message"=>"You have Enter wrong details.","user_data"=>null);
                return $response;
            }
        }
    }
    
    public function Clientlogin($username, $password)
    {
         
        if ((!empty($username)) and (!empty($password))) 
        {
            $table = db_prefix() . 'contacts';
            $this->db->where('email', $username);
            $user = $this->db->get($table)->row();
            if ($user) {
                // Email is okey lets check the password now
                if (!app_hasher()->CheckPassword($password, $user->password)) {
                        $response=array("status"=>false,"message"=>"You have Enter Wrong Password","user_data"=>null);
                        return $response;
                } else {
                     if ($user->active == 0) {
                            $response=array("status"=>false,"message"=>"Your Account InActive","user_data"=>null);
                            return $response;
                     }else{
                            $token = bin2hex(random_bytes(16));
                            $this->db->where('userid', $user->userid);
                            $this->db->set('login_tokan',$token);
                            $this->db->update('tblcontacts');
                                    
                            $user_data=array(
                                "userId"=> $user->userid,
                                "name"=> $user->firstname.' '.$user->lastname,
                                "email"=> $user->email,
                                "mobile"=> $user->phonenumber,
                                "login_tokan"=>$token,
                                "Type"=>"Client",
                                "profile_image"=> $user->profile_image,
                            );
                            $response=array("status"=>true,"message"=>"You have logged in successfully","user_data"=>$user_data);
                            return $response;
                                
                            }
                        }
            } else {
                $response=array("status"=>false,"message"=>"You have Enter wrong details.","user_data"=>null);
                return $response;
            }
        }
    }
}