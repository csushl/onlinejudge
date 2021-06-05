<?php require_once("admin-header.php");

  if(isset($OJ_LANG)){
    require_once("../lang/$OJ_LANG.php");
  }
  $path_fix="../";
  $OJ_TEMPLATE="bs3";
?>
<html>
<head>
<title><?php echo $MSG_ADMIN?></title>
<link rel="stylesheet" href="<?php echo $OJ_CDN_URL.$path_fix."template/$OJ_TEMPLATE/"?>bootstrap-theme.min.css">
<!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
<script src="<?php echo $OJ_CDN_URL.$path_fix."template/$OJ_TEMPLATE/"?>jquery.min.js"></script>

<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
<script src="<?php echo $OJ_CDN_URL.$path_fix."template/$OJ_TEMPLATE/"?>bootstrap.min.js"></script>

</head>

<body>
<hr>
<a class='btn btn-block btn-sm' href="help.php" target="main" title="<?php echo $MSG_ADMIN?>"><b><?php echo $MSG_ADMIN?></b></a>
<hr>

<a class='btn btn-block btn-sm' href="../status.php" target="_top" title="<?php echo $MSG_HELP_SEEOJ?>"><b><?php echo $MSG_SEEOJ?></b></a><br>

<center>
<div class="btn-group-vertical" role="menu">

  <div class="btn-group" role="menu">
    <button type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <?php echo $MSG_NEWS."-".$MSG_ADMIN ?> <span class="caret"></span>
    </button>
    <div class="dropdown-menu">
<?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])){?>
      <a class="dropdown-item btn-sm" href="setmsg.php" target="main" title="<?php echo $MSG_HELP_SETMESSAGE?>"><b><?php echo $MSG_NEWS."-".$MSG_SETMESSAGE?></b></a>
      <a class="dropdown-item btn-sm" href="news_list.php" target="main" title="<?php echo $MSG_HELP_NEWS_LIST?>"><b><?php echo $MSG_NEWS."-".$MSG_LIST?></b></a>
      <a class="dropdown-item btn-sm" href="news_add_page.php" target="main" title="<?php echo $MSG_HELP_ADD_NEWS?>"><b><?php echo $MSG_NEWS."-".$MSG_ADD?></b></a>
<?php }?>
    </div>
  </div>
  <div class="btn-group" role="menu">
    <button type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <?php echo $MSG_USER."-".$MSG_ADMIN ?> <span class="caret"></span>
    </button>
    <div class="dropdown-menu">
<?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset( $_SESSION[$OJ_NAME.'_'.'password_setter'])){?>
      <a class="dropdown-item btn-sm" href="user_list.php" target="main" title="<?php echo $MSG_HELP_USER_LIST?>"><b><?php echo $MSG_USER."-".$MSG_LIST?></b></a>
<?php }?>
<?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])){?>
      <a class="dropdown-item btn-sm" href="user_add.php" target="main" title="<?php echo $MSG_HELP_USER_ADD?>"><b><?php echo $MSG_USER."-".$MSG_ADD?></b></a>
<?php }?>
<?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset( $_SESSION[$OJ_NAME.'_'.'password_setter'])){?>
      <a class="dropdown-item btn-sm" href="changepass.php" target="main" title="<?php echo $MSG_HELP_SETPASSWORD?>"><b><?php echo $MSG_USER."-".$MSG_SETPASSWORD?></b></a>
<?php }?>
<?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])){?>
      <a class="dropdown-item btn-sm" href="privilege_list.php" target="main" title="<?php echo $MSG_HELP_PRIVILEGE_LIST?>"><b><?php echo $MSG_USER."-".$MSG_PRIVILEGE."-".$MSG_LIST?></b></a>
      <a class="dropdown-item btn-sm" href="privilege_add.php" target="main" title="<?php echo $MSG_HELP_ADD_PRIVILEGE?>"><b><?php echo $MSG_USER."-".$MSG_PRIVILEGE."-".$MSG_ADD?></b></a>
<?php }?>
    </div>
  </div>

  <div class="btn-group" role="menu">
    <button type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <?php echo $MSG_PROBLEM."-".$MSG_ADMIN ?> <span class="caret"></span>
    </button>
    <div class="dropdown-menu">
