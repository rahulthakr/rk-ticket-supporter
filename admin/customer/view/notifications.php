<?php if(isset($_SESSION['notice'])): ?>
<div class="alert alert-<?=$_SESSION['notice']?> alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&#215;</button>
    <strong>Notification: </strong> <?=$_SESSION['notice_text']?>
</div>
<?php $this->notice_front_falsh_remove(); ?>
<?php endif; ?>