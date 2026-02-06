<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.havasdigitalfactory.com/
 * @since      1.0.0
 *
 * @package    Df_guidelines_checker
 * @subpackage Df_guidelines_checker/admin/partials
 */

// check user capabilities
if ( ! current_user_can( 'manage_options' ) ) {
	return;
}

// first step : little configuration
$hdf_audit_configuration = get_option( 'hdf_audit_configuration' );

if ( empty( $hdf_audit_configuration ) ):
	?>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1><i data-feather="aperture"></i> <?php echo( esc_html( get_admin_page_title() ) . ' - ' . __( 'Configuration', $this->plugin_name ) ); ?></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <form id="df_audit_config_form">
                    <div class="d-grid gap-3">
                        <div class="row">
                            <div class="col-12">
                                <label for="project_name"><?php _e( 'Project name :', $this->plugin_name ); ?></label>
                                <input type="text" id="project_name" name="project_name" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <label for="id_det"><?php _e( 'ID DET :', $this->plugin_name ); ?></label>
                                <input type="text" id="id_det" name="id_det" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-primary" type="button" id="df_audit_config_save">
                                    <span id="df_audit_loader" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
									<?php _e( 'Save the configuration', $this->plugin_name ); ?>
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div id="df_audit_result_msg" class="d-none"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php
else:
	?>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1><i data-feather="aperture"></i> <?php echo( esc_html( get_admin_page_title() ) ); ?></h1>
            </div>
        </div>
		<?php
		// retrieve previous saved datas if exist
		$distant_id                   = '';
		$previous_hdf_audit_checklist = get_option( 'hdf_audit_checklist' . mb_convert_case( $env, MB_CASE_UPPER ) );
		
		if ( ! empty( $previous_hdf_audit_checklist ) ):
			$previous_hdf_audit_checklist = $this->json_to_array_clean( $previous_hdf_audit_checklist );
			
			$score      = $previous_hdf_audit_checklist['scoring'];
			$distant_id = $previous_hdf_audit_checklist['distant_id'];
			
			if ( ! empty( $score ) ):
				$progress_class = 'bg-danger';
				$ranges = array(
					'bg-danger'  => 0,
					'bg-warning' => 25,
					'bg-info'    => 75,
					'bg-success' => 100
				);
				
				foreach ( $ranges as $class => $range ):
					if ( $score < $range ):
						continue;
					endif;
					
					$progress_class = $class;
				endforeach;
				?>
                <div class="row">
                    <div class="col-12">
                        <div class="progress">
                            <div id="audit_progress" class="progress-bar <?php esc_attr_e( $progress_class ); ?> progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?php esc_attr_e( $score ); ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php esc_attr_e( $score ); ?>%">
								<?php printf( __( '%d%% Complete', $this->plugin_name ), $score ); ?>
                            </div>
                        </div>
                    </div>
                </div>
			<?php
			endif;
		endif;
		?>
        <div class="row">
            <div class="col-12">
                <h5><?php echo( mb_convert_case( $env, MB_CASE_UPPER ) . ' ' . __( 'environment', $this->plugin_name ) ); ?> (<a href="<?php echo( esc_url( $escaped_url ) ); ?>"><?php echo( sprintf( __( 'switch to %s environment', $this->plugin_name ), mb_convert_case( $switch_to[ $env ], MB_CASE_UPPER ) ) ); ?></a>)</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <form id="df_audit_form">
                    <div class="d-grid gap-3">
                        <div class="row">
                            <div class="col-12">
                                <input type="hidden" name="env_audit" value="<?php esc_attr_e( mb_convert_case( $env, MB_CASE_UPPER ) ); ?>">
                                <input type="hidden" name="automated_audit" value="<?php esc_attr_e( json_encode( $checks ) ); ?>">
                                <input type="hidden" id="audit_distant_id" name="distant_id" value="<?php esc_attr_e( $distant_id ); ?>">
                                <h3><?php _e( 'Checklist DEV BACK :', $this->plugin_name ); ?></h3>
                                <div class="accordion accordion-flush" id="hdf_accordion_audit">
									<?php
									$cpt              = 1;
									
									foreach ( $checks as $id => $section ):
										$expanded = 'false';
										$class        = '';
										$button_class = 'collapsed';
										$id_heading   = 'hdf_heading_auto' . $cpt;
										$id_collapse  = 'hdf_collapse_auto' . $cpt;
										
										if ( 1 === $cpt ):
											$class        = 'show';
											$expanded     = 'true';
											$button_class = '';
										endif;
										// no error ?
										if ( $section['global_status'] ) :
											$badge_class = 'bg-success';
										else:
											$badge_class = 'bg-light text-dark';
										endif;
										?>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="<?php echo( esc_attr( $id_heading ) ); ?>">
                                                <button class="hdf-accordion-button accordion-button <?php echo( esc_attr( $button_class ) ); ?>" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo( esc_attr( $id_collapse ) ); ?>" aria-expanded="<?php echo( esc_attr( $expanded ) ); ?>" aria-controls="<?php echo( esc_attr( $id_collapse ) ); ?>">
                                                    <span class="hdf_audit_title_button"><?php echo( $section['icon'] ); ?>&nbsp;<?php echo( esc_html( $section['title'] ) ); ?></span>
                                                    <span class="hdf_audit_score badge <?php echo( esc_attr( $badge_class ) ); ?>"><?php echo( $section['score'] ); ?></span>
                                                </button>
                                            </h2>
                                            <div id="<?php echo( esc_attr( $id_collapse ) ); ?>" class="accordion-collapse collapse <?php echo( esc_attr( $class ) ); ?>" aria-labelledby="<?php echo( esc_attr( $id_heading ) ); ?>" data-bs-parent="#hdf_accordion_audit">
                                                <div class="accordion-body">
													<?php
													if ( ! empty( $section['notice'] ) ):
														?>
                                                        <div class="panel-body">
															<?php echo( $section['notice'] ); ?>
                                                        </div>
													<?php
													endif;
													?>
                                                    <ul class="list-group">
														<?php
														foreach ( $section['content'] as $data ):
															$css_class = '';
															$icon = '<i data-feather="check"></i>';
															$snippet = '';
															$download_uri = '';
															
															if ( isset( $data['status'] ) && ( 'nok' === $data['status'] ) ) {
																$css_class = 'list-group-item-danger';
																$icon      = '<i data-feather="x"></i>';
															}
															
															if ( ! empty( $data['snippet'] ) ) {
																$snippet = '<div><code>' . $data['snippet'] . '</code></div>';
															}
															
															if ( ! empty( $data['download_uri'] ) ) {
																$download_uri = $data['download_uri'];
															}
															?>
                                                            <li class="list-group-item <?php echo( $css_class ); ?>"><?php echo( $icon . ' ' . $data['text'] . $snippet . $download_uri ); ?></li>
														<?php
														endforeach;
														?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
										<?php
										$cpt ++;
									endforeach;
									
									$cpt              = 1;
									
									foreach ( $this->hdf_checklist as $id_section => $section ):
										$expanded = 'false';
										$class        = '';
										$button_class = 'collapsed';
										$id_heading   = 'hdf_heading' . $cpt;
										$id_collapse  = 'hdf_collapse' . $cpt;
										// calcul score
										$total = 0;
										$score = 0;
										
										foreach ( $section['checks'] as $id_checklist => $item_checklist ):
											$input_checked = '';
											
											if ( isset( $previous_hdf_audit_checklist[ $id_section ]['checks'][ $id_checklist ]['value'] ) && ( 'ok' === $previous_hdf_audit_checklist[ $id_section ]['checks'][ $id_checklist ]['value'] ) ):
												$score ++;
											endif;
											
											$total ++;
										endforeach;
										
										if ( $total === $score ) :
											$badge_class = 'bg-success';
										else:
											$badge_class = 'bg-light text-dark';
										endif;
										?>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="<?php echo( esc_attr( $id_heading ) ); ?>">
                                                <button class="hdf-accordion-button accordion-button <?php echo( esc_attr( $button_class ) ); ?>" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo( esc_attr( $id_collapse ) ); ?>" aria-expanded="<?php echo( esc_attr( $expanded ) ); ?>" aria-controls="<?php echo( esc_attr( $id_collapse ) ); ?>">
                                                    <span class="hdf_audit_title_button"><?php echo( $section['icon'] ); ?>&nbsp;<?php echo( $section['label'] ); ?></span>
                                                    <span class="hdf_audit_score badge <?php echo( esc_attr( $badge_class ) ); ?>"><?php echo( $score . '/' . $total ); ?></span>
                                                </button>
                                            </h2>
                                            <div id="<?php echo( esc_attr( $id_collapse ) ); ?>" class="accordion-collapse collapse <?php echo( esc_attr( $class ) ); ?>" aria-labelledby="<?php echo( esc_attr( $id_heading ) ); ?>" data-bs-parent="#hdf_accordion_audit">
                                                <div class="accordion-body">
                                                    <input type="hidden" name="checklist[<?php echo( $id_section ); ?>][label]" value="<?php esc_attr_e( $section['label'] ); ?>">
													<?php
													foreach ( $section['checks'] as $id_checklist => $item_checklist ):
														$input_checked = '';
														
														if ( isset( $previous_hdf_audit_checklist[ $id_section ]['checks'][ $id_checklist ]['value'] ) && ( 'ok' === $previous_hdf_audit_checklist[ $id_section ]['checks'][ $id_checklist ]['value'] ) ):
															$input_checked = 'checked="checked"';
														endif;
														?>
                                                        <div class="form-group">
                                                            <label for="<?php echo( esc_attr( $id_checklist ) ); ?>"><?php echo( $cpt . ' - ' . $item_checklist['text'] ); ?></label>
                                                            <div class="row">
                                                                <div class="col-md-2 col-md-offset-1">
                                                                    <label class="hdf_switch">
                                                                        <input <?php echo( $input_checked ); ?> type="checkbox" value="ok" id="checklist[<?php echo( $id_section ); ?>][checks][<?php echo( esc_attr( $id_checklist ) ); ?>][value]" name="checklist[<?php echo( $id_section ); ?>][checks][<?php echo( esc_attr( $id_checklist ) ); ?>][value]">
                                                                        <span class="hdf_slider hdf_round"></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-md-7 col-md-offset-1">
                                                                    <textarea class="form-control" rows="5" id="checklist[<?php echo( $id_section ); ?>][checks][<?php echo( esc_attr( $id_checklist ) ); ?>][comments]" name="checklist[<?php echo( $id_section ); ?>][checks][<?php echo( esc_attr( $id_checklist ) ); ?>][comments]"><?php
                                                                        if ( ! empty( $previous_hdf_audit_checklist[ $id_section ]['checks'][ $id_checklist ]['comments'] ) ):
	                                                                        echo( $previous_hdf_audit_checklist[ $id_section ]['checks'][ $id_checklist ]['comments'] );
                                                                        endif;
                                                                        ?></textarea>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="checklist[<?php echo( $id_section ); ?>][checks][<?php echo( esc_attr( $id_checklist ) ); ?>][label]" value="<?php esc_attr_e( $item_checklist['text'] ); ?>">
                                                        </div>
														<?php
														$cpt ++;
													endforeach;
													?>
                                                </div>
                                            </div>
                                        </div>
									<?php
									endforeach;
									?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-primary" type="button" id="df_audit_save">
                                    <span id="df_audit_loader" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
									<?php _e( 'Save the audit', $this->plugin_name ); ?>
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div id="df_audit_result_msg" class="d-none"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php
endif;
