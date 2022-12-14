<?php $title = 'Register'; include('includes/header.php');?>
<?php include('includes/mysqli_connect.php');?>
<?php include('includes/functions.php');?>
<?php include('includes/sidebar-a.php'); ?>
<div id="content">
    <?php 
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Bat dau xu ly form
            $errors = array();
            // Mac dinh cho cac truong nhap lieu la FALSE
            $fn = $ln = $e = $p = FALSE;
            
            if(preg_match('/^[\w\'.-]{2,20}$/i', trim($_POST['first_name']))) {
                $fn = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
            } else {
                $errors[] = 'first name';
            }
            
            if(preg_match('/^[\w\'.-]{2,20}$/i', trim($_POST['last_name']))) {
                $ln = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
            } else {
                $errors[] = 'last name';
            }
            
            if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $e = mysqli_real_escape_string($dbc, $_POST['email']);
            } else {
                $errors[] = 'email';
            }
            
            if(preg_match('/^[\w\'.-]{4,20}$/', trim($_POST['password1']))) {
                if($_POST['password1'] == $_POST['password2']) {
                    // Neu mat khau mot phu hop voi mat khau hai, thi luu vao csdl
                    $p = mysqli_real_escape_string($dbc, trim($_POST['password1']));
                } else {
                    // Neu mat khau khong phu hop voi nhau
                    $errors[] = "password not match";
                }
            } else {
                $errors[] = 'password';
            }
            
            if($fn && $ln && $e && $p) {
                // Neu moi thu deu day du, truy van csdl
                $q = "SELECT user_id FROM users WHERE email = '{$e}'";
                $r = mysqli_query($dbc, $q);
                confirm_query($r, $q);
                if(mysqli_num_rows($r) == 0) {
                    // Luc nay email van con trong, cho phep nguoi dung dang ky
                    
                    // Tao ra mot chuoi Activation Key
                    $a = md5(uniqid(rand(), true));
                    
                    // Chen gia tri vao CSDL
                    $q = "INSERT INTO users (first_name, last_name, email, pass, active, registration_date)
                        VALUES ('{$fn}', '{$ln}', '{$e}', SHA1('$p'), '{$a}', NOW())";
                    $r = mysqli_query($dbc, $q);
                    confirm_query($r, $q);
                    
                    if(mysqli_affected_rows($dbc) == 1) {
                        // Neu dien thong tin thanh cong, thi gui email kich hoat cho nguoi dung
                        $body = "C???m ??n b???n ???? ????ng k?? ??? trang izCMS. M???t email k??ch ho???t ???? ???????c g???i t???i ?????a ch??? email m?? b???n cung c???p. 
                        Phi??n b???n click v??o ???????ng link ????? k??ch ho???t t??i kho???n \n\n ";
                        $body .= BASE_URL . "processor/activate.php?x=".urlencode($e)."&y={$a}";
                        if(mail($_POST['email'], 'K??ch ho???t t??i kho???n t???i izCMS', $body, 'FROM: localhost')) {
                            $message = "<p class='success'>T??i kho???n c???a b???n ???? ???????c ????ng k?? th??nh c??ng. Email ???? ???????c g???i t???i ?????a ch??? c???a b???n. B???n ph???i
                            nh???n v??o link ????? k??ch ho???t t??i kho???n tr?????c khi s??? d???ng n??.</p>";
                        } else {
                            $message = "<p class='warning'>Kh??ng th??? g???i ???????c email cho b???n. R???t xin l???i v??? s??? b???t ti???n n??y.</p>";
                        }
                    } else {
                        $message = "<p class='warning'>Sorry, your order could not be processed due to a system error.</p>";
                    }
                    
                } else {
                    // Email da ton tai, phai dang ky bang email khac.
                    $message = "<p class='warning'>The email was already used previously. Please use another email address.</p>";
                }
            } else {
                // Neu mot trong cac truong bi thieu gia tri
                $message = "<p class='warning'>Please fill in all the required fields.</p>";
            }
        }// END main IF
    ?>
    
    <h2>Register</h2>
    <?php if(!empty($message)) echo $message; ?>
    <form action="register.php" method="post">
        <fieldset>
       	    <legend>Register</legend>
                <div>
                    <label for="First Name">First Name <span class="required">*</span>
                        <?php if(isset($errors) && in_array('first name', $errors)) echo "<span class='warning'>Please enter your first name</span>"; ?>
                    </label> 
    	           <input type="text" name="first_name" size="20" maxlength="20" value="<?php if(isset($_POST['first_name'])) echo $_POST['first_name']; ?>" tabindex='1' />
                </div>
                
                <div>
                    <label for="Last Name">Last Name <span class="required">*</span>
                    <?php if(isset($errors) && in_array('last name', $errors)) echo "<span class='warning'>Please enter your last name</span>"; ?>
                    </label> 
    	           <input type="text" name="last_name" size="20" maxlength="40" value="<?php if(isset($_POST['last_name'])) echo $_POST['last_name']; ?>" tabindex='2' />
                </div>
                
                <div>
                    <label for="email">Email <span class="required">*</span>
                    <?php if(isset($errors) && in_array('email', $errors)) echo "<span class='warning'>Please enter your valid email</span>"; ?>
                    </label> 
    	           <input type="text" name="email" id="email" size="20" maxlength="80" value="<?php if(isset($_POST['email'])) echo htmlentities($_POST['email'], ENT_COMPAT, 'UTF-8'); ?>" tabindex='3' />
                    <span id="available"></span>
                </div>
                
                <div>
                    <label for="password">Password <span class="required">*</span>
                    <?php if(isset($errors) && in_array('password', $errors)) echo "<span class='warning'>Please enter your password</span>"; ?>
                    </label> 
    	           <input type="password" name="password1" size="20" maxlength="20" value="<?php if(isset($_POST['password1'])) echo $_POST['password1']; ?>" tabindex='4' />
                </div>
                
                <div>
                    <label for="email">Confirm Password <span class="required">*</span> 
                    <?php if(isset($errors) && in_array('password not match', $errors)) echo "<span class='warning'>Your confirmed password does not match.</span>"; ?>
                    </label> 
    	           <input type="password" name="password2" size="20" maxlength="20" value="<?php if(isset($_POST['password12'])) echo $_POST['password2']; ?>" tabindex='5' />
                </div>
        </fieldset>
        <p><input type="submit" name="submit" value="Register" /></p>
    </form>
</div><!--end content-->
<?php include('includes/sidebar-b.php');?>
<?php include('includes/footer.php'); ?>