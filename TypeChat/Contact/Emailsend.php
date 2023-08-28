<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $to = "typechat98@gmail.com";
    $inputMessage = $_POST['message'];
    $inputEmail = $_POST['email'];
    $inputName = $_POST['name'];
    $subject = "Form Submitted";
    $message = "A form has been filled in on your website. \r\n\n The message is: {$inputMessage} \r\n\n The email address is: {$inputEmail} \r\n\n  The name is: {$inputName}";
    $headers = "From: " . $_POST['email'];
    if (!preg_match("/^[a-zA-z]*$/",$inputName))
    {
        $php_errormsg = "Invalid name!";
        include 'Error.php';
    }
    else if (!filter_var($inputEmail, FILTER_VALIDATE_EMAIL))
    {
        $php_errormsg = "Invalid email!";
        include 'Error.php';
    }
    else if (empty($inputMessage))
    {
        $php_errormsg = "You didnt put in a message pleas try again!";
        include 'Error.php';
    }
    else if (empty($inputEmail))
    {
        $php_errormsg = "You didnt put in a email pleas try again!";
        include 'Error.php';
    }
    else if (empty($inputName))
    {
        $php_errormsg = "You didnt put in a name pleas try again!";
        include 'Error.php';
    }
    else {
        $emailVerzonde = mail($to, $subject, $message, $headers);
        if ($emailVerzonde) {
            include_once 'Thanks.php';
// create email headers

            $headers = 'From: '.$inputEmail."\r\n".
                "Reply-To: ".$inputEmail."\r\n" .
                "X-Mailer: PHP/" . phpversion();

            /* Prepare autoresponder subject */

            $respond_subject = "Thank you for contacting us!";

            /* Prepare autoresponder message */

            $respond_message = "Thank you for contacting TypeChat!

We will get back to you as soon as possible.

If there is somthing you need to know right away please call us at 06 40266268.

Yours sincerely,

Team TypeChat!

This is an automated response, please do not reply!
";

            mail($inputEmail, $respond_subject, $respond_message);


            mail($to, $inputName, $inputMessage, $headers);
        } else {
            include 'email.view.error.php';
            $php_errormsg = "Something went wrong pleas call us at 06 123456789!";
        }
    }

}
?>