<?php
//help bootstrap  : https://apcpedagogie.com/le-systeme-de-grille-bootstrap/

require_once( 'config.php' );
require_once( 'inc/functions.php' );

include "vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;


//get json flexible content
$flexibles_content = getFlexiblesAcfJsonFile();

//init
$js_infos_projet_available_flexibles = array();

//on soummet le form
if ( $_SERVER["REQUEST_METHOD"] == "POST" && $_POST['send_form'] == 'ok' ) {

	//init errors
	$errors = [];

	//step1
	$fields['project_name']         = '';
	$fields['det']                  = '';
	$fields['type']                 = '';
	$fields['availables_flexibles'] = array();
	//step2
	$fields['page_titles']      = array();
	$fields['page_home']        = array();
	$fields['page_privacy']     = array();
	$fields['select_flexibles'] = array();

	//step1
	$fields['project_name']         = filter_var( $_POST['project_name'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES );
	$fields['det']                  = filter_var( $_POST['det'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES );
	$fields['type']                 = filter_var( $_POST['type'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES );
	$fields['availables_flexibles'] = $_POST['availables_flexibles'];
	//step2
	$fields['page_titles']         = $_POST['page_titles'];
	$fields['page_home']           = $_POST['page_home'];
	$fields['page_privacy']        = $_POST['page_privacy'];
	$fields['flexibles_selection'] = $_POST['flexibles_selection'];

	//check
	if ( empty( $fields['project_name'] ) ) {
		$errors['project_name'] = 'Vous devez saisir le nom du projet';
	}

	if ( empty( $fields['det'] ) ) {
		$errors['det'] = 'Vous devez saisir DET';
	}

	if ( ! preg_match( '/^http/', $fields['det'] ) ) {
		$errors['det'] = 'Le lien n\'est pas correct';
	}

	if ( empty( $fields['availables_flexibles'] ) ) {
		$errors['availables_flexibles'] = 'Vous devez sélectionner au moins un module';
	}

	if ( empty( $errors ) ) {

		$step1 = 'ok';

		//on génère la liste des flexibles sélectionnés à fournir au js
		$js_infos_projet_available_flexibles = array();
		foreach ( $flexibles_content as $key => $flexible ) {
			if ( array_key_exists( $key, $fields['availables_flexibles'] ) ) {
				$item['name']                          = $flexible['name'];
				$item['label']                         = $flexible['label'];
				$js_infos_projet_available_flexibles[] = $item;
			}
		}

		if ( $_POST['btn_submit'] == 'ok' ) {

			if ( empty( $fields['page_titles'] ) or empty( $fields['page_titles'][0] ) ) {
				$errors['page_titles'] = 'Vous devez saisir au moins un titre';
			}

			if ( empty( $fields['flexibles_selection'] ) ) {
				$errors['flexibles_selection'] = 'Vous devez saisir au moins un module';
			}

			if ( empty( $errors ) ) {

				//on traite les données pour les formater et les envoyer par mail au dev back.
				$datas                 = array();
				$datas['date']         = date( 'Y-m-d H:i:s' );
				$datas['project_name'] = $fields['project_name'];
				$datas['det']          = $fields['det'];
				$datas['type']         = $fields['type'];
				foreach ( $fields['availables_flexibles'] as $item ) {
					$datas['availables_flexibles'][] = $item;
				}
				foreach ( $fields['page_titles'] as $key => $item ) {
					$datas['pages'][ $key ]['title'] = $item;
				}
				foreach ( $fields['page_home'] as $key => $item ) {
					$datas['pages'][ $key ]['is_homepage'] = ( $item == "true" ) ? 1 : 0;
				}
				foreach ( $fields['page_privacy'] as $key => $item ) {
					$datas['pages'][ $key ]['is_privacy_page'] = ( $item == "true" ) ? 1 : 0;
				}
				foreach ( $fields['flexibles_selection'] as $key => $item ) {
					$datas['pages'][ $key ]['flexibles'] = stripslashes( $item );
				}

				$step2 = 'ok';

				//création du fichier json dans uploads
				$filename = sanitize_title( $datas['project_name'] ) . '-' . date( 'Ymd-His' ) . '.json';
				if ( ! file_exists( __DIR__ . '/uploads/' ) ) {
					mkdir( __DIR__ . '/uploads/', 0755, true ) or wp_die( "Unable to create folder " . __DIR__ . '/uploads/' );
				}
				$myfile = fopen( "uploads/" . $filename, "w" ) or wp_die( "Unable to create file uploads/" . $filename );
				fwrite( $myfile, json_encode( $datas, JSON_PRETTY_PRINT ) );
				fclose( $myfile );

				//envoi du fichier par mail
				$mail = new PHPMailer( true );
				$mail->IsMail();
				$mail->IsHTML( true );
				$mail->CharSet = 'utf-8';
				$mail->setFrom( EMAIL_FROM, NAME_FROM );

				$mail->addAddress( EMAIL_RECIPIENT_ADMIN );
				$mail->addCC( EMAIL_RECIPIENT_ADMIN_CC );
				$mail->Subject = EMAIL_SUBJECT . ' - ' . $datas['project_name'];
				$mail->Body    = "
                Nouvelle demande de création de contenu de site WP<br>
				Date : " . $datas['date'] . " <br>
				Nom du projet : " . $datas['project_name'] . " <br>
				DET : " . $datas['det'] . " <br>
                Type : " . $datas['type'] . " <br>";

				$mail->addAttachment( __DIR__ . '/uploads/' . $filename, $filename );

				$return_mail_send = $mail->send();
				if ( ! $return_mail_send ) {
					$message             = 'Le message de notification vers l\'admin n\'a pu être envoyé';
					$errors['send_mail'] = $message;
					error_log( $message . '-' . $mail->ErrorInfo );
					wp_die( $message );
				} else {
					$step2 = 'ok';
				};


			}
		}
	}
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" href="https://bootswatch.com/5/cerulean/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="src/style.css">
    <script src="src/script.js" defer></script>
</head>
<body>
<!-- Header -->
<header class="page-header" id="banner">
    <h1>Formulaire de configuration de contenu de site WP</h1>
    <p class="lead">Havas Factory</p>
</header>
<main>
    <form method="POST">
        <input type="hidden" name="send_form" value="ok"/>

        <!-- Step 1 -->
        <fieldset class="step-1" <?php if ( $step1 == 'ok' ) : ?>style="display: none"<?php endif; ?>>
            <h2>Infos projet</h2>
            <div class="form-group flex-group">
                <div>
                    <label for="project_name" class="form-label">Nom du projet <span>*</span></label>
                    <input type="text" name="project_name" class="form-control <?php if ( ! empty( $errors['project_name'] ) ) : ?>is-invalid<?php endif; ?>" required="required" id="project_name" placeholder="Nom du projet" value="<?php if ( ! empty( $fields['project_name'] ) ) {
						echo $fields['project_name'];
					} ?>">
					<?php if ( ! empty( $errors['project_name'] ) ) : ?>
                        <div class="invalid-feedback"><?php echo $errors['project_name']; ?></div>
					<?php endif; ?>
                </div>
                <div>
                    <label for="det" class="form-label">Lien DET <span>*</span></label>
                    <input type="text" name="det" class="form-control <?php if ( ! empty( $errors['det'] ) ) : ?>is-invalid<?php endif; ?>" required="required" id="det" placeholder="https://..." value="<?php if ( ! empty( $fields['det'] ) ) {
						echo $fields['det'];
					} ?>">
					<?php if ( ! empty( $errors['det'] ) ) : ?>
                        <div class="invalid-feedback"><?php echo $errors['det']; ?></div>
					<?php endif; ?>
                </div>
            </div>
            <div class="form-group">
                <label for="type" class="form-label">Type de site <span>*</span></label>
                <select class="form-select" id="type" name="type">
                    <option value="classic" <?php if ( isset( $fields['project_name'] ) && $fields['project_name'] == 'classic' ) : ?>selected="selected"<?php endif; ?>>Classic</option>
                    <!--option value="react" <?php if ( isset( $fields['project_name'] ) && $fields['project_name'] == 'react' ) : ?>selected="selected"<?php endif; ?>>REACT</option-->
                    <!--option value="gutenberg" <?php if ( isset( $fields['project_name'] ) && $fields['project_name'] == 'gutenberg' ) : ?>selected="selected"<?php endif; ?>>Gütenberg</option-->
                </select>
            </div>
			<?php if ( ! empty( $errors['type'] ) ) : ?>
                <div class="alert alert-dismissible alert-danger">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
					<?php echo $errors['type']; ?>
                </div>
			<?php endif; ?>
            <div class="form-group">
                <label for="det" class="form-label">Modules vendus <span>*</span></label>
                <div class="check-group">
					<?php foreach ( $flexibles_content as $key => $flexible ) : ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="availables_flexibles[<?php echo $key; ?>]" value="<?php echo $flexible['name']; ?>" id="flexible_<?php echo $key ?>" <?php if ( array_key_exists( $key, $fields['availables_flexibles'] ) ) : ?>checked=""<?php endif; ?>>
                            <label class="form-check-label" for="flexible_<?php echo $key ?>">
								<?php echo $flexible['label']; ?>
                            </label>
                        </div>
					<?php endforeach; ?>
                </div>
            </div>
			<?php if ( ! empty( $errors['availables_flexibles'] ) ) : ?>
                <div class="alert alert-dismissible alert-danger">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
					<?php echo $errors['availables_flexibles']; ?>
                </div>
			<?php endif; ?>
            <button type="submit" class="btn btn-primary" name="btn_next" value="ok">Suivant</button>
        </fieldset>
        <!-- ./Step 1 -->

        <!-- Step 2 -->
		<?php if ( $step1 == 'ok' ) : ?>
            <fieldset class="step-2" <?php if ( $step2 == 'ok' ) : ?>style="display: none"<?php endif; ?>>
                <h2>Construisez vos pages</h2>
                <div id="blocPageConstruct" data-elements='<?php echo json_encode( $js_infos_projet_available_flexibles ) ?>'>
                    <div class="page-row">
                        <div class="row-heading">
                            <h3 class="form-label">Page n°<span class="nth-row">1</span></h3>
                            <span style="display: none" class="delete-row-btn">
									<i class="bi bi-trash"></i>
								</span>
                        </div>
                        <div class="form-group">
                            <label for="page_titles" class="form-label">Titre de la page <span>*</span></label>
                            <input type="text" name="page_titles[]" class="form-control <?php if ( ! empty( $errors['page_titles'] ) ) : ?>is-invalid<?php endif; ?>" required="required" id="page_titles[]" placeholder="Titre">
							<?php if ( ! empty( $errors['page_titles'] ) ) : ?>
                                <div class="invalid-feedback"><?php echo $errors['page_titles']; ?></div>
							<?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="page_home[]">Est-ce la page d'accueil ?</label>
                            <div class="check-group">
                                <input class="home-radio home-radio-true" type="radio" name="page_home[1]" id="page_home_true[]" value="true">
                                <label for="page_home_true[]">oui</label>
                                <input class="home-radio home-radio-false" type="radio" checked name="page_home[1]" id="page_home_false[]" value="false">
                                <label for="page_home_false[]">non</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="page_privacy[]">Est-ce la page de confidentialité ?</label>
                            <div class="check-group">
                                <input class="privacy-radio privacy-radio-true" type="radio" name="page_privacy[1]" id="page_privacy_true[]" value="true">
                                <label for="page_privacy_true[]">oui</label>
                                <input class="privacy-radio privacy-radio-false" type="radio" checked name="page_privacy[1]" id="page_privacy_false[]" value="false">
                                <label for="page_privacy_false[]">non</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exampleSelect2" class="form-label">Sélectionnez des modules</label>
                            <p>Conformément à l'agencement prévu en sélectionnant parmi la liste un même module autant de fois que nécessaire</p>
                            <div class="flexible-group">
                                <ul class="flexible-select form-select">
									<?php foreach ( $flexibles_content as $key => $flexible ) : ?>
										<?php if ( array_key_exists( $key, $fields['availables_flexibles'] ) ) : ?>
                                            <li><?php echo $flexible['label']; ?><i data-value="<?php echo $flexible['name']; ?>" data-label="<?php echo $flexible['label']; ?>" class="bi bi-plus-circle add-flexible-btn"></i></li>
										<?php endif; ?>
									<?php endforeach; ?>
                                </ul>
                                <ul class="flexible-list">
                                    <!-- JS GEN -->
                                </ul>
                            </div>
                        </div>
                        <input type='hidden' name="flexibles_selection[]" class='hidden-input'/>
                    </div>
                </div>
                <div class="buttons">
                    <button type="button" id="addRowBtn" class="btn btn-light">
                        <i class="bi bi-plus-circle"></i>
                        Ajouter une page
                    </button>
                    <button type="submit" class="btn btn-primary" name="btn_submit" value="ok">Envoyer la configuration</button>
                </div>
            </fieldset>

		<?php endif; ?>
        <!-- ./Step 2 -->

        <!-- success -->
		<?php if ( $step2 == 'ok' ) : ?>
            <div class="step-3 alert alert-dismissible alert-success">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <strong>Well done!</strong> Un mail contenant les informations de votre site a été envoyé à l'équipe développeurs.
            </div>
            <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-primary" role="button">Créer une nouvelle configuration de site</a>
		<?php endif; ?>
    </form>
</main>
</body>
</html>
