<?php
	$margin=0;
	if($_POST['kind']=="l"){
		$margin=85;
    $function="fetchApplikes(this)";
	}elseif ($_POST['kind']=="c") {
		$margin=30;
    $function="fetchAppComment(this)";
	}
echo '<div id="viewApps" class="'.$_POST['kind'].'" style="display:none; float: right; margin-right: '.$margin.'px;">
        <table style="text-align: center; border-collapse: collapse;">
          <tr>
            <td height="25px" width="40px">
              <a href="javascript:void(0);" data-num="'.$_POST['yn'].'" class="sub_cbb bt" onclick="'.$function.';" data-app="yn" data-postid="'.$_POST['postID'].'">
            </a></td>
            <td width="40px">
              <a href="javascript:void(0);" data-num="'.$_POST['fb'].'" class="sub_cbb_2 sub_cbb bt" onclick="'.$function.';" data-app="fb" data-postid="'.$_POST['postID'].'">
            </a></td>
          </tr>
          <tr>
            <td><a href="javascript:void(0);" data-num="'.$_POST['yn'].'" onclick="'.$function.';" data-app="yn" data-postid="'.$_POST['postID'].'" class="font-link yn" style="color:#F16521;">'.$_POST['yn'].'</a></td>
            <td><a href="javascript:void(0);" data-num="'.$_POST['fb'].'" onclick="'.$function.';" data-app="fb" data-postid="'.$_POST['postID'].'" class="font-link" style="color:#F16521;">'.$_POST['fb'].'</a></td>
          </tr>
        </table>
      </div>';
?> 