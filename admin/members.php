<?php
require("../core/autoload.php");
require("../core/authx.php");
$memberObj  = new Admins();
$emailObj   = new Emails();
$formObj    = new Forms();
$page       = "Members";
$errors     = array();
$messages   = array();

/*************************************************
 ********* NEW ADMIN MODAL PROCESSING ************
 *************************************************/
if(isset($_POST["newMemberSignup"])){
    
    //init variables 
    $username = $formObj->validateField(Helper::sanitizeString($_POST["userName"], "string"));
    $password = $_POST["password"];
    $email    = $formObj->validateField(Helper::sanitizeString(strtolower($_POST["emailAddress"]), "email"));
    $role     = $formObj->validateField(Helper::sanitizeString($_POST["userRole"], "string"));

    $check_empty = $formObj->checkIfEmpty(array($username, $password, $email, $role));
    if($check_empty === false){
        $errors[] = "Fields Are empty, Fill In The Form To continue";
    }
    if(filter_var($email, FILTER_VALIDATE_EMAIL) === false || strlen($email) > 30){
        $errors[] = "Please input a valid email of not more than 30 characters";
    }
    if(strlen($password) < 5){
        $errors[] = "Password must be at least 5 characters";
    }
	if(strlen($username) < 2 || strlen($username) > 15){
		$errors[] = "username must be a min: 2 xters, max: 15 xters";
	}
    //check if user already exists
    $checkuser_rows	= $memberObj->checkIfMemeberAlreadyExist($username, $email);
	if( $checkuser_rows > 0){
		$errors[] = "User Credentials already exists";
    }
    /* $hash = password_hash($email, CRYPT_SHA512);
    $send = $emailObj->process("newmember", array(
        "email" => $email,
        "password" => $password,
        "role"     => $role,
        "hash"     => $hash
    ));
    if($send === false){
        $errors[] = 'couldn\'t register Please try again later';
    } */
    if(empty($errors) === true /* && $send === true */){
         $params = array(
             "username" => $username,
             "email"    => $email,
             "password" => password_hash($password, PASSWORD_DEFAULT),
             "role"     => $role
         );
        $insert_query = $memberObj->addNewMember($params);
        if($insert_query === true){
           $messages[] = "Logins details has been sent to the user";
            Helper::refresh();
        } else {
            $errors[] = "Registration Failed..., try again later";
        }
    }

}

/****************************************************
 ********* EDIT ADMIN MODAL PROCESSING ************
 ***************************************************/
if(isset($_POST["editMemberInfo"])){
	
    //init variables 
    $userid   = $memberObj->conn->esc_data($_GET["member-id"]);

    //init variables 
    $username = $formObj->validateField(Helper::sanitizeString($_POST["userEdit"], "string"));
    $email    = $formObj->validateField(Helper::sanitizeString(strtolower($_POST["emailEdit"]), "email"));
    $role     = $formObj->validateField(Helper::sanitizeString($_POST["userEdit"], "string"));
    
    if(empty($email) || empty($username) || empty($role)){
        $errors[] = "Fields Are empty, Fill In The Form To continue";
    } 

    if(!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 30){
        $errors[] = "Please input a valid Email Of Not More Than 30 characters";
    }

	if(strlen($username) < 2 || strlen($username) > 15){
		$errors[] = "username carries a min:2 xters, max: 25 Xters";
    }
    
    //check if user credentials already exists
    $checkuser_rows = $memberObj->checkIfMemeberAlreadyExist($username, $email);
	if( $checkuser_rows > 1 ){
		$errors[] = "User Credentials already exists";
    }
    if(empty($errors) === true){
        $array = array(
            "username" => $username,
            "email"    => $email,
            "role"     => $role
        );
        $update_query = $memberObj->updateMemberInfo($array, $userid);
        if ($update_query){
            $messages[] = "Member Info Updated";
            Helper::refresh();	
        } else {
            $errors[] = "Updating Member Info failed, Pls try again later";
            Helper::refresh();
        } 
    }
}

/****************************************************
 ********* EDIT ADMIN PSW PROCESSING ************
 ***************************************************/