<?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'problem_editor'])||isset($_SESSION[$OJ_NAME.'_'.'contest_creator'])) {?>
      <a class="dropdown-item btn-sm" href="problem_list.php" target="main" title="<?php echo $MSG_HELP_PROBLEM_LIST?>"><b><?php echo $MSG_PROBLEM."-".$MSG_LIST?></b></a>
<?php }
      if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'problem_editor'])) {?>
      <a class="dropdown-item btn-sm" href="problem_add_page.php" target="main" title="<?php echo html_entity_decode($MSG_HELP_ADD_PROBLEM)?>"><b><?php echo $MSG_PROBLEM."-".$MSG_ADD?></b></a>
      <a class="dropdown-item btn-sm" href="problem_import.php" target="main" title="<?php echo $MSG_HELP_IMPORT_PROBLEM?>"><b><?php echo $MSG_PROBLEM."-".$MSG_IMPORT?></b></a>
      <a class="dropdown-item btn-sm" href="problem_export.php" target="main" title="<?php echo $MSG_HELP_EXPORT_PROBLEM?>"><b><?php echo $MSG_PROBLEM."-".$MSG_EXPORT?></b></a>
<?php }?>
    </div>
  </div>


  <div class="btn-group" role="menu">
    <button type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <?php echo $MSG_CONTEST."-".$MSG_ADMIN ?> <span class="caret"></span>
    </button>
    <div class="dropdown-menu">
<?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])||isset($_SESSION[$OJ_NAME.'_'.'contest_creator'])){?>
      <a class="dropdown-item btn-sm" href="contest_list.php" target="main"  title="<?php echo $MSG_HELP_CONTEST_LIST?>"><b><?php echo $MSG_CONTEST."-".$MSG_LIST?></b></a>
      <a class="dropdown-item btn-sm" href="contest_add.php" target="main"  title="<?php echo $MSG_HELP_ADD_CONTEST?>"><b><?php echo $MSG_CONTEST."-".$MSG_ADD?></b></a>
      <a class="dropdown-item btn-sm" href="user_set_ip.php" target="main" title="<?php echo $MSG_SET_LOGIN_IP?>"><b><?php echo $MSG_CONTEST."-".$MSG_SET_LOGIN_IP?></b></a>
      <a class="dropdown-item btn-sm" href="team_generate.php" target="main" title="<?php echo $MSG_HELP_TEAMGENERATOR?>"><b><?php echo $MSG_CONTEST."-".$MSG_TEAMGENERATOR?></b></a>
      <a class="dropdown-item btn-sm" href="team_generate2.php" target="main" title="<?php echo $MSG_HELP_TEAMGENERATOR?>"><b><?php echo $MSG_CONTEST."-".$MSG_TEAMGENERATOR?></b></a>
<?php }?>
    </div>
  </div>

<?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])){?>
  <div class="btn-group" role="menu">
    <button type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <?php echo $MSG_SYSTEM."-".$MSG_ADMIN ?> <span class="caret"></span>
    </button>
    <div class="dropdown-menu">
      <a class="dropdown-item btn-sm" href="rejudge.php" target="main" title="<?php echo $MSG_HELP_REJUDGE?>"><b><?php echo $MSG_SYSTEM."-".$MSG_REJUDGE?></b></a>      
      <a class="dropdown-item btn-sm" href="source_give.php" target="main" title="<?php echo $MSG_HELP_GIVESOURCE?>"><b><?php echo $MSG_SYSTEM."-".$MSG_GIVESOURCE?></b></a>
      <a class="dropdown-item btn-sm" href="../online.php" target="main"><b><?php echo $MSG_SYSTEM."-".$MSG_HELP_ONLINE?></b></a>      
      <a class="dropdown-item btn-sm" href="update_db.php" target="main" title="<?php echo $MSG_HELP_UPDATE_DATABASE?>"><b><?php echo $MSG_SYSTEM."-".$MSG_UPDATE_DATABASE?></b></a>
    </div>
  </div>
<?php }?>

</div>
</center>
<!--
<?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])){?>
  <br><a class='btn btn-block btn-sm' href="https://github.com/zhblue/hustoj/" target="_blank"><b>HUSTOJ</b></a>
  <br><center><a class="btn btn-sm" target='_blank' href="https://github.com/zhblue/hustoj/blob/master/wiki/FAQ.md"><?php echo $MSG_ADMIN." ".$MSG_FAQ?></a></center>
  <br><a class='btn btn-block btn-sm' href="https://github.com/zhblue/freeproblemset/" target="_blank"><b>FreeProblemSet</b></a>
  <br><a class='btn btn-block btn-sm' href="http://tk.hustoj.com" target="_blank"><b>自助题库</b></a>
  <br><a class='btn btn-block btn-sm' href="http://shang.qq.com/wpa/qunwpa?idkey=d52c3b12ddaffb43420d308d39118fafe5313e271769277a5ac49a6fae63cf7a" target="_blank">手机QQ加官方<br>群23361372</a>
<?php }?>
-->
<?php if (isset($_SESSION[$OJ_NAME.'_'.'administrator'])&&!$OJ_SAE){?>
  <a href="problem_copy.php" target="main" title="Create your own data"><font color="eeeeee">CopyProblem</font></a> <br>
  <a href="problem_changeid.php" target="main" title="Danger,Use it on your own risk"><font color="eeeeee">ReOrderProblem</font></a>
<?php }?>
</body>
</html>
