<?php 
require_once "MailService.php";
class ContactController {
    function form() {
        //hiển thị form
        require "view/contact/form.php";
    }

    function send() {
        //send email to shop owner
        $mailService = new MailService();
        $to =$_POST["email"];
        $subject = "KiMiShop: Khách hàng liên hệ";
        $site = get_domain_site();
        $nameFrom = $_POST["fullname"];
        $name = $_POST["fullname"];
        $mailFrom = $_POST["email"];
        $email = $_POST["email"];
        $mobile = $_POST["mobile"];
        $message = $_POST["content"];
        $content = "
        Hi shop owner,<br>
        Customer contact info:<br>
        Name: $name <br>
        Email: $email <br>
        Mobile: $mobile <br>
        Message: $message <br>
        ========xxx=====<br>
        Sent from: $site
        ";
        $mailService->recipient($mailFrom,$nameFrom, $subject, $content);
    }
}
?> 