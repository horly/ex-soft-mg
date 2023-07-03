<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => "Ces informations d'identification ne correspondent pas à nos données.",
    'password' => "Le mot de passe fourni est incorrect.",
    'throttle' => "Trop de tentatives de connexion. Veuillez réessayer dans : secondes secondes.",

    'login' => 'Se connecter',
    'sign_up' => "S'enregistrer",

    'to_publish_your_activities' => "Pour publier vos services et activités",

    'email' => 'E-mail',
    'password' => 'Mot de passe',
    'remember_me' => 'Se souvenir de moi',
    'forgot_password' => 'Mot de passe oublié?',
    'sign_in' => 'S\'identifier',
    'not_registered_yet' => 'Pas encore inscrit ?',
    'create_an_account' => 'Créer un compte',

    'enter_your_email' => "Entrez l'adresse email",
    'enter_your_password' => "Tapez le mot de passe",

    'enter_your_firstname' => "Entrez le prénom",
    'enter_your_lastname' => "Entrez le nom",
    'create_your_password' => "Créer le mot de passe",
    'confirm_your_password' => "Confirmez le mot de passe",

    /*
    |--------------------------------------------------------------------------
    | Register Page
    |--------------------------------------------------------------------------
    */

    'register' => 'S\'inscrire',
    'create_an_account_if_you' => 'Créez un compte si vous n\'en avez pas.',
    'first_name' => 'Prénom',
    'last_name' => 'Nom',
    'password_confirmation' => 'Confirmer le mot de passe',
    'country' => 'Pays',
    'select_your_country' => 'Sélectionnez votre pays',
    'agree_terms' => 'Accepter nos conditions',
    'already_have_an_account' => 'Vous avez déjà un compte ?',

    'error_firstname_register_message' => 'Le prénom n\'est pas valide !',
    'error_lastname_register_message' => 'Le nom n\'est pas valide !',
    'error_email_register_message' => 'L\'email n\'est pas valide!',
    'error_password_register_message' => 'Votre mot de passe doit comporter au moins 8 caractères !',
    'password_confirmation_register_message' => 'Vos mots de passe doivent être identiques !',
    'error_country_register_message' => 'S\'il vous plaît sélectionnez votre pays!',
    'error_agreeTerms_register_message' => 'Vous devez accepter nos conditions.',


    'error_role_register_message' => "Veuillez séléctionner un rôle !",
    'error_function_register_message' => "Veuillez séléctionner une fonction !",
    'error_phone_number_register_message' => "Le number de téléphone que vous avez entré n'est pas valide !",

    /*
    |--------------------------------------------------------------------------
    | Function userChecker in LoginController
    |--------------------------------------------------------------------------
    */

    'Your_account_is_not_activate_yet' => 'Votre compte n\'est pas encore activé, veuillez vérifier votre boîte mail et activer votre compte ou renvoyer le message de confirmation.',

    /*
    |--------------------------------------------------------------------------
    | CreateNewUser fortify and function registerAnonymousUser
    |--------------------------------------------------------------------------
    */

    'please_confirm_your_Email' => 'Veuillez confirmer votre adresse email',

    /*
    |--------------------------------------------------------------------------
    | Confirmation email
    |--------------------------------------------------------------------------
    */

    'hi' => 'Salut',
    'confirm_your_email' => 'Confirmez votre adresse email.',
    'please_confirm_your' => 'Veuillez confirmer votre compte en copiant et collant le code d\'activation.',
    'activation_code' => 'Code d\'activation :',
    'or_by_clicking' => 'Ou en cliquant sur le bouton de validation.',
    'confirm_my_email' => 'Confirmer mon email',
    'team' => 'L\'équipe',
    'thanks_you' => 'vous remercie!',

    /*
    |--------------------------------------------------------------------------
    | email_code_activation page
    |--------------------------------------------------------------------------
    */

    'account_activation' => 'Activation du compte',
    'activation_Code' => 'Code d\'activation',
    'enter_the_activation_code' => 'Entrez le code d\'activation',
    'change_your_email_address' => 'Change ton adresse e-mail',
    'Resend_the_activation_code' => 'Renvoyer le code d\'activation',
    'activate' => 'Activer',
    'you_have_just_resent_the_activation' => 'Vous venez de renvoyer l\'email d\'activation, veuillez vérifier votre boîte mail et activer votre compte.',
    'this_user_token' => 'Ce jeton ne correspond à aucun utilisateur',
    'this_activation_code_is_invalid' => 'Ce code d\'activation n\'est pas valide !',
    'your_email_address_has_been_verified' => 'Votre adresse e-mail a été vérifiée.',
    'your_account_is_already_activate' => 'Votre compte est déjà activé !',

    /*
    |--------------------------------------------------------------------------
    | email_activation_code page
    |--------------------------------------------------------------------------
    */

    'change_email_address' => 'Changer l\'adresse e-mail',
    'new_email_address' => 'Nouvelle adresse e-mail',
    'enter_the_new_email_address' => 'Entrez la nouvelle adresse e-mail',
    'send' => 'Envoyer',
    'this_email_address_is_already_used' => 'Cette adresse e-mail est déjà utilisée, veuillez saisir une autre adresse e-mail !',
    'you_have_just_changed_your_email_address' => 'Vous venez de changer votre adresse e-mail, le code d\'activation a été envoyé à l\'adresse e-mail : ',
    'please_check_your_mail_box' => ', veuillez vérifier votre boîte mail et activer votre compte.',

    /*
    |--------------------------------------------------------------------------
    | send_email_reset_password page
    |--------------------------------------------------------------------------
    */

    'reset_password' => 'Réinitialiser le mot de passe',
    'reset_your_password' => 'Réinitialisez votre mot de passe',
    'please_enter_your_email_address' => 'Veuillez saisir votre adresse e-mail. Nous vous enverrons un lien pour réinitialiser votre mot de passe.',
    'enter_your_email' => 'Entrez votre adresse email',
    'back_to' => 'Retour à',
    'we_have_sent_the_reset_password_link' => 'Nous avons envoyé le lien de réinitialisation du mot de passe à l\'adresse e-mail saisie.',
    'the_email_address_you_entered_does_not' => 'L\'adresse e-mail que vous avez saisie ne correspond à aucun utilisateur.',

    /*
    |--------------------------------------------------------------------------
    | reset_password_email page
    |--------------------------------------------------------------------------
    */

    'we_received_a_request_to_change' => 'Nous avons reçu une demande de changement de mot de passe.',
    'click_the_button_below_to_set' => 'Cliquez sur le bouton ci-dessous pour définir un nouveau mot de passe.',
    'if_you_are_not_the_initiator_of_this_request' => 'Si vous n\'êtes pas l\'initiateur de cette demande, veuillez nous en informer pour la sécurité de votre compte.',
    'reset_my_password' => 'Réinitialiser mon mot de passe',

    /*
    |--------------------------------------------------------------------------
    | reset_password page
    |--------------------------------------------------------------------------
    */

    'change_password' => 'Changer le mot de passe',
    'old_password' => 'Ancien mot de passe',
    'new_password' => 'Nouveau mot de passe',
    'confirm_password' => 'Confirmez le mot de passe',
    'save_changes' => 'Enregistrer',
    'new_password_saved_successfully' => 'Nouveau mot de passe enregistré avec succès !',

    'error_new_password_message_profile' => 'Votre mot de passe doit comporter au moins 8 caractères !',
    'error_confirm_new_password_message_profile' => 'Vos mots de passe doivent être identiques !',
    'error_old_password_message_profile' => 'Le mot de passe saisi ne correspond pas à l\'ancien mot de passe !',

    'loading' => "Chargement",
];
