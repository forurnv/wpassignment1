<?php
/**
 * Outputs Settings View for a Post Type
 *
 * @since    3.0.0
 */
?>
    
<!-- Post Type -->
<div class="postbox wpzinc-vertical-tabbed-ui">

    <!-- Second level tabs -->
    <ul class="wpzinc-nav-tabs wpzinc-js-tabs" data-panels-container="#profiles-container" data-panel=".profile" data-active="wpzinc-nav-tab-vertical-active">
        <!-- Default Settings -->
        <li class="wpzinc-nav-tab default">
            <a href="#profile-default" class="wpzinc-nav-tab-vertical-active">
                <?php _e( 'Defaults', $this->base->plugin->name ); ?>
            </a>
        </li>

        <?php
        // Account tabs
        if ( ! is_wp_error( $profiles ) ) {
            foreach ( $profiles as $key => $profile ) {
                $profile_enabled = $this->get_setting( $post_type, '[' . $profile['id'] . '][enabled]', 0 );
                ?>
                <li class="wpzinc-nav-tab <?php echo $profile['service']; ?>">
                    <a href="#profile-<?php echo $profile['id']; ?>" class="<?php echo ( $profile_enabled ? ' enabled' : ' disabled' ); ?>" title="<?php echo $profile['formatted_service'] . ': ' . $profile['formatted_username']; ?>">
                        <?php 
                        echo $profile['formatted_username'];

                        if ( $profile_enabled ) {
                            ?>
                            <span class="dashicons dashicons-yes"></span>
                            <?php
                        }
                        ?>
                    </a>
                </li>
                <?php 

            }
        }
        unset( $profile );
        ?>
    </ul>

    <!-- Content -->
    <div id="profiles-container" class="wpzinc-nav-tabs-content no-padding">
        <!-- Defaults -->
        <?php
        $profile_id = 'default';
        ?>
        <div id="profile-<?php echo $profile_id; ?>" class="profile">
            <!-- Action Tabs -->
            <ul class="wpzinc-nav-tabs-horizontal wpzinc-js-tabs" data-panels-container="#profile-<?php echo $profile_id; ?>-actions-container" data-panel=".action" data-active="wpzinc-nav-tab-horizontal-active">
                <?php 
                foreach ( $post_actions as $action => $action_label ) {
                    $action_enabled = $this->get_setting( $post_type, '[' . $profile_id . '][' . $action .'][enabled]', 0 );
                    ?>
                    <li class="wpzinc-nav-tab-horizontal <?php echo $action; ?>">
                        <a href="#profile-<?php echo $profile_id; ?>-<?php echo $action; ?>" class="<?php echo ( $action_enabled ? ' enabled' : '' ) . ( $action == 'publish' ? ' wpzinc-nav-tab-horizontal-active' : '' ); ?>">
                            <?php 
                            echo $action_label; 

                            if ( $action_enabled ) {
                                ?>
                                <span class="dashicons dashicons-yes"></span>
                                <?php
                            }
                            ?>
                        </a>
                    </li>
                    <?php
                }
                ?>
            </ul>

            <div id="profile-<?php echo $profile_id; ?>-actions-container">
                <?php
                // Iterate through Post Actions (Publish, Update etc)
                foreach ( $post_actions as $action => $action_label ) {
                    ?>
                    <div id="profile-<?php echo $profile_id; ?>-<?php echo $action; ?>" class="action">
                        <?php
                        require( $this->base->plugin->folder . 'vendor/views/settings-post-action.php' );
                        ?>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <!-- /Defaults -->

        <!-- Profiles -->
        <?php
        if ( is_wp_error( $profiles ) ) {
            ?>
            <div>
                <?php _e( 'Hmm, we couldn\'t fetch your social media profiles.  Please refresh the Page.', $this->base->plugin->name ); ?>
            </div>
            <?php
        } else {
            foreach ( $profiles as $key => $profile ) {
                $profile_id = $profile['id'];
                ?>
                <div id="profile-<?php echo $profile_id; ?>" class="profile">
                    <?php
                    require( $this->base->plugin->folder . 'vendor/views/settings-post-actionheader.php' );
                    ?>
                </div>
                <?php
            }
        }
        ?>
        <!-- /Profiles -->
    </div>
</div>
<!-- /post_type -->