<div class="admin-bar-container">
    <nav id="admin-bar" class="admin-bar">
    <?php
    $links = array();
    if($user = current_user()) {
        $links = array(
            array(
                'label' => __('Angemeldet als %s', $user->name),
                'uri' => ''
            ),
            array(
                'label' => __('Mein Konto'),
                'uri' => admin_url('/users/edit-test/'.$user->id)
            ),
            array(
                'label' => __('Administration'),
                'uri' => admin_url('/')
            ),
            array(
                'label' => __('Log Out'),
                'uri' => url('/users/logout')
            )
        );
    }
    echo nav($links, 'public_navigation_admin_bar');
    ?>
    </nav>
</div>