if(isset($_POST["changeMemberPassword"])){

    $id       = $memberObj->conn->esc_data((int)$_GET["member-id"]);
    $password = $_POST["newPsw"];
    $confirm  = $_POST["cnewPsw"];

    if($formObj->checkIfEmpty(array($password, $confirm)) === false){
        $errors[] = "Fields are empty, please fill in the form to continue";
    }
    if(strlen($password) < 5){
        $errors[] = "Password Must be @least 5 Xters";
    }
    if($confirm != $password){
        $errors[] = "Passwords doesn't Match";
    }
    if(empty($errors) === true){
        $array = array("password" => password_hash($password, PASSWORD_DEFAULT));
        $update_query = $memberObj->updateMemberPsw($array, $id);
        if($update_query){
            $messages[] = "Member Password Updated";
            Helper::refresh();
        } else {
            $errors[] = "couldn't Update Member Password, try again later";
            Helper::refresh();
        }
    }
}

/****************************************************
 ********* DELETE ADMIN MODAL PROCESSING ************
 ***************************************************/
if(isset($_POST["delMemberInfo"])){

    $id = $memberObj->conn->esc_data((int)$_GET["member-id"]);
    if(isset($_POST["alsoDel"])){
        $delete_query = $memberObj->deleteMemberWithPosts($id);
    } else {
        $delete_query = $memberObj->deleteMember($id);
    }
    if($delete_query){
        $messages[] = "Member Deleted";
        Helper::refresh();
    } else {
        $errors[] = "Couldn't delete Member, Please try again later";
        Helper::refresh();
    }
}

include "../templates/header.inc.php";
 ?>
            <div class="col-md-3">
                <button data-target="#newAdminModal" data-toggle="modal" class="btn btn-block mb-3 btn-danger"> Add New Admin </button>
            </div>
            <div class="col-md-6 col-sm-12 ml-auto">
                <form action="<?php Forms::post2self(); ?>" method="GET">
                    <div class="input-group">
                        <input type="search" name="s" id="searchMembers" placeholder="Search Members..." class="form-control"/>
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-ente">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
        
