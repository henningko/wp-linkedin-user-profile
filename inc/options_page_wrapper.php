<div class="wrap">
    
    <div id="icon-options-general" class="icon32"></div>
    <h2><?php _e('LinkedIn User Profile Settings', 'linkedin-user-profile'); ?></h2>
    
    <div id="poststuff">
    
        <div id="post-body" class="metabox-holder">
        
            <!-- main content -->
            <div id="post-body-content">
                
                <div class="meta-box-sortables ui-sortable">
                    
                    <div class="postbox">
                    
                        <h3><span>Set up your LinkedIn App Credentials</span></h3>
                        <div class="inside">

                            <form method="post" action="">

                                <table class="form-table">

                                    <tr valign="top">
                                        <td scope="row">
                                            <label for="linkedin_api_key">API Key</label>
                                        </td>
                                        <td>
                                            <input name="linkedin_api_key" id="" type="text" value="<?php echo $options['linkedin_api_key']; ?>" class="regular-text" />
                                        </td>
                                    </tr>

                                    <tr valign="top">
                                        <td scope="row">
                                            <label  for="linkedin_app_secret">App Secret</label>
                                        </td>
                                        <td>
                                            <input name="linkedin_app_secret" id="" type="text" value="<?php echo $options['linkedin_app_secret']; ?>" class="regular-text" />
                                        </td>
                                    </tr>

                                </table>
                                
                                <input type="hidden" name="linkedin_submitted" value="1" />
                                <p>
                                    <input class="button-primary" type="submit" name="Example" value="<?php _e( 'Save' ); ?>" />
                                </p>

                            </form>

                        </div> <!-- .inside -->
                    
                    </div> <!-- .postbox -->
                    
                </div> <!-- .meta-box-sortables .ui-sortable -->
                
            </div> <!-- post-body-content -->
            
        </div> <!-- #post-body .metabox-holder .columns-2 -->
        
        <br class="clear">
    </div> <!-- #poststuff -->
    
</div> <!-- .wrap -->