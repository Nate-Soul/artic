<?php
require("../core/autoload.php");
require("../core/authx.php");
$page = "Settings";

$formObj    = new Forms();
$appObj     = new App();
$errors     = array();
$messaeges  = array();

//add business detail process
if(isset($_POST["addBusinessDetails"])){

    $business_name  = $formObj->validateField(Helper::sanitizeString($_POST["businessName"], "string"));
    $business_email = $form->validateField(Helper::sanitizeString($_POST["businessEmail"], "email"));
    $business_tel   = $formObj->validateField($_POST["businessPhone"]);
    $business_url   = $formObj->validateField(Helper::sanitizeString($_POST["businesswebAddr"], "url"));

    $empty_check = $formObj->checkIfEmpty(array($business_email, $business_name, $business_tel, $business_url));
    if($empty_check === false){
        $errors[] = "form fields are empty, fill in the fields to continue";
    }
    if(!filter_var($business_email, FILTER_VALIDATE_EMAIL)){
        $errors[] = "please input a valid email address";
    }
    if(strlen($business_name) <= 2 || strlen($business_name) > 25){
        $errors[] = "business name is too long/short min: 3Xters & max:25Xters";
    }
    if(!filter_var($business_url, FILTER_VALIDATE_URL)){
        $errors[] = "please enter a valid web address";
    }
    if( empty($errors) === true ){
        $array = array(
            "email"     => $business_email,
            "telephone" => $business_tel,
            "name"      => $business_name,
            "website"   => $business_url
        );
        $createApp = $appObj->createApp($array);
        if($createApp){
            $messaeges[] = "App Details was Added successfully";
            Helper::refresh();
        } else {
            $messaeges[] = "App details creation wasn't failed: please try again later";
            Helper::refresh();
        }
    }
}

//edit business detail
if(isset($_POST["editBusinessDetails"])){

    $app_name  = $formObj->validateField(Helper::sanitizeString($_POST["appName"], "string"));
    $app_email = $formObj->validateField(Helper::sanitizeString($_POST["appEmail"], "email"));
    $app_tel   = $formObj->validateField($_POST["appPhone"]);
    $app_url   = $formObj->validateField(Helper::sanitizeString($_POST["appWebAddr"], "url"));

    $empty_check = $formObj->checkIfEmpty(array($app_email, $app_name, $app_tel, $app_url));
    if($empty_check === false){
        $errors[] = "form fields are empty, fill in the fields to continue";
    }
    if(!filter_var($app_email, FILTER_VALIDATE_EMAIL)){
        $errors[] = "please input a valid email address";
    }
    if(strlen($app_name) <= 2 || strlen($app_name) > 25){
        $errors[] = "business name is too long/short min: 3Xters & max:25Xters";
    }
    if(!filter_var($app_url, FILTER_VALIDATE_URL)){
        $errors[] = "please enter a valid web address";
    }
    if( empty($errors) === true ){
        $array = array(
            "email"     => $app_email,
            "telephone" => $app_tel,
            "name"      => $app_name,
            "website"   => $app_url
        );
        $updateApp = $appObj->updateApp($array);
        if($updateApp){
            $messaeges[] = "App Details was Updated successfully";
            Helper::refresh();
        } else {
            $messaeges[] = "App details update wasn't failed: please try again later";
            Helper::refresh();
        }
    }
}

//reset business detail 
if(isset($_POST["yesure"])){
    $reset_query = $appObj->resetApp();
    if($reset_query){
        $messaeges[] = "App Has Been Reseted!";
        Helper::refresh();
    } else {
        $errors[] = "App reset failed! pls try again later";
        Helper::refresh();
    }
}

include "../templates/header.inc.php"; ?>
            <div class="col-md-3">
                <a href="./" class="btn btn-block mb-3 btn-warning"><i class="fa fa-chevron-left"></i> Back To Dashboard </a>
            </div>
            <div class="col-md-3">
                <a href="../" class="btn btn-block mb-3 btn-info" target="_blank"><i class="fa fa-eye"></i> Preview Site </a>
            </div>
        </div>
    </div>
