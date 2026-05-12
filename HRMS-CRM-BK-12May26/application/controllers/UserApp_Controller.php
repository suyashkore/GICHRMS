<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class UserApp_Controller extends ClientsController {
    public function __construct() {
        parent::__construct();
        hooks()->do_action('clients_authentication_constructor', $this);
        $this->load->helper(array('form', 'url', 'file'));
        $this->load->library('upload');
    }
    
    public function GetAllStaffAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }else{
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                    $data = array(
                        "staff_id"=>$decode['staff_id']
                    );
                    $staff_list = $this->GetAllStaff($data);
                    if($staff_list){
                        $response = array("status"=>true,"message"=>"Staff List","staff_list"=>$staff_list);
                    }else{
                        $response = array("status"=>false,"message"=>"somthing went wrong");
                    }
                }
            }
        
        echo json_encode($response);    
    }
    public function GetAllStaff($params=FALSE) 
    {
        $staff_id = $params['staff_id'];
        //return $BookingID;
        $this->db->select('tblstaff.staffid,tblstaff.firstname,tblstaff.lastname,tbldepartments.name AS DeptName,tbljob_position.position_name');
        $this->db->JOIN('tblstaff_departments','tblstaff_departments.staffid = tblstaff.staffid',"LEFT");
        $this->db->JOIN('tbldepartments', 'tbldepartments.departmentid = tblstaff_departments.departmentid','LEFT');
        $this->db->JOIN('tbljob_position', 'tbljob_position.position_id = tblstaff.job_position',"LEFT");
        $this->db->where('tblstaff.active', 1);
        $this->db->where_not_in('tblstaff.staffid', $staff_id);
        $staff_list = $this->db->get(db_prefix().'staff')->result_array();
        return $staff_list;
    }
    
    public function GetAllGroupAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }else{
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                    $data = array(
                        "staff_id"=>$decode['staff_id'],
                        "user_type"=>$decode['user_type']
                    );
                    if($decode['user_type'] == "Client"){
                        $checkLoginTokan = $this->CheckClientTokan($decode['login_tokan'],$decode['staff_id']);
                    }else{
                        $checkLoginTokan = $this->CheckTokan($decode['login_tokan'],$decode['staff_id']);
                    }
                    
                    if($checkLoginTokan){
                        $group_list = $this->GetAllStaffGroup($data);
                        if($group_list){
                            $response = array("status"=>true,"message"=>"Group List","group_list"=>$group_list);
                        }else{
                            $response = array("status"=>false,"message"=>"somthing went wrong");
                        }
                    }else{
                        $response = array("status"=>false,"message"=>"Please login with registered mobile number");
                    }
                }
            }
        echo json_encode($response);    
    }
    
    public function GetAllStaffGroup($params=FALSE) 
    {
        $staff_id = $params['staff_id'];
        $user_type = $params['user_type'];
        //return $BookingID;
        
        $this->db->select('tblchatgroupmembers.*');
        if($user_type == "Client"){
            $this->db->or_where('client_id', $staff_id);
        }else{
            $this->db->where('member_id', $staff_id);
        }
        $group_list = $this->db->get(db_prefix().'chatgroupmembers')->result_array();
        return $group_list;
    }
    
    public function GetAllGroupMsgAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }else{
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                    $data = array(
                        "GroupID"=>$decode['GroupID'],
                        "staff_id"=>$decode['staff_id']
                    );
                    if($decode['user_type'] == "Client"){
                        $checkLoginTokan = $this->CheckClientTokan($decode['login_tokan'],$decode['staff_id']);
                    }else{
                        $checkLoginTokan = $this->CheckTokan($decode['login_tokan'],$decode['staff_id']);
                    }
                    
                    if($checkLoginTokan){
                        $groupMsg_list = $this->GetAllGroupMsg($data);
                        if($groupMsg_list){
                            $response = array("status"=>true,"message"=>"Message List","message_list"=>$groupMsg_list);
                        }else{
                            $response = array("status"=>false,"message"=>"somthing went wrong");
                        }
                    }else{
                        $response = array("status"=>false,"message"=>"Please login with registered mobile number");
                    }
                }
            }
        echo json_encode($response);    
    }
    
    public function GetAllGroupMsg($params=FALSE) 
    {
        $GroupID = $params['GroupID'];
        //return $BookingID;
        
        $this->db->select('tblchatgroupmessages.*,tblstaff.firstname,tblstaff.profile_image');
        $this->db->JOIN('tblstaff','tblstaff.staffid = tblchatgroupmessages.sender_id','LEFT');
        $this->db->where('group_id', $GroupID);
        $group_list = $this->db->get(db_prefix().'chatgroupmessages')->result_array();
        return $group_list;
    }
    
    
    public function SendGroupMsgAPI($param=FALSE) {
        $response = array();
        //echo json_encode($response);    
        //die;
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }else{
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                    $data = array(
                        "group_id"=>$decode['group_id'],
                        "message"=>$decode['message'],
                        "time_sent"=>date('Y-m-d H:i:s')
                    );
                    if($decode['user_type'] == "Client"){
                        $data['client_id'] = $decode['sender_id'];
                        $checkLoginTokan = $this->CheckClientTokan($decode['login_tokan'],$decode['sender_id']);
                    }else{
                        $data['sender_id'] = $decode['sender_id'];
                        $checkLoginTokan = $this->CheckTokan($decode['login_tokan'],$decode['sender_id']);
                    }
                    
                    if($checkLoginTokan){
                        $insert_id = $this->SendGroupMsg($data);
                        
                        $this->pusher->trigger($decode['group_name'], 'group-send-event', array(
                            'message' => pr_chat_convertLinkImageToString($decode['message']),
                            'from' => $decode['sender_id'],
                            'to_group' => $decode['group_id'],
                            'from_name' => get_staff_full_name($decode['sender_id']),
                            'group_name' => $decode['group_name'],
                            'last_insert_id' => $insert_id,
                            'sender_image' => $decode['sender_image'],
                        ));
        
                        $this->pusher->trigger($decode['group_name'], 'group-notify-event', array(
                            'from' => $decode['sender_id'],
                            'from_name' => get_staff_full_name($decode['sender_id']),
                            'to_group' => $decode['group_id'],
                            'group_name' => $decode['group_name'],
                            'sender_image' => $decode['sender_image'],
                            'message' => pr_chat_convertLinkImageToString($decode['message']),
                        ));
                        
                        if($insert_id){
                            $response = array("status"=>true,"message"=>"Message send Successfully");
                        }else{
                            $response = array("status"=>false,"message"=>"somthing went wrong");
                        }
                    }else{
                        $response = array("status"=>false,"message"=>"Please login with registered mobile number");
                    }
                }
            }
        echo json_encode($response);    
    }
    
    public function SendGroupMsgMultiMediaAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            /*if ($content_type!="") {
                $response = array("error" => true,"message" => "ggg");  
            }else{*/
                $content=trim(file_get_contents("php://input"));
                //$decode=json_decode($content,true);
                    $data = array(
                        "sender_id"=>$_POST['sender_id'],
                        "to_group"=>$_POST['to_group'],
                    );
                    if($_POST['user_type'] == "Client"){
                        $checkLoginTokan = $this->CheckClientTokan($_POST['login_tokan'],$_POST['sender_id']);
                    }else{
                        $checkLoginTokan = $this->CheckTokan($_POST['login_tokan'],$_POST['sender_id']);
                    }
                    if($checkLoginTokan){
                        
                        $allowedFiles = get_option('allowed_files');
                        $allowedFiles = str_replace(',', '|', $allowedFiles);
                        $allowedFiles = str_replace('.', '', $allowedFiles);
                
                        $config = array(
                            'upload_path' => PR_CHAT_MODULE_GROUPS_UPLOAD_FOLDER,
                            'allowed_types' => $allowedFiles,
                            'max_size' => '9048000',
                        );
                        $this->load->library('upload', $config);
                        if ($this->upload->do_upload()) {
                            $from = $_POST['sender_id'];
                            $to_group = $_POST['to_group'];
                
                            if (is_numeric($from) && is_numeric($to_group)) {
                                $this->db->insert(
                                    'tblchatgroupsharedfiles',
                                    [
                                        'sender_id' => $from,
                                        'group_id' => $to_group,
                                        'file_name' => $this->upload->data('file_name'),
                                    ]
                                );
                                $insert_id = $this->db->insert_id();
                                $data = $this->upload->data();
                            }
                        }else{
                            $data = $this->upload->display_errors();
                        }
                        
                        
                        if($insert_id){
                            $response = array("status"=>true,"message"=>"File Uploaded Successfully","data"=>$data);
                        }else{
                            $response = array("status"=>false,"message"=>"somthing went wrong","data"=>$data);
                        }
                    }else{
                        $response = array("status"=>false,"message"=>"Please login with registered mobile number",'data'=>$data);
                    }
                //}
            }
        echo json_encode($response);    
    }
    
    
    public function SendGroupMsg($data) 
    {
        
        $this->db->insert(db_prefix() . 'chatgroupmessages', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
        
    }
    
    
    public function GetAllMsgAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }else{
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                    $data = array(
                        "sender_id"=>$decode['sender_id'],
                        "reciever_id"=>$decode['reciever_id'],
                        "limit"=>$decode['limit'],
                        "offset"=>$decode['offset']
                    );
                    $checkLoginTokan = $this->CheckTokan($decode['login_tokan'],$decode['sender_id']);
                    
                    if($checkLoginTokan){
                        $Msg_list = $this->GetAllMsg($data);
                        if($Msg_list){
                            $response = array("status"=>true,"message"=>"Message List","message_list"=>$Msg_list);
                        }else{
                            $response = array("status"=>true,"message"=>"No message","message_list"=>$Msg_list);
                        }
                    }else{
                        $response = array("status"=>false,"message"=>"Please login with registered mobile number");
                    }
                }
            }
        echo json_encode($response);    
    }
    
    public function GetAllMsg($params=FALSE) 
    {
        $to = $params['sender_id'];
        $from = $params['reciever_id'];
        $offset = $params['offset'];
        $limit = $params['limit'];
        //return $BookingID;
        
        $sql = 'SELECT * FROM ' . db_prefix() . "chatmessages WHERE (sender_id = {$to} AND reciever_id = {$from}) OR (sender_id = {$from} AND reciever_id = {$to}) ORDER BY id DESC LIMIT {$offset}, {$limit}";

        $query = $this->db->query($sql)->result();

        foreach ($query as &$chat) {
            $chat->message = $chat->message;
            //$chat->message = $chat->message;
            //$chat->message = check_for_links_lity($chat->message);
            $this->checkMessageForAudio($chat);
            $chat->user_image = getUserImage($chat->sender_id);
            $chat->sender_fullname = get_staff_full_name($chat->sender_id);
            $chat->time_sent_formatted = _dt($chat->time_sent);
        }
        
        return $query;
    }
    private function checkMessageForAudio($chat)
    {
        if (preg_match('~\b(src|audio|controls|ogg)\b~i', $chat->message)) {
            $chat->message = html_entity_decode($chat->message);
        }
    }
    
    public function SendMsgAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }else{
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                    $data = array(
                        "sender_id"=>$decode['sender_id'],
                        "reciever_id"=>$decode['reciever_id'],
                        "message"=>$decode['message'],
                        "time_sent"=>date('Y-m-d H:i:s')
                    );
                    $checkLoginTokan = $this->CheckTokan($decode['login_tokan'],$decode['sender_id']);
                    $imageData['receiver_image'] = getUserImage(str_replace('#', '', $decode['reciever_id']));
                    
                    if($checkLoginTokan){
                        $insert_id = $this->SendMsg($data);
                        
                        $this->pusher->trigger('presence-mychanel', 'send-event', array(
                            'message' => pr_chat_convertLinkImageToString($decode['message']),
                            'from' => $decode['sender_id'],
                            'to' => $decode['reciever_id'],
                            'from_name' => get_staff_full_name($decode['sender_id']),
                            'last_insert_id' => $insert_id,
                            'sender_image' => $decode['sender_image'],
                            'receiver_image' => $imageData['receiver_image'],
                        ));
    
                        $this->pusher->trigger(
                            'presence-mychanel',
                            'notify-event',
                            array(
                                'from' => $decode['sender_id'],
                                'to' => str_replace('#', '', $decode['reciever_id']),
                                'from_name' => get_staff_full_name($decode['sender_id']),
                                'sender_image' => $decode['sender_image'],
                                'message' => pr_chat_convertLinkImageToString($decode['message']),
                            )
                        );
                        
                        if($insert_id){
                            $response = array("status"=>true,"message"=>"Message send Successfully");
                        }else{
                            $response = array("status"=>false,"message"=>"somthing went wrong");
                        }
                    }else{
                        $response = array("status"=>false,"message"=>"Please login with registered mobile number");
                    }
                }
            }
        echo json_encode($response);    
    }
    
    
    public function SendMsg($data) 
    {
        
        $this->db->insert(db_prefix() . 'chatmessages', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
        
    }
    
    public function SendMsgMultiMediaAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            /*if ($content_type!="") {
                $response = array("error" => true,"message" => "ggg");  
            }else{*/
                $content=trim(file_get_contents("php://input"));
                //$decode=json_decode($content,true);
                    $data = array(
                        "sender_id"=>$_POST['sender_id'],
                        "reciever_id"=>$_POST['reciever_id'],
                    );
                    $checkLoginTokan = $this->CheckTokan($_POST['login_tokan'],$_POST['sender_id']);
                    
                    if($checkLoginTokan){
                        
                        $allowedFiles = get_option('allowed_files');
                        $allowedFiles = str_replace(',', '|', $allowedFiles);
                        $allowedFiles = str_replace('.', '', $allowedFiles);
                
                        $config = array(
                            'upload_path' => PR_CHAT_MODULE_UPLOAD_FOLDER,
                            'allowed_types' => $allowedFiles,
                            'max_size' => '9048000',
                        );
                        $this->load->library('upload', $config);
                        if ($this->upload->do_upload()) {
                            $from = $_POST['sender_id'];
                            $to = $_POST['reciever_id'];
                
                            if (is_numeric($from) && is_numeric($to)) {
                                $this->db->insert(
                                    'tblchatsharedfiles',
                                    [
                                        'sender_id' => $from,
                                        'reciever_id' => $to,
                                        'file_name' => $this->upload->data('file_name'),
                                    ]
                                );
                                $insert_id = $this->db->insert_id();
                                $data = $this->upload->data();
                            }
                        }else{
                            $data = $this->upload->display_errors();
                        }
                        
                        
                        if($insert_id){
                            $response = array("status"=>true,"message"=>"File Uploaded Successfully","data"=>$data);
                        }else{
                            $response = array("status"=>false,"message"=>"somthing went wrong","data"=>$data);
                        }
                    }else{
                        $response = array("status"=>false,"message"=>"Please login with registered mobile number",'data'=>$data);
                    }
                //}
            }
        echo json_encode($response);    
    }
    
    
    
    public function CheckTokan($login_tokan,$staff_id) 
    {
        $this->db->where('staffid', $staff_id);
        $this->db->where('login_tokan', $login_tokan);
        $UserDetails = $this->db->get(db_prefix().'staff')->row_array();
        return $UserDetails;
    }
    
    public function CheckClientTokan($login_tokan,$staff_id) 
    {
        $this->db->where('userid', $staff_id);
        $this->db->where('login_tokan', $login_tokan);
        $UserDetails = $this->db->get(db_prefix().'contacts')->row_array();
        return $UserDetails;
    }
    
    public function LoginAPI($param=FALSE) 
    {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }else{
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                $data=array(
                    "username"=>$decode['username'],
                    "password"=>$decode['password']
                );
                $response=$this->login($data);
            }
        }
        echo json_encode($response);    
    }
    
    public function login($params=FALSE){
        
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->Login($params['username'],$params['password']);
        return $success; 
    }
    
    public function ClientLoginAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }else{
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                $data=array(
                    "username"=>$decode['username'],
                    "password"=>$decode['password']
                );
                $response=$this->Clientlogin($data);
            }
        }
        echo json_encode($response);    
    }
    
    public function Clientlogin($params=FALSE){
        
        $this->load->model('UserApp_Model');
        $success = $this->UserApp_Model->Clientlogin($params['username'],$params['password']);
        return $success; 
    }
    
    public function LogOutAPI($param=FALSE) {
        $response = array();
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $content_type=$_SERVER['CONTENT_TYPE'];
            if ($content_type!="application/json") {
                $response = array("error" => true,"message" => "Invalid content type.");  
            }else{
                $content=trim(file_get_contents("php://input"));
                $decode=json_decode($content,true);
                if($decode['Type'] == "Client"){
                    $checkLoginTokan = $this->CheckTokan_for_client($decode['login_tokan'],$decode['staff_id']);
                }else{
                    $checkLoginTokan = $this->CheckTokan($decode['login_tokan'],$decode['staff_id']);
                }
                
                if($checkLoginTokan){
                    $data=array(
                        "staff_id"=>$decode['staff_id'],
                        "Type"=>$decode['Type'],
                        "login_tokan"=>$decode['login_tokan'],
                    );
                    $response=$this->LogOut($data);    
                }else{
                    $response = array("status"=>false,"message"=>"Please login with registered mobile number");
                }
                
            }
        }
        echo json_encode($response);    
    }
    
    
    public function Logout($data) 
    {
        $affected_row = 0;
        if($data["Type"] == "Client"){
            $this->db->where('userid', $data["staff_id"]);
            $this->db->set('login_tokan',NULL);
            $this->db->update('tblcontacts');
            if($this->db->affected_rows() > 0){
                $affected_row++;
            }
        }else{
            $this->db->where('staffid', $data["staff_id"]);
            $this->db->set('login_tokan',NULL);
            $this->db->update('tblstaff');
            if($this->db->affected_rows() > 0){
                $affected_row++;
            }
        }
        if($affected_row >0){
            $response=array("status"=>true,"message"=>"You have logged Out successfully");
            return $response;
        }else{
            $response=array("status"=>false,"message"=>"Semething went wrong");
            return $response;
        }
    }
    
    public function CheckTokan_for_client($login_tokan,$staff_id) 
    {
        $this->db->where('userid', $staff_id);
        $this->db->where('login_tokan', $login_tokan);
        $UserDetails = $this->db->get(db_prefix().'contacts')->row_array();
        return $UserDetails;
    }
}