<?php
$userTitle = $user->username;
if ($userTitle != '') {
    $userTitle = ': ' . html_escape($userTitle) . ' ';
} else {
    $userTitle = '';
}
// $userTitle = __('Edit User #%s', $user->id) . $userTitle;
$userTitle = __('Konto-Einstellungen') . $userTitle;
echo head(array('title' => $userTitle, 'bodyclass' => 'users'));
echo common('users-nav', array('user' => $user));
echo flash();
?>
<form id="change-password" method="post">
<section class="seven columns alpha">
    <p class='explanation'>* <?php echo __('required field'); ?></p>
    <?php echo $this->form; ?>
</section>
<section class="three columns omega">
    <div id="save" class="panel">
        <?php echo $this->formSubmit('submit', __('Save Password'), array('class' => 'submit big green button')); ?>
    </div>
</section>
</form>

<?php echo foot();?>