</section>
        
<!--settings section-->
<section id="settings" class="py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-9 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"> Business Settings </h4>
                    </div>
                    <div class="card-body">
                    <?php Helper::displayErrors($errors); Helper::displayMsg($messaeges);
                    if($appObj->getAppCount() == 0 ){ ?>
                        <form action="<?php Forms::post2self(); ?>" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="businessName"> Business Name </label>
                                <input type="text" name="businessName" id="businessName" class="form-control" <?php Helper::stickyText("businessName"); ?> 
                                placeholder="Enter Business Name" required>
                            </div>
                            <!-- <div class="form-group">
                            <label for="businesslabel"> Business Label/Title </label>
                                <textarea name="businessLabel" id="businessLabel" class="form-control" placeholder="Enter Business title/label" 
                                rows="1"><?php //Helper::stickyArea("businessLabel"); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="businessSlogan">Business Slogan</label>
                                <input type="text" name="businessSlogan" id="businessSlogan" class="form-control" placeholder="Enter Business Motto/Slogan" 
                                <?php //Helper::stickyText("businessSlogan"); ?> required>
                            </div> -->
                            <div class="form-group">
                                <label for="businessEmail">Business Email </label>
                                <input type="email" name="businessEmail" id="businessEmail" class="form-control" placeholder="Enter Business Email" 
                                <?php Helper::stickyText("businessEmail"); ?>/>
                            </div>
                            <div class="form-group">
                                <label for="businessPhone"> Business Telephone </label>
                                <input type="tel" name="businessPhone" id="businessPhone" class="form-control" placeholder="Enter Business telephone" 
                                <?php Helper::stickyText("businessPhone"); ?>>
                            </div>
                            <div class="form-group">
                                <label for="businesswebAddr"> Business URL </label>
                                <input type="url" name="businesswebAddr" id="businesswebAddr" class="form-control" placeholder="Enter Business url" 
                                <?php Helper::stickyTextEdit(SITE_URL, "businesswebAddr"); ?>>
                                <input type="hidden" name="addBusinessDetails">
                            </div>
                            <!-- <div class="form-group custom-file mb-3">
                                <label class="custom-file-label" for="businessLogo"> Business logo </label>
                                <input type="file" name="businessLogo" id="businessLogo" class="custom-file-input">
                            </div>
                            <div class="form-group custom-file mb-3">
                                <label class="custom-file-label" for="businessHeaderBig"> Business Header Image 1 </label>
                                <input type="file" name="businessHeaderBig" id="businessHeaderBig" class="custom-file-input">
                            </div>
                            <div class="form-group custom-file mb-3">
                                <label class="custom-file-label" for="businessHeaderMin"> Business Header Image 2 </label>
                                <input type="file" name="businessLogo" id="businessLogo" class="custom-file-input">
                            </div>
                            -->
                            <button type="submit" class="btn btn-success btn-block"> Save </button>
                        </form>
                    <?php } else {
                        $app = $appObj->getAppById();
                        $app_id     = $app["id"];
                        $app_name   = $app["name"];
                        $app_email  = $app["email"];
                        $app_phone  = $app["telephone"];
                        $app_url    = $app["website"];                                 
                    ?>                    
                        <form action="<?php Forms::post2self(); ?>" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="appName"> Business Name </label>
                                <input type="text" name="appName" id="appName" class="form-control" <?php Helper::stickyTextEdit($app_name, "appName"); ?> 
                                placeholder="Enter Business Name"/>
                            </div>
                            <!-- <div class="form-group">
                            <label for="businesslabel"> Business Label/Title </label>
                                <textarea name="businessLabel" id="businessLabel" class="form-control" placeholder="Enter Business title/label" 
                                rows="1"><?php //Helper::stickyAreaEdit("businessLabel"); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="appSlogan">Business Slogan</label>
                                <input type="text" name="appSlogan" id="appSlogan" class="form-control" placeholder="Enter Business Motto/Slogan" 
                                <?php //Helper::stickyTextEdit($app_slogan, "appSlogan"); ?> required>
                            </div> -->
                            <div class="form-group">
                                <label for="appEmail">Business Email </label>
                                <input type="email" name="appEmail" id="appEmail" class="form-control" placeholder="Enter Business Email" 
                                <?php Helper::stickyTextEdit($app_email,"appEmail"); ?>/>
                            </div>
                            <div class="form-group">
                                <label for="appPhone"> Business Telephone </label>
                                <input type="tel" name="appPhone" id="appPhone" class="form-control" placeholder="Enter Business telephone" 
                                <?php Helper::stickyTextEdit($app_phone, "appPhone"); ?>>
                            </div>
                            <div class="form-group">
                                <label for="appWebAddr"> Business URL </label>
                                <input type="url" name="appWebAddr" id="appWebAddr" class="form-control" placeholder="Enter Business url" 
                                <?php Helper::stickyTextEdit($app_url, "appWebAddr"); ?>>
                                <input type="hidden" name="editBusinessDetails">
                            </div>
                            <!-- <div class="form-group custom-file mb-3">
                                <label class="custom-file-label" for="businessLogo"> Business logo </label>
                                <input type="file" name="businessLogo" id="businessLogo" class="custom-file-input">
                            </div>
                            <div class="form-group custom-file mb-3">
                                <label class="custom-file-label" for="businessHeaderBig"> Business Header Image 1 </label>
                                <input type="file" name="businessHeaderBig" id="businessHeaderBig" class="custom-file-input">
                            </div>
                            <div class="form-group custom-file mb-3">
                                <label class="custom-file-label" for="businessHeaderMin"> Business Header Image 2 </label>
                                <input type="file" name="businessLogo" id="businessLogo" class="custom-file-input">
                            </div>
                            -->
                            <div class="form-row">
                                <div class="col-6">
                                    <button type="submit" class="btn btn-success"> Update </button>
                                </div>
                                <div class="col-6 text-right">
                                    <a href="#resetAppModal" data-toggle="modal" class="btn btn-ente"> Reset App </a>
                                </div>
                            </div>
                        </form>
                    <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"> Edit Settings </h4>
                    </div>
                    <div class="card-body">
                        <form action="#">
                            <fieldset class="form-group">
                                <legend> Allow Author Registration </legend>
                                <div class="custom-radio">
                                    <label for="allowAuthor" class="custom-input-label"> Yes </label>
                                    <input type="radio" name="allowAuthor" id="allowAuthor" class="custom-input" checked="checked"/>
                                </div>
                                <div class="custom-radio">
                                    <label for="allowAuthor" class="custom-input-label"> No </label>
                                    <input type="radio" name="allowAuthor" id="noAuthor" class="custom-input"/>
                                </div>
                            </fieldset>
                            <fieldset class="form-group">
                                <legend> Home Page </legend>
                                <div class="custom-radio">
                                    <label for="allowAuthor" class="custom-input-label"> Grid View </label>
                                    <input type="radio" name="view" id="grid" class="custom-input"/>
                                </div>
                                <div class="custom-radio">
                                    <label for="allowAuthor" class="custom-input-label"> Matrix </label>
                                    <input type="radio" name="view" id="matrix" class="custom-input" checked="checked"/>
                                </div>
                            </fieldset>
                            <button type="submit" class="btn btn-block btn-success"> Save Changes </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include "../templates/footer.inc.php"; ?>
<!-- reset app modal -->
<div class="modal fade" id="resetAppModal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-danger"> Reset App </h6>
                <a role="button" class="close" data-dismiss="modal"><span class="text-danger"> &times; </span></a>
            </div>
            <div class="modal-body">
                <form method="POST" action="<?php Forms::post2self(); ?>">
                <p class="text-center"> 
                    Are you sure You want to reset app? <br>
                    This action cannot be undone.
                </p>
            </div>
            <div class="modal-footer">
                <input type="submit" value="sure" name="yesure" class="btn btn-sm btn-danger">
                </form>
                <button href="#" class="btn btn-sm btn-warning" data-dismiss="modal"> Cancel </button>
            </div>
        </div>
    </div>
</div>
<?php include "../templates/scripts.inc.php"; ?>