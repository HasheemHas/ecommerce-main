 <?php
 require_once ("../backend/include/initialize.php");

 if (isset($_GET['redirect']) && $_GET['redirect'] !== '') {
     $_SESSION['login_redirect'] = $_GET['redirect'];
 }

 if (@$_GET['page'] <= 2 or @$_GET['page'] > 5) {
  # code...
    // unset($_SESSION['PRODUCTID']);
    // // unset($_SESSION['QTY']);
    // // unset($_SESSION['TOTAL']);
} 


 
if(isset($_POST['sidebarLogin'])){
  $email = trim($_POST['U_USERNAME']);
  $upass  = trim($_POST['U_PASS']);
  $h_upass = sha1($upass);
  
   if ($email == '' OR $upass == '') {

      message("Invalid Username and Password!", "error");
      redirect(web_root."index.php");
         
    } else {   
        $cus = new Customer();
        $cusres = $cus::cusAuthentication($email,$h_upass);

        if ($cusres === true){
           $go = !empty($_SESSION['login_redirect']) ? $_SESSION['login_redirect'] : 'index.php?q=profile';
           unset($_SESSION['login_redirect']);
           redirect(web_root . $go);
         }else{
              message("Invalid Username and Password! Please contact administrator", "error");
              redirect(web_root."index.php");
         }
 
 }
}



 if(isset($_POST['modalLogin'])){
  $email = trim($_POST['U_USERNAME']);
  $upass  = trim($_POST['U_PASS']);
  $h_upass = sha1($upass);
  
   if ($email == '' OR $upass == '') { 
      message("Invalid Username and Password!", "error");
       redirect(web_root."index.php?page=6");
         
    } else {   
        $cus = new Customer();
        $cusres = $cus::cusAuthentication($email,$h_upass);

        if ($cusres === true){
           if($_POST['proid']==''){
            redirect(web_root."index.php?q=orderdetails");
           }else{
               $proid = $_POST['proid'];
               $mydb->setQuery("INSERT INTO `tblwishlist` (`PROID`, `CUSID`, `WISHDATE`, `WISHSTATS`)  VALUES ('". $proid."','".$_SESSION['CUSID']."','".DATE('Y-m-d')."',0)");
               $mydb->executeQuery();
               redirect(web_root."index.php?q=profile");
             }

         
        }else if ($cusres === 'unverified') {
             message("Your email address has not been verified yet. Please check your inbox for the verification link.", "error");
             redirect(web_root."index.php?q=login");
        }else{
             message("Invalid Username and Password! Please contact administrator", "error");
             redirect(web_root."index.php");
        }
 
 }
 }
 ?> 
 

 