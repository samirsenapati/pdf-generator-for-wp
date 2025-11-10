<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://finaldoc.com
 * @since      1.0.0
 *
 * @package    FinalPDF
 * @subpackage FinalPDF/admin/onboarding
 */

global $pagenow, $finalpdf_obj;
if ( empty( $pagenow ) || 'plugins.php' != $pagenow ) {
	return false;
}

$finalpdf_onboarding_form_deactivate = apply_filters( 'wps_finalpdf_deactivation_form_fields', array() );
?>
<?php if ( ! empty( $finalpdf_onboarding_form_deactivate ) ) : ?>
	<div class="mdc-dialog mdc-dialog--scrollable">
		<div class="wps-finalpdf-on-boarding-wrapper-background mdc-dialog__container">
			<div class="wps-finalpdf-on-boarding-wrapper mdc-dialog__surface" role="alertdialog" aria-modal="true" aria-labelledby="my-dialog-title" aria-describedby="my-dialog-content">
				<div class="mdc-dialog__content">
					<div class="wps-finalpdf-on-boarding-close-btn">
						<a href="#">
							<span class="finalpdf-close-form material-icons wps-finalpdf-close-icon mdc-dialog__button" data-mdc-dialog-action="close">clear</span>
						</a>
					</div>

					<h3 class="wps-finalpdf-on-boarding-heading mdc-dialog__title"></h3>
					<p class="wps-finalpdf-on-boarding-desc"><?php esc_html_e( 'May we have a little info about why you are deactivating?', 'finalpdf' ); ?></p>
					<form action="#" method="post" class="wps-finalpdf-on-boarding-form">
						<?php
						$finalpdf_obj->wps_finalpdf_plug_generate_html( $finalpdf_onboarding_form_deactivate );
						?>
						<div class="wps-finalpdf-on-boarding-form-btn__wrapper mdc-dialog__actions">
							<div class="wps-finalpdf-on-boarding-form-submit wps-finalpdf-on-boarding-form-verify ">
								<input type="submit" class="wps-finalpdf-on-boarding-submit wps-on-boarding-verify mdc-button mdc-button--raised" value="Send Us">
							</div>
							<div class="wps-finalpdf-on-boarding-form-no_thanks">
								<a href="#" class="wps-deactivation-no_thanks mdc-button"><?php esc_html_e( 'Skip and Deactivate Now', 'finalpdf' ); ?></a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="mdc-dialog__scrim"></div>
	</div>
<?php endif; ?>
