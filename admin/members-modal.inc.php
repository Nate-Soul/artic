<!-- Edit Member modal -->
<div id="editMemberModal<?php echo $admin["admin_id"]; ?>" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h4 class="modal-title"> Edit Member Info </h4>
                <a role="button" class="close" data-dismiss="modal"><span class="text-danger">&times;</span></a>
            </div>
            <div class="modal-body">
                <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>?member-id=<?php echo $admin["admin_id"]; ?>" id="editAdminForm" method="POST">
                    <div class="form-group">
                        <label for="username"> Username </label>
                        <input type="text" name="userEdit" id="userEdit" class="form-control" <?php Helper::stickyTextEdit($admin["username"], "userEdit"); ?>>
                    </div>
                    <div class="form-group">
                        <label for="emailEdit"> Email </label>
                        <input type="email" name="emailEdit" id="emailEdit" class="form-control" <?php Helper::stickyTextEdit($admin["email"], "emailEdit"); ?>>
                    </div>
                    <div class="form-group">
                        <label for="roleEdit"> Admin Role </label>
                        <select name="roleEdit" id="roleEdit" class="custom-select">
                            <optgroup>
                                <option value="Admin" <?php Helper::stickySelectEdit("roleEdit", $admin["role"], "Admin"); ?>>Admin</option>
                                <option value="Author" <?php Helper::stickySelectEdit("roleEdit", $admin["role"], "Author"); ?>>Author</option>
                            </optgroup>
                        </select>
                        <input type="hidden" name="editMemberInfo">
                    </div>
            </div>
            <div class="modal-footer">
                    <button type="submit" class="btn btn-info"> Update </button>
                </form>
                <button class="btn btn-danger" data-dismiss="modal"> Cancel </button>
            </div>
        </div>
    </div>
</div>


<!-- change Member Password modal -->
<div id="editMemberPswModal<?php echo $admin["admin_id"]; ?>" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h4 class="modal-title"> <i class="fa fa-edit"></i> Members Password </h4>
                <a role="button" class="close" data-dismiss="modal"><span class="text-danger">&times;</span></a>
            </div>
            <div class="modal-body">
                <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>?member-id=<?php echo $admin["admin_id"]; ?>" id="editAdminForm" method="POST">
                    <div class="form-group">
                        <label for="newPsw"> New Password </label>
                        <input type="password" name="newPsw" id="newPsw" class="form-control" placeholder="Enter new password" 
                        <?php Helper::stickyTextAlt("newPsw"); ?>>
                    </div>
                    <div class="form-group">
                        <label for="cnewPsw"> Confirm Password </label>
                        <input type="password" name="cnewPsw" id="cnewPsw" class="form-control" placeholder="confirm Passwword" 
                        <?php Helper::stickyTextAlt("cnewPsw"); ?>>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="changeMemberPassword">
                    </div>
            </div>
            <div class="modal-footer">
                    <button type="submit" class="btn btn-info"> change password </button>
                </form>
                <button class="btn btn-danger" data-dismiss="modal"> Cancel </button>
            </div>
        </div>
    </div>
</div>

<!-- delete member modal -->
 <div class="modal fade" id="delMemberModal<?php echo $admin["admin_id"]; ?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-danger"> Delete Member </h6>
                <a role="button" class="close" data-dismiss="modal"><span class="text-danger">&times;</span></a>
            </div>
            <div class="modal-body">
                <form method="POST" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>?member-id=<?php echo $admin["admin_id"]; ?>">
                <p> 
                    Are you sure You want to delete this member
                    <strong><?php echo $admin["username"]; ?></strong>
                </p>
                <div class="form-group">
                    <label for="alsoDel">
                    <input type="checkbox" name="alsoDel" id="alsoDel">
                    Yes also delete members post </label>
                    <input type="hidden" name="delMemberInfo">
                </div>
            </div>
            <div class="modal-footer">
                <input type="submit" value="sure" class="btn btn-sm btn-danger">
                </form>
                <button href="#" class="btn btn-sm btn-warning" data-dismiss="modal"> Cancel </button>
            </div>
        </div>
    </div>
</div>