<!--post-section-->
<section id="users" class="py-4">
        <div class="container">
            <div class="row">
                <!-- search -->
                <div class="col-md-12 mb-4">
                <?php 
                    if(isset($_GET["s"]) && !empty($_GET["s"])){
                    $q = $memberObj->conn->esc_data($_GET["s"]);
                    $searchMembers = $memberObj->searchMembers($q);
                    ?>
                    <div class="card card-body">
                    <?php if(!empty($searchMembers)){ ?>
                        <p class="card-text text-success">
                            Showing Results for "<?php echo $q; ?>"
                        </p>
                        <div class="table-responsive-sm">
                            <table class="table">
                                <thead class="thead-dark">
                                    <th><button class="btn btn-sm btn-ente"><span class="fa fa-times"></span></button></th>
                                    <th> ID </th>
                                    <th> Username </th>
                                    <th> Email </th>
                                    <th> Role </th>
                                    <th colspan="3"> Action </th>
                                </thead>
                                <tbody>
                        <?php foreach($searchMembers as $admin){ ?>
                                <tr>
                                    <td><input type="checkbox" name="checkArray[]" class="check-one-all"></td>
                                    <td> <?php echo $admin["admin_id"]; ?> </td>
                                    <td> <?php echo $admin["username"]; ?> </td>
                                    <td> <?php echo $admin["email"]; ?> </td>
                                    <td> <?php echo $admin["role"]; ?> </td>
                                    <td><a href="#editMemberModal<?php echo $admin["admin_id"]; ?>" data-toggle="modal" class="btn btn-sm btn-info"><i class="fa fa-edit"></i> Edit</a> </td>
                                    <td><a href="#editMemberPswModal<?php echo $admin["admin_id"]; ?>" data-toggle="modal" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i> Edit Password</a> </td>
                                    <td><a href="#delMemberModal<?php echo $admin["admin_id"]; ?>" data-toggle="modal" class="btn btn-sm btn-ente"><i class="fa fa-trash"></i> Delete</a></td>
                                </tr>
                                <?php include "members-modal.inc.php"; } unset($admin); ?>
                                </tbody>
                            </table>
                        </div>
                        <?php } else { ?>
                        <div class="alert alert-danger"> No results for this search </div>
                        <?php } ?>
                    </div>
                <?php } ?>
                </div>
                <!--//search ends here-->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">All Users </h4>
                        </div>
                        <div class="card-body">
                        <?php 
                        Helper::displayErrors($errors);
                        Helper::displayMsg($messages);
                        if($memberObj->getAdminCount() > 0){ ?>
                            <div class="table-responsive-sm">
                                <table class="table table-striped">
                                    <thead class="thead-dark">
                                        <th><button class="btn btn-sm btn-ente"><span class="fa fa-times"></span></button></th>
                                        <th> ID </th>
                                        <th> Username </th>
                                        <th> Email </th>
                                        <th> Role </th>
                                        <th colspan="3"> Action </th>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $pageObj = new Paging($memberObj->fetchAdmins(), 4);
                                        $admins  = $pageObj->getRecords();
                                        foreach($admins as $admin){ 
                                    ?>
                                        <tr>
                                            <td> <input type="checkbox" name="checkArray[]" class="check-one-all"></td>
                                            <td> <?php echo $admin["admin_id"]; ?> </td>
                                            <td> <?php echo $admin["username"]; ?> </td>
                                            <td> <?php echo $admin["email"]; ?> </td>
                                            <td> <?php echo $admin["role"]; ?> </td>
                                            <td><a href="#editMemberModal<?php echo $admin["admin_id"]; ?>" data-toggle="modal" class="btn btn-sm btn-info"><i class="fa fa-edit"></i> Edit</a> </td>
                                            <td><a href="#editMemberPswModal<?php echo $admin["admin_id"]; ?>" data-toggle="modal" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i> Edit Password</a> </td>
                                            <td><a href="#delMemberModal<?php echo $admin["admin_id"]; ?>" data-toggle="modal" class="btn btn-sm btn-ente"><i class="fa fa-trash"></i> Delete</a></td>
                                        </tr>
                                        <?php include "members-modal.inc.php"; } unset($admin); ?>
                                    </tbody>
                                </table>
                                <?php } else { ?>
                                <div class="alert alert-info"> huh How did you get in here...? </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="card-footer">
                            <?= $pageObj->getPaging(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
<?php include "../templates/footer.inc.php"; ?>

<!-- add new admin modal -->
    <div id="newAdminModal" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h4 class="modal-title"> Add New Admin </h4>
                    <a role="button" class="close" data-dismiss="modal"><span class="text-danger">&times;</span></a>
                </div>
                <div class="modal-body">
                    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="newAdminForm" method="POST">
                        <div class="form-group">
                            <label for="userName"> Username </label>
                            <input type="text" name="userName" id="userName" placeholder="Enter Username" class="form-control" <?php Helper::stickyText("userName"); ?> required>
                        </div>
                        <div class="form-group">
                        <label for="emailAddress"> Email Address </label>
                            <input type="email" name="emailAddress" id="emailAddress" placeholder="Enter a valid email address" class="form-control" <?php Helper::stickyText("emailAddress"); ?> required>
                        </div>
                        <div class="form-group">
                            <label for="password"> Password </label>
                            <input type="password" name="password" id="password" placeholder="Enter pass key" class="form-control" <?php Helper::stickyText("password"); ?> required/>
                        </div>
                        <div class="form-group">
                            <label for="cpassword"> Confirm Password </label>
                            <input type="password" name="cpassword" id="cpassword" placeholder="confirm pass key" class="form-control" <?php Helper::stickyText("cpassword"); ?> required/>
                        </div>
                        <div class="form-group">
                            <label for="UserRole"> Role </label>
                            <select name="userRole" id="userRole" class="custom-select" required>
                                <optgroup>
                                    <option value="Admin" <?php Helper::stickySelect("userRole", "Admin"); ?>> Admin </option>
                                    <option value="Author" <?php Helper::stickySelect("userRole", "Author"); ?>> Author </option>                                    
                                </optgroup>
                            </select>
                            <input type="hidden" name="newMemberSignup">
                        </div>
                </div>
                <div class="modal-footer">
                        <button type="submit" class="btn btn-info"> Add Admin</button>
                    </form>
                    <button class="btn btn-danger" data-dismiss="modal"> Cancel </button>
                </div>
            </div>
        </div>
    </div>

<?php include "../templates/scripts.inc.php"; ?>