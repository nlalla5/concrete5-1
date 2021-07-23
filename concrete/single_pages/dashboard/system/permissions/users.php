<?php defined('C5_EXECUTE') or die("Access Denied."); 
$app = \Concrete\Core\Support\Facade\Application::getFacadeApplication();
?>

<?php ob_start(); ?>
<?=View::element('permission/help');?>
<?php $help = ob_get_contents(); ?>
<?php ob_end_clean(); ?>

<form method="post" action="<?=$view->action('save')?>" role="form">
  <?=$app->make('helper/validation/token')->output('save_permissions')?>

	<?php
    $tp = new TaskPermission();
    if ($tp->canAccessTaskPermissions()) {
        ?>
    <?php View::element('permission/lists/user')?>
	<?php
    } else {
        ?>
		<p><?=t('You cannot access task permissions.')?></p>
	<?php
    } ?>

	<div class="ccm-dashboard-form-actions-wrapper">
	    <div class="ccm-dashboard-form-actions">
            <a href="<?=$view->url('/dashboard/system/permissions/users')?>" class="btn btn-secondary float-left"><?=t('Cancel')?></a>
            <button class="float-right btn btn-primary" type="submit" ><?=t('Save')?></button>
	    </div>
	</div>
</form>
