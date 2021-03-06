<?php
include_once 'Controller.php';
include_once 'models/OrderModel.php';
// session_start();

class OrderController extends Controller{
    function acceptOrder(){
        if(!isset($_GET['token']) || strlen($_GET['token'])!=40){
            $_SESSION['error_order'] = 'Liên kết bạn nhập vào không hợp lệ. Vui lòng thử lại!';
        }
        $token = $_GET['token'];
        $model = new OrderModel;
        $bill = $model->findOrderByToken($token);
        if(!$bill){
            $_SESSION['error_order'] = 'Không tìm thấy đơn hàng!';
        }
        $nowTimestamps = time();
        $billTime = strtotime($bill->token_date);
        // quá 5 ngày ; 86400 = 24*60*60
        if($nowTimestamps - 5*86400 <= $billTime){
            //Chuyen sang DH da xac nhận
            $model->updateStatusBill($bill->id);
            $_SESSION['success_order'] = 'ĐH của bạn đã xác nhận thành công, chúng tôi sẽ liên hệ với bạn';
        }
        else{
            $_SESSION['error_order'] = 'Thời gian xác nhận đơn hàng đã hết hiệu lực, vui lòng tạo DH mới!';
        }
        return $this->loadView('message', 'Thông báo');
    }
}

?>