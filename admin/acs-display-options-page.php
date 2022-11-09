<?php

/**
 * Provide a backend area view for the plugin
 *
 * This file is used to dispaly the settings of the plugin.
 *
 * @link       
 * @since      1.0.0
 *
 * @package    ACS_Advanced_Search
 * @subpackage ACS_Advanced_Search/admin
 */
?>
<div class="wrap acs-plugin-page">
    <div class="acs-container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="acs-page-header">
                    <h1 class="acs-page-heading"><?php _e( 'ACS Advanced Search', 'acs-advanced-custom-search' ); ?></h1>
		</div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-8">
				<div class="acs-box">
					<div class="acs-box-title">
						<h3><?php //_e( 'Select Fields to include in Advanced Search', 'wp-advanced-custom-search' ); ?></h3>
					</div>
					<div class="acs-box-container">
						<form method="post" action="options.php">
							<?php
								settings_fields( 'acs-advanced-custom-search-settings' );
								do_settings_sections( 'acs-advanced-custom-search-settings' );
								submit_button(__('Save Changes'), 'primary', 'submit', false);
							?>
						</form>
					</div>
				</div>
				<div class="acs-box">
					<div class="acs-box-title">
						<h3><?php _e( 'Customization', 'acs-advanced-custom-search' ); ?></h3>
					</div>
					<div class="acs-box-container">
						<p><?php _e( 'If you want to use Advanced Search anywhere on the website, you can use our shortcode:', 'acs-advanced-custom-search' ); ?></p>
						<input type="text" value="[acs-advanced-custom-search]" readonly>
                                                <p><?php _e( 'You can also use shortcode in php template', 'acs-advanced-custom-search' );?></p>
                                                <input type="text" value="do_shortcode('[acs-advanced-custom-search]');" readonly style="width: 350px;"/>
                                        </div>
				</div>
			</div>
        </div>
    </div>
</div>

