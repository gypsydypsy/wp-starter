<div class="f f-form c-form">
    <div class="container medium--">
        <h2 class="h2">Formulaire</h2>
        <form>
            <!--Error messages-->
            <div class="error-message error-message-required"><?php _e( 'Ce champ est requis', 'havas_starter_pack' ); ?></div>
            <div class="error-message error-message-invalid"><?php _e( 'Ce champ est invalide', 'havas_starter_pack' ); ?></div>
            <div class="error-message error-message-invalid-alphabet"><?php _e( 'Ce champ peut uniquement comporter des lettres', 'havas_starter_pack' ); ?></div>
            <div class="error-message error-message-invalid-email"><?php _e( 'Votre email est invalide', 'havas_starter_pack' ); ?></div>
            <div class="error-message error-message-invalid-phone"><?php _e( 'Votre numéro de téléphone est invalide', 'havas_starter_pack' ); ?></div>
            <div class="error-message error-message-tooshort"><?php _e( 'Ce champ ne comporte pas suffisament de caractères', 'havas_starter_pack' ); ?></div>
            <div class="error-message error-message-toolong"><?php _e( 'Ce champ est trop long', 'havas_starter_pack' ); ?></div>
            <div class="error-message error-message-compare"><?php _e( 'Au moins un des deux champs doivent être complétés', 'havas_starter_pack' ); ?></div>
            <!--./Error messages-->

            <div class="form-title"><?php _e( 'Coordonnées', 'havas_starter_pack' ); ?><span class="required">*</span>
            </div>

            <div class="form-row">
                <div class="form-group radio">
                    <label for="civility"><?php _e( 'Civilité', 'havas_starter_pack' ); ?><span
                                class="required">*</span></label>
                    <div class="radioCtn">
                        <div>
                            <input type="radio" id="mr" name="civility" required value="0"/>
                            <span></span>
                            <label for="mr"><?php _e( 'Mr', 'havas_starter_pack' ); ?></label>
                        </div>
                        <div>
                            <input type="radio" id="mme" name="civility" required value="1"/>
                            <span></span>
                            <label for="mme"><?php _e( 'Mme', 'havas_starter_pack' ); ?></label>
                        </div>
                    </div>
                    <div class="error" aria-live="polite"></div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="firstname"><?php _e( 'Prénom', 'havas_starter_pack' ); ?><span
                                class="required">*</span></label>
                    <input type="text" id="firstname" name="firstname" placeholder="Élodie" required
                           pattern="^[A-zÀ-ÿ '.-]+"/>
                    <div class="error" aria-live="polite"></div>
                </div>
                <div class="form-group">
                    <label for="lastname"><?php _e( 'Nom', 'havas_starter_pack' ); ?><span
                                class="required">*</span></label>
                    <input type="text" id="lastname" name="lastname" placeholder="Dupont" required
                           pattern="^[A-zÀ-ÿ '.-]+"/>
                    <div class="error" aria-live="polite"></div>
                </div>
            </div>

            <div class="form-desc"><?php _e( 'Merci de renseigner au moins l’un des deux champs ci-dessous.', 'havas_starter_pack' ); ?></div>
            <div class="form-row">
                <div class="form-group email">
                    <label for="email"><?php _e( 'Email', 'havas_starter_pack' ); ?></label>
                    <input type="email" id="email" name="email" placeholder="elodie.dupont@email.com"
                           pattern="[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-zÀ-ÿ]{1,63}$" data-compare="true"/>
                    <div class="error" aria-live="polite"></div>
                </div>
                <div class="form-group tel">
                    <label for="phone"><?php _e( 'Téléphone', 'havas_starter_pack' ); ?></label>
                    <input type="tel" id="phone" name="phone"
                           placeholder="+000 00 00 00 00"
                           pattern="^[0-9*#+()]+$" data-compare="true"/>
                    <div class="error" aria-live="polite"></div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group select">
                    <label for="object"><?php _e( 'Objet', 'havas_starter_pack' ); ?><span
                                class="required">*</span></label>
                    <select name="object" id="object" class="js-select" required>
                        <option value=""><?php _e( 'Choisissez le sujet de votre demande...', 'havas_starter_pack' ); ?></option>
                        <option value="1"><?php _e( 'Choix 1', 'havas_starter_pack' ); ?></option>
                        <option value="2"><?php _e( 'Choix 2', 'havas_starter_pack' ); ?></option>
                        <option value="3"><?php _e( 'Choix 3', 'havas_starter_pack' ); ?></option>
                    </select>
                    <div class="error" aria-live="polite"></div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group textarea">
                    <label for="message"></label>
                    <textarea name="message" id="message"
                              placeholder="<?php _e( 'Votre message', 'havas_starter_pack' ); ?>" required></textarea>
                    <div class="error" aria-live="polite"></div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group checkbox optin">
                    <div class="checkboxCtn">
                        <input type="checkbox" id="optin" name="optin" required/>
                        <span></span>
                        <label for="optin"><?php _e( 'Optin', 'havas_starter_pack' ); ?></label>
                    </div>
                    <div class="error" aria-live="polite"></div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <div class="g-recaptcha"
                         data-sitekey="<?php the_field( 'google_recaptcha_public_key', 'option' ) ?>"
                         data-callback="verifyCaptcha"></div>
                    <div class="error captcha--"
                         aria-live="polite"><?php _e( 'Veuillez valider le recaptcha', 'havas_starter_pack' ); ?>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group submit">
                    <button type="button"
                            class="c-button bg-- js-submit"><?php _e( 'Soumettre', 'havas_starter_pack' ); ?>
                        <span><?php _e( 'Envoi...', 'havas_starter_pack' ); ?></span></button>
                    <div class="error global--"
                         aria-live="polite"><?php _e( 'Une erreur est survenue', 'havas_starter_pack' ); ?>
                        <span></span>
                    </div>
                    <div class="success"></div>
                </div>
            </div>
            <div class="form-notice"><span
                        class="required">*</span><?php _e( 'Champs obligatoires', 'havas_starter_pack' ); ?></div>
        </form>
    </div>
</div>