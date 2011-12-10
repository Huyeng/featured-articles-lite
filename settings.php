<?php
/**
 * @package Featured articles PRO - Wordpress plugin
 * @author CodeFlavors ( http://www.codeflavors.com )
 * @version 2.4
 */

/* set the current page url */
$current_page = menu_page_url('featured-articles-lite/settings.php', false);


if( isset( $_POST['fa_options'] ) && !empty($_POST['fa_options']) ){
	if( !wp_verify_nonce($_POST['fa_options'],'featured-articles-set-options') ){
		die('Sorry, your action is invalid.');
	}else{
			
		$plugin_options = array(
			'complete_unistall'=>0
		);		
		foreach( $plugin_options as $option=>$value ){
			if( isset( $_POST[$option] ) ){
				$plugin_options = $_POST[$option];
			}	
		}
		
		// get wordpress roles
		$roles = $wp_roles->get_names();
		foreach( $roles as $role=>$name ){
			// administrator has default access so skip this role
			if( 'administrator' == $role ) continue;
			// add/remove editing capabilities
			if( isset( $_POST['role'][$role] ) ){
				$wp_roles->add_cap($role, FA_CAPABILITY);
			}else{
				$wp_roles->remove_cap($role, FA_CAPABILITY);
			}
		}
		FA_plugin_options();
		wp_redirect($current_page);
		exit();
	}	
}

// Folder path processing
if( isset($_POST['fa_themes_folder_nonce']) && wp_verify_nonce($_POST['fa_themes_folder_nonce'], 'update_fa_themes_folder') ){
	$folder_change_result = FA_set_themes_folder( $_POST['fa_themes_folder'] );
}

$options = FA_plugin_options();
?>
<div class="wrap">
	<div class="icon32" id="icon-options-general"><br></div>
    <h2 id="add-new-user">Featured Articles - plugin settings</h2>
    <form method="post" action="<?php echo $current_page;?>&noheader=true">
        <?php wp_nonce_field('featured-articles-set-options', 'fa_options');?>
        <table class="form-table">
        <tbody>
        <tr valign="top">
            <th scope="row">
            	<label for="">Set plugin access: </label>
            </th>
            <td>            	
                <?php
					$roles = $wp_roles->get_names();
					foreach( $roles as $role=>$name ):
						if( 'administrator' == $role ){
							continue;
						}	
						$r = $wp_roles->get_role( $role );
						$checked = array_key_exists( FA_CAPABILITY, $r->capabilities ) ? ' checked="checked"' : '';							
				?>
                	<label><input type="checkbox" name="role[<?php echo $role;?>]" value="1"<?php echo $checked;?> style="width:auto;" /> <?php echo $name;?></label><br />
                 
                <?php endforeach;?>
            </td>
            <td>
            	<span class="description">
            		You can grant permissions to users depending on their role. Admins have default access to all plugin areas.
            	</span>
            </td>
        </tr>
        <tr valign="top">
        	<th scope="row">
            	<label for="complete_uninstall">Enable full uninstall:<br />
            </th>
            <td><input type="checkbox" name="complete_uninstall" id="complete_uninstall" value="1"<?php if($options['complete_uninstall']):?> checked="checked"<?php endif;?> /></td>
        	<td>
        		<?php if($options['complete_uninstall']):?>
                <span style="color:red;">
                	While we don't expect anything bad to happen we recommended that you first back-up your database before completely removing the plugin.<br />
                	Complete plugin removal will be performed after you deactivate the plugin and delete it from Wordpress Plugins page.<br />
                </span>
                <?php else:?>
            	<span class="description">If checked, when the plugin is uninstalled from plugins page all data (sliders, slides, options and meta fields) will also be removed from database.</span>
                <?php endif;?>
        	</td>
        </tr>
        <tr valign="top">
        	<th scope="row">
            	<label for="complete_uninstall">Enable automatic slider insertion:<br />                                
            </th>
            <td><input type="checkbox" name="auto_insert" id="complete_uninstall" value="1"<?php if($options['auto_insert']):?> checked="checked"<?php endif;?> /></td>
            <td>
            	<span class="description">
					When enabled it will display on slider editing/creation a new panel that allows insertion into category pages, home page and pages of slides without the need of additional code.<br />
					Please note that this kind of slider insertion in your pages will display the slider before the loop you have in those pages. For more precise display into your pages we recommend using the manual insertion or the shortcode insertion.
				</span>
            </td>
        </tr>
        </tbody>
        </table>
<p class="submit">
    <input type="submit" value="Save settings" class="button-primary" id="addusersub" name="adduser">
</p>        
    </form>
    
    <h2>Change FeaturedArticles themes folder</h2>
    
    <p>Do this only if you created custom themes for your plugin. If you're using the default themes, you can leave this setting as is.</p>
    
    <ol>
    	<li>Create a folder in <?php echo WP_CONTENT_DIR;?>.<br /> Naming it is your own choince, it just needs to be web safe (only letters and underscore will keep you safe; <strong><em>fa_themes</em></strong> for example is a valid name );</li>
    	<li><strong>Copy</strong> ALL themes from the current location to the new folder you created on step 1.</li>
    	<li>Add folder path here and save. You don't need the whole server path, only path from inside wp_content folder.<br /> We'll do all checking before setting anything. If something is wrong, we'll let you know.</li>
    	<li>After successful save, you'll see the new path in text field. You can now remove all themes from the old location if you want to.</li>
    </ol>	
    
    <form method="post" action="">
    	<?php wp_nonce_field('update_fa_themes_folder', 'fa_themes_folder_nonce');?>
    	<label for="fa_themes_folder" style="font-weight:bold;">Enter only the path from within your current wp_content folder:</label><br />
    	<?php echo WP_CONTENT_DIR;?>/<input type="text" name="fa_themes_folder" id="fa_themes_folder" value="<?php FA_themes_path(false, true);?>" size="50" />    
    	
    	<?php if( isset($folder_change_result) ):?>
    		<ul>
    		<?php if( is_wp_error( $folder_change_result ) ):?>
    			<?php 
    				$codes = $folder_change_result->get_error_codes();
    				foreach( $codes as $err_code ):?>
    				<li style="color:red;"><?php echo $folder_change_result->get_error_message($err_code);?></li>
    				<?php endforeach;
    			?>
    		<?php else:?>
    			<li style="color:green;">Done, folder path changed. See if everything is OK.</li>
    		<?php endif;?>
    		</ul>
    	<?php endif;?>
    	
    	<?php submit_button('Save new path');?>
    </form>
</div>