<?php
require("../core/autoload.php");
require("../core/auth.php");

$userID     = $_SESSION["user"]["admin_id"];
$profileObj = new Admins();
$me         = $profileObj->fetchAdminById($userID);
$formObj    = new Forms();
$page       = "Profile";
$errors     = array();
$messages   = array();

/****************************************************
 ********* EDIT ACCOUNT MODAL PROCESSING ************
 ***************************************************/
if(isset($_POST["editMyaccount"])){
    //init variables 
    $userid   = $profileObj->conn->esc_data($userID);
    $username = $_POST["usernameEdit"];
    $email    = strtolower($_POST["emailEdit"]);
    $formObj->validateFields(array($username, $email));
    
    if( $formObj->checkIfEmpty(array($username, $email)) === false ){
        $errors[] = "Fields Are empty, Fill In The Form To continue";
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 30){
        $errors[] = "Please input a valid Email Of Not More Than 30 characters";
    }

	if(strlen($username) < 2 || strlen($username) > 15){
		$errors[] = "username carries a min:2 xters, max: 25 Xters";
    }
    //check if user credentials already exists
    $checkuser_rows = $profileObj->checkIfMemeberAlreadyExist($username, $email);
	if( $checkuser_rows > 1 ){
		$errors[] = "User Credentials already exists";
    }
    if(empty($errors) === true){
        $array = array(
            "username" => $username,
            "email"    => $email,
        );
        $update_query = $profileObj->updateMemberInfo($array, $userid);
        if ($update_query){
            $messages[] = "Your Account Info Has Been Updated";
            Helper::refresh();	
        } else {
            $errors[] = "Account Update Failed";
            Helper::refresh();
        } 
    }
}

/****************************************************
 ********* EDIT ACCOUNT PSW PROCESSING ************
 ***************************************************/
if(isset($_POST["changePswRequest"])){

    $id       = $profileObj->conn->esc_data($userID);
    $oldPsw   = $_POST["oldPassword"];
    $password = $_POST["password"];
    $confirm  = $_POST["cPassword"];

    if( $formObj->checkIfEmpty(array($password, $confirm)) === false ){
        $errors[] = "Fields are empty, please fill in the form to continue";
    }
    if(strlen($password) < 5){
        $errors[] = "Password Must be @least 5 Xters";
    }
    if($confirm != $password){
        $errors[] = "Passwords doesn't Match";
    }
    $get_old_password = $profileObj->fetchPassword($id);
    if(!password_verify($oldPsw, $get_old_password)){
        $errors[] = "old password was incorrect: ".$get_old_password." didn't match ".$oldPsw;
    }
    if(empty($errors) === true){
        $password = password_hash($password, PASSWORD_DEFAULT);
        $array = array("password" => $password);
        $update_query = $profileObj->updateMemberPsw($array, $id);
        if($update_query){
            $messages[] = "Password Updated";
            Helper::refresh();
        } else {
            $errors[] = "Password update failed, try again later";
            Helper::refresh();
        }
    }
}

/****************************************************
 ********* DELETE ACCOUNT MODAL PROCESSING ************
 ***************************************************/
if(isset($_POST["delMyAccount"])){

    $id = $profileObj->conn->esc_data($userID);
    if(isset($_POST["alsoDel"])){
        $delete_query = $profileObj->deleteMemberWithPosts($id);
    } else {
        $delete_query = $profileObj->deleteMember($id);
    }
    if($delete_query){
        $messages[] = "Your account has been deleted, you'll be automatically logged Out in a second";
        Helper::refresh(1, "../2412157914.php");
    } else {
        $errors[] = "Couldn't delete account, Please try again later";
        Helper::refresh();
    }
}

include "../templates/author-header.inc.php"; ?>
            <div class="col-md-3">
                <a href="./" class="btn btn-block mb-3 btn-warning"> <i class="fa fa-chevron-left"></i> Back to dashboard</a>
            </div>
            <div class="col-md-3">
                <a href="#editAccountPswModal" data-toggle="modal" class="btn btn-block mb-3 btn-success"> <i class="fa fa-pencil"></i> Change Password</a>
            </div>
            <div class="col-md-3">
                <a href="#delAccountModal" data-toggle="modal" class="btn btn-block mb-3 btn-danger"> <i class="fa fa-trash"></i> Delete Account </a>
            </div>
        </div>
    </div>
</section>
        
<!--profile-section-->
<section id="profile" class="py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-9 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"> My Profile</h4>
                    </div>
                    <div class="card-body">
                    <?php 
                        Helper::displayErrors($errors); 
                        Helper::displayMsg($messages);
                    ?>
                        <form action="<?php Forms::post2self(); ?>" id="editMyaccountForm" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="usernameEdit"> Username </label>
                                <input type="text" name="usernameEdit" id="usernameEdit" class="form-control" <?php Helper::stickyTextEdit($me[0]["username"], "usernameEdit"); ?> required>
                            </div>
                            <div class="form-group">
                                <label for="emailEdit">Email </label>
                                <input type="email" name="emailEdit" id="emailEdit" class="form-control" <?php Helper::stickyTextEdit($me[0]["email"], "emailEdit"); ?> required>
                            </div>
                            <!--
                            <div class="form-group">
                                <label for="bio"> Bio </label>
                                <textarea name="bio" id="bio" class="form-control" cols="30" rows="5">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Similique alias at ipsa labore consequuntur expedita pariatur nisi sequi repudiandae corrupti?</textarea>
                            </div> 
                            <div class="form-group custom-file">
                                <label class="custom-file-label" for="avatar">Upload avatar</label>
                                <input type="file" name="avatar" id="avatar" class="custom-file-input"/>
                            </div> -->
                            <input type="hidden" name="editMyaccount">
                            <button type="submit" class="btn btn-success btn-block"> Save </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-body">
                    <img src="../images/icons/avatar.png" class="card-img-top mb-3" alt="username"/>
                    <h5 class="card-title"> Username: <?php echo $me[0]["username"]; ?> </h5>
                    <p class="card-text">
                        <strong>Email:</strong> <?php echo $me[0]["email"]; ?>
                    </p>
                    <p class="card-text">
                        <strong>Bio</strong><br/>
                        <?php //echo $me[0]["bio"]; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include "../templates/footer.inc.php"; ?>

<!-- change password modal -->
<div id="editAccountPswModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h4 class="modal-title"> Change Password </h4>
                <a role="button" class="close" data-dismiss="modal"><span class="text-danger">&times;</span></a>
            </div>
            <div class="modal-body">
                <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="editAdminPswForm" method="POST">
                    <div class="form-group">
                        <label for="oldPassword"> Old Password </label>
                        <input type="password" name="oldPassword" id="oldPassword" class="form-control" placeholder="Enter old password" 
                        <?php Helper::stickyText("oldPassword"); ?> required>
                    </div>
                    <div class="form-group">
                        <label for="password"> New Password </label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Enter new password" 
                        <?php Helper::stickyText("password"); ?> required>
                    </div>
                    <div class="form-group">
                        <label for="cPassword"> Confirm Password </label>
                        <input type="password" name="cPassword" id="cPassword" class="form-control" placeholder="confirm new password" 
                        <?php Helper::stickyText("cPassword"); ?> required>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="changePswRequest">
                    </div>
            </div>
            <div class="modal-footer">
                    <button type="submit" class="btn btn-info"> Change </button>
                </form>
                <button class="btn btn-danger" data-dismiss="modal"> Cancel </button>
            </div>
        </div>
    </div>
</div>

<!-- delete account modal -->
<div class="modal fade" id="delAccountModal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-danger"> Delete Account </h6>
                <a role="button" class="close" data-dismiss="modal"><span class="text-danger"> &times; </span></a>
            </div>
            <div class="modal-body">
                <form method="POST" action="<?php Forms::post2self(); ?>">
                <p class="text-center text-ente"> 
                    Are you sure You want to delete your account? this action can't be undone.
                </p>
                <div class="form-group">
                    <label for="alsoDel">
                    <input type="checkbox" name="alsoDel" id="alsoDel">
                    Yes also remove my articles </label>
                    <input type="hidden" name="delMyAccount">
                </div>
            </div>
            <div class="modal-footer">
                <input type="submit" value="sure" name="sure" class="btn btn-sm btn-danger">
                </form>
                <button href="#" class="btn btn-sm btn-warning" data-dismiss="modal"> Cancel </button>
            </div>
        </div>
    </div>
</div>

<?php include "../templates/scripts.inc.php"; ?>
