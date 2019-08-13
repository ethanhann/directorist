<?php
if (!class_exists('ATBDP_Settings_Manager')):
    class ATBDP_Settings_Manager
    {

        private $extension_url = '';

        public function __construct()
        {
            // the safest hook to use is after_setup_theme, since Vafpress Framework may exists in Theme or Plugin
            add_action('after_setup_theme', array($this, 'display_plugin_settings'));
            $this->extension_url = sprintf("<a target='_blank' href='%s'>%s</a>", esc_url(admin_url('edit.php?post_type=at_biz_dir&page=atbdp-extension')), __('Checkout Awesome Extensions', ATBDP_TEXTDOMAIN));
        }

        /**
         * It displays the settings page of the plugin using VafPress framework
         * @return void
         **@since 3.0.0
         */
        public function display_plugin_settings()
        {
            $atbdp_options = array(
                //'is_dev_mode' => true,
                'option_key' => 'atbdp_option',
                'page_slug' => 'aazztech_settings',
                'menu_page' => 'edit.php?post_type=at_biz_dir',
                'use_auto_group_naming' => true,
                'use_util_menu' => true, // show import and export menu
                'minimum_role' => 'manage_options',
                'layout' => 'fixed',
                'page_title' => __('Directory Settings', ATBDP_TEXTDOMAIN),
                'menu_label' => __('Directory Settings', ATBDP_TEXTDOMAIN),
                'template' => array(
                    'title' => __('Directory Settings', ATBDP_TEXTDOMAIN),
                    'logo' => esc_url(ATBDP_ADMIN_ASSETS . 'images/settings_icon.png'),
                    'menus' => $this->get_settings_menus(),
                ),
            );

            // initialize the option page
            new VP_Option($atbdp_options);
        }

        /**
         * Get all the menus for the Settings Page
         * @return array It returns an array of Menus
         * @since 3.0.0
         */
        function get_settings_menus()
        {
            return apply_filters('atbdp_settings_menus', array(
                /*Main Menu 1*/
                'listings' => array(
                    'name' => 'listings',
                    'title' => __('Listing Settings', ATBDP_TEXTDOMAIN),
                    'icon' => 'font-awesome:fa-list',
                    'menus' => $this->get_listings_settings_submenus(),
                ),
                /*Main Menu 3*/
                'search' => array(
                    'name' => 'search',
                    'title' => __('Search Settings', ATBDP_TEXTDOMAIN),
                    'icon' => 'font-awesome:fa-search',
                    'controls' => apply_filters('atbdp_search_settings_controls', array(
                        'search_section' => array(
                            'type' => 'section',
                            'title' => __('Search Form Settings', ATBDP_TEXTDOMAIN),
                            'description' => __('You can Customize Search Form related settings here. After switching any option, Do not forget to save the changes.', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_search_settings_fields(),
                        ), // ends 'search_settings' section
                        'search_result' => array(
                            'type' => 'section',
                            'title' => __('Search Result Settings', ATBDP_TEXTDOMAIN),
                            'description' => __('You can Customize Search Result related settings here. After switching any option, Do not forget to save the changes.', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_search_form_settings_fields(),
                        ), // ends 'search_settings' section
                    )),
                ),
                /*Main Menu 4*/
                'pages' => array(
                    'name' => 'pages',
                    'title' => __('Pages, Links & Views', ATBDP_TEXTDOMAIN),
                    'icon' => 'font-awesome:fa-line-chart',
                    'controls' => apply_filters('atbdp_pages_settings_controls', array(
                        'page_section' => array(
                            'type' => 'section',
                            'title' => __('Upgrade/Regenerate Pages', ATBDP_TEXTDOMAIN),
                            'description' => __('If you are an existing user of the directorist, you have to upgrade your Directorist pages shortcode.', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_pages_regenerate_settings_fields(),
                        ),
                        'search_section' => array(
                            'type' => 'section',
                            'title' => __('Pages, links & views Settings', ATBDP_TEXTDOMAIN),
                            'description' => __('You can Customize Listings related settings here. After switching any option, Do not forget to save the changes.', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_pages_settings_fields(),
                        ), // ends 'pages' section

                    )),
                ),
                /*Main Menu 5*/
                'seo_menu' => array(
                    'title' => __('Titles & Metas', ATBDP_TEXTDOMAIN),
                    'name' => 'seo_settings',
                    'icon' => 'font-awesome:fa-bolt',
                    'controls' => apply_filters('atbdp_seo_settings_controls', array(
                        'seo_section' => array(
                            'type' => 'section',
                            'title' => __('Titles & Metas', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_seo_settings_fields(),
                        ),
                    )),
                ),

                /*Main Menu 5*/
                'general_menu' => array(
                    'title' => __('Currency Settings', ATBDP_TEXTDOMAIN),
                    'name' => 'currency_settings',
                    'icon' => 'font-awesome:fa-money',
                    'controls' => apply_filters('atbdp_currency_settings_controls', array(
                        'currency_section' => array(
                            'type' => 'section',
                            'title' => __('Currency Settings', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_currency_settings_fields(),
                        ),
                    )),
                ),
                /*Main Menu 6*/
                'categories_menu' => array(
                    'title' => __('Categories & Locations Page', ATBDP_TEXTDOMAIN),
                    'name' => 'categories_menu',
                    'icon' => 'font-awesome:fa-list-alt',
                    'controls' => apply_filters('atbdp_categories_settings_controls', array(

                        'category_section' => array(
                            'type' => 'section',
                            'title' => __('Categories Page Setting', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_categories_settings_fields(),
                        ),
                        'location_section' => array(
                            'type' => 'section',
                            'title' => __('Locations Page Setting', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_locations_settings_fields()
                        ),
                    )),
                ),
                /*Lets make the following extension menu customization by the extensions. Apply a filter on it*/
                'extensions_menu' => array(
                    'title' => __('Extensions Settings', ATBDP_TEXTDOMAIN),
                    'name' => 'menu_1',
                    'icon' => 'font-awesome:fa-magic',
                    'menus' => $this->get_extension_settings_submenus(),
                ),
                /*lets make the settings for email*/
                'email_menu' => array(
                    'title' => __('Emails Settings', ATBDP_TEXTDOMAIN),
                    'name' => 'email_menu1',
                    'icon' => 'font-awesome:fa-envelope',
                    'menus' => $this->get_email_settings_submenus(),
                ),
                /*lets make the settings for registration & login*/
                'registration_login_menu' => array(
                    'title' => __('Registration & Login', ATBDP_TEXTDOMAIN),
                    'name' => 'registration_login',
                    'icon' => 'font-awesome:fa-align-right',
                    'menus' => $this->get_reg_log_settings_submenus(),
                ),
                /*lets make the settings for registration & login*/
                /*'style_settings_menu' => array(
                    'title' => __('Style Settings', ATBDP_TEXTDOMAIN),
                    'name' => 'style_settings',
                    'icon' => 'font-awesome:fa-adjust',
                    'controls' => apply_filters('atbdp_style_settings_controls', array(
                        'style_settings' => array(
                            'type' => 'section',
                            'title' => __('Style Settings', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_listings_style_settings_fields(),
                        ),
                    )),
                ),*/
            ));
        }

        /**
         * Get all the submenus for registration & login menu
         * @return array It returns an array of submenus
         * @since 5.1.0
         */
        public function get_reg_log_settings_submenus()
        {
            return apply_filters('atbdp_reg_log_settings_submenus', array(
                /*Submenu : Registration Settings */
                array(
                    'title' => __('Registration Form', ATBDP_TEXTDOMAIN),
                    'name' => 'registration_form',
                    'icon' => 'font-awesome:fa-home',
                    'controls' => apply_filters('atbdp_registration_settings_controls', array(
                        'reg_username_field' => array(
                            'type' => 'section',
                            'title' => __('Username', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_reg_username_field(),
                        ),
                        'reg_password_field' => array(
                            'type' => 'section',
                            'title' => __('Password', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_reg_password_field(),
                        ),
                        'reg_email_field' => array(
                            'type' => 'section',
                            'title' => __('Email', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_reg_email_field(),
                        ),
                        'reg_website_field' => array(
                            'type' => 'section',
                            'title' => __('Website', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_reg_website_field(),
                        ),
                        'reg_fname_field' => array(
                            'type' => 'section',
                            'title' => __('First Name', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_reg_fname_field(),
                        ),
                        'reg_lname_field' => array(
                            'type' => 'section',
                            'title' => __('Last Name', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_reg_lname_field(),
                        ),
                        'reg_bio_field' => array(
                            'type' => 'section',
                            'title' => __('About/bio', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_reg_bio_field(),
                        ),
                        'signUp_button' => array(
                            'type' => 'section',
                            'title' => __('Sign Up Button', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_reg_signUp_button(),
                        ),
                        'login_text' => array(
                            'type' => 'section',
                            'title' => __('Login Message', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_reg_Login_text(),
                        ),
                    )),
                ),
                /*Submenu : login form settings*/
                array(
                    'title' => __('Login Form', ATBDP_TEXTDOMAIN),
                    'name' => 'login_form',
                    'icon' => 'font-awesome:fa-home',
                    'controls' => apply_filters('atbdp_login_form_settings_controls', array(
                        'log_username' => array(
                            'type' => 'section',
                            'title' => __('Username or Email Address', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_user_email_login(),
                        ),
                        'log_password' => array(
                            'type' => 'section',
                            'title' => __('Password', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_password_login(),
                        ),
                        'log_remember' => array(
                            'type' => 'section',
                            'title' => __('Remember Login Information', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_remember_login(),
                        ),
                        'log_loginButton' => array(
                            'type' => 'section',
                            'title' => __('Log In Button', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_button_login(),
                        ),
                        'log_signup' => array(
                            'type' => 'section',
                            'title' => __('Sign Up Message', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_signup_login(),
                        ),
                        'log_recoverPassword' => array(
                            'type' => 'section',
                            'title' => __('Recover Password', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_recoverPassword_login(),
                        ),
                    )),
                ),
            ));
        }

        /**
         * Get login text setting field for login form
         * @return array
         * @since 5.1.0
         */
        public function get_reg_Login_text()
        {
            return apply_filters('atbdp_log_signup_text_setting', array(
                array(
                    'type' => 'toggle',
                    'name' => 'display_login',
                    'label' => __('Enable', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'login_text',
                    'label' => __('Text', ATBDP_TEXTDOMAIN),
                    'default' => __('Already have an account? Please login', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'login_url',
                    'label' => __('Url', ATBDP_TEXTDOMAIN),
                    'default' => ATBDP_Permalink::get_login_page_url(),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'log_linkingmsg',
                    'label' => __('Linking Text', ATBDP_TEXTDOMAIN),
                    'default' => __('Here', ATBDP_TEXTDOMAIN),
                ),
            ));
        }

        /**
         * Get username setting field for login form
         * @return array
         * @since 5.1.0
         */
        public function get_user_email_login()
        {
            return apply_filters('atbdp_log_username_field_setting', array(
                array(
                    'type' => 'textbox',
                    'name' => 'log_username',
                    'label' => __('Label', ATBDP_TEXTDOMAIN),
                    'default' => __('Username or Email Address', ATBDP_TEXTDOMAIN),
                ),

            ));
        }

        /**
         * Get password field for login form
         * @return array
         * @since 5.1.0
         */
        public function get_password_login()
        {
            return apply_filters('atbdp_log_password_field_setting', array(

                array(
                    'type' => 'textbox',
                    'name' => 'log_password',
                    'label' => __('Label', ATBDP_TEXTDOMAIN),
                    'default' => __('Password', ATBDP_TEXTDOMAIN),
                ),

            ));
        }

        /**
         * Get remember me text setting field for login form
         * @return array
         * @since 5.1.0
         */
        public function get_remember_login()
        {
            return apply_filters('atbdp_log_remember_field_setting', array(
                array(
                    'type' => 'toggle',
                    'name' => 'display_rememberme',
                    'label' => __('Enable', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'log_rememberme',
                    'label' => __('Label', ATBDP_TEXTDOMAIN),
                    'default' => __('Remember Me', ATBDP_TEXTDOMAIN),
                ),
            ));
        }

        /**
         * Get login button text setting field for login form
         * @return array
         * @since 5.1.0
         */
        public function get_button_login()
        {
            return apply_filters('atbdp_log_login_button_setting', array(
                array(
                    'type' => 'textbox',
                    'name' => 'log_button',
                    'label' => __('Text', ATBDP_TEXTDOMAIN),
                    'default' => __('Log In', ATBDP_TEXTDOMAIN),
                ),
            ));
        }

        /**
         * Get sign up setting field for login form
         * @return array
         * @since 5.1.0
         */
        public function get_signup_login()
        {
            return apply_filters('atbdp_log_signup_text_setting', array(
                array(
                    'type' => 'toggle',
                    'name' => 'display_signup',
                    'label' => __('Enable', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'reg_text',
                    'label' => __('Text', ATBDP_TEXTDOMAIN),
                    'default' => __('Don\'t have an account?', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'reg_url',
                    'label' => __('Url', ATBDP_TEXTDOMAIN),
                    'default' => ATBDP_Permalink::get_registration_page_url(),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'reg_linktxt',
                    'label' => __('Linking Text', ATBDP_TEXTDOMAIN),
                    'default' => __('Sign Up', ATBDP_TEXTDOMAIN),
                ),
            ));
        }

        /**
         * Get recover password setting field for login form
         * @return array
         * @since 5.1.0
         */
        public function get_recoverPassword_login()
        {
            return apply_filters('atbdp_log_rec_pass_setting', array(
                array(
                    'type'    => 'toggle',
                    'name'    => 'display_recpass',
                    'label'   => __('Enable', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'recpass_text',
                    'label' => __('Name', ATBDP_TEXTDOMAIN),
                    'default' => __('Recover Password', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'recpass_desc',
                    'label' => __('Description', ATBDP_TEXTDOMAIN),
                    'default' => __('Please enter your email address. You will receive a new password via email.', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'recpass_username',
                    'label' => __('Username or Email Label', ATBDP_TEXTDOMAIN),
                    'default' => __('Username or E-mail', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'recpass_placeholder',
                    'label' => __('Username or Email Placeholder', ATBDP_TEXTDOMAIN),
                    'default' => __('eg. mail@example.com', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'recpass_button',
                    'label' => __('Button Text', ATBDP_TEXTDOMAIN),
                    'default' => __('Get New Password', ATBDP_TEXTDOMAIN),
                ),
            ));
        }

        /**
         * Get username setting field for registration
         * @return array
         * @since 5.1.0
         */
        public function get_reg_username_field()
        {
            return apply_filters('atbdp_reg_username_field_setting', array(
                array(
                    'type' => 'textbox',
                    'name' => 'reg_username',
                    'label' => __('Label', ATBDP_TEXTDOMAIN),
                    'default' => __('Username', ATBDP_TEXTDOMAIN),
                ),

            ));
        }

        /**
         * Get password setting field for registration
         * @return array
         * @since 5.1.0
         */
        public function get_reg_password_field()
        {
            return apply_filters('atbdp_reg_password_field_setting', array(
                array(
                    'type' => 'toggle',
                    'name' => 'display_password_reg',
                    'label' => __('Enable', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'reg_password',
                    'label' => __('Label', ATBDP_TEXTDOMAIN),
                    'default' => __('Password', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_password_reg',
                    'label' => __('Required', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
            ));
        }

        /**
         * Get email setting field for registration
         * @return array
         * @since 5.1.0
         */
        public function get_reg_email_field()
        {
            return apply_filters('atbdp_reg_email_field_setting', array(
                array(
                    'type' => 'textbox',
                    'name' => 'reg_email',
                    'label' => __('Label', ATBDP_TEXTDOMAIN),
                    'default' => __('Email', ATBDP_TEXTDOMAIN),
                ),

            ));
        }

        /**
         * Get website setting field for registration
         * @return array
         * @since 5.1.0
         */
        public function get_reg_website_field()
        {
            return apply_filters('atbdp_reg_website_field_setting', array(
                array(
                    'type' => 'toggle',
                    'name' => 'display_website_reg',
                    'label' => __('Enable', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'reg_website',
                    'label' => __('Label', ATBDP_TEXTDOMAIN),
                    'default' => __('Website', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_website_reg',
                    'label' => __('Required', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),

            ));
        }

        /**
         * Get First Name setting field for registration
         * @return array
         * @since 5.1.0
         */
        public function get_reg_fname_field()
        {
            return apply_filters('atbdp_reg_fname_field_setting', array(
                array(
                    'type' => 'toggle',
                    'name' => 'display_fname_reg',
                    'label' => __('Enable', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'reg_fname',
                    'label' => __('Label', ATBDP_TEXTDOMAIN),
                    'default' => __('First Name', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_fname_reg',
                    'label' => __('Required', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),

            ));
        }

        /**
         * Get Last Name setting field for registration
         * @return array
         * @since 5.1.0
         */
        public function get_reg_lname_field()
        {
            return apply_filters('atbdp_reg_lname_field_setting', array(
                array(
                    'type' => 'toggle',
                    'name' => 'display_lname_reg',
                    'label' => __('Enable', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'reg_lname',
                    'label' => __('Label', ATBDP_TEXTDOMAIN),
                    'default' => __('Last Name', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_lname_reg',
                    'label' => __('Required', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),

            ));
        }

        /**
         * Get bio setting field for registration
         * @return array
         * @since 5.1.0
         */
        public function get_reg_bio_field()
        {
            return apply_filters('atbdp_reg_bio_field_setting', array(
                array(
                    'type' => 'toggle',
                    'name' => 'display_bio_reg',
                    'label' => __('Enable', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'reg_bio',
                    'label' => __('Label', ATBDP_TEXTDOMAIN),
                    'default' => __('About/bio', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_bio_reg',
                    'label' => __('Required', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),

            ));
        }

        /**
         * Get signup button setting field for registration
         * @return array
         * @since 5.1.0
         */
        public function get_reg_signUp_button()
        {
            return apply_filters('atbdp_reg_signUp_button_setting', array(
                array(
                    'type' => 'textbox',
                    'name' => 'reg_signup',
                    'label' => __('Text', ATBDP_TEXTDOMAIN),
                    'default' => __('Sign Up', ATBDP_TEXTDOMAIN),
                ),

            ));
        }

        /**
         * Get all the submenus for listings
         * @return array It returns an array of submenus
         * @since 4.0.0
         */
        public function get_listings_settings_submenus()
        {
            return apply_filters('atbdp_general_listings_submenus', array(
                /*Submenu : General Listings*/
                array(
                    'title' => __('General Listings', ATBDP_TEXTDOMAIN),
                    'name' => 'general_listings',
                    'icon' => 'font-awesome:fa-sliders',
                    'controls' => apply_filters('atbdp_general_listings_controls', array(
                        'all_listing_section' => array(
                            'type' => 'section',
                            'title' => __('Listings Page Setting', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_listings_page_settings_fields(),
                        ),
                        'emails' => array(
                            'type' => 'section',
                            'title' => __('General Listings', ATBDP_TEXTDOMAIN),
                            'description' => __('You can Customize all Listings related settings here', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_general_listings_settings_fields(),
                        )
                    )),
                ),

                /*Submenu : Listing form */
                array(
                    'title' => __('Single Listing', ATBDP_TEXTDOMAIN),
                    'name' => 'listings_form',
                    'icon' => 'font-awesome:fa-info',
                    'controls' => apply_filters('atbdp_listings_form_controls', array(
                        'emails' => array(
                            'type' => 'section',
                            'title' => __('Single Listing', ATBDP_TEXTDOMAIN),
                            'description' => __('You can Customize Listings Form related settings here', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_listings_form_settings_fields(),
                        ),
                    )),
                ),

                /*Submenu : Badge Management */
                array(
                    'title' => __('Badge Setting', ATBDP_TEXTDOMAIN),
                    'name' => 'badge_management',
                    'icon' => 'font-awesome:fa-certificate',
                    'controls' => apply_filters('atbdp_badge_controls', array(
                        'badges' => array(
                            'type' => 'section',
                            'title' => __('Badge Management', ATBDP_TEXTDOMAIN),
                            'description' => __('You can Customize Badge here', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_badge_settings_fields(),
                        ),
                        'popular_badge' => array(
                            'type' => 'section',
                            'title' => __('Popular Badge', ATBDP_TEXTDOMAIN),
                            'description' => __('You can Customize Popular Badge here', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_popular_badge_settings_fields(),
                        ),
                    )),
                ),
                /*Submenu : Review */
                array(
                    'title' => __('Review Setting', ATBDP_TEXTDOMAIN),
                    'name' => 'review_setting',
                    'icon' => 'font-awesome:fa-star',
                    'controls' => apply_filters('atbdp_review_controls', array(
                        'emails' => array(
                            'type' => 'section',
                            'title' => __('Review Setting', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_listings_review_settings_fields(),
                        ),
                    )),
                ),

                /*Submenu : Form */
                array(
                    'title' => __('Form Fields', ATBDP_TEXTDOMAIN),
                    'name' => 'form_fields_setting',
                    'icon' => 'font-awesome:fa-adjust',
                    'controls' => apply_filters('atbdp_review_controls', array(
                        'title_field' => array(
                            'type' => 'section',
                            'title' => __('Title', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_listings_title_field_settings(),
                        ),
                        'desc_field' => array(
                            'type' => 'section',
                            'title' => __('Long Description', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_listings_desc_field_settings(),
                        ),
                        'cat_field' => array(
                            'type' => 'section',
                            'title' => __('Category', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_listings_cat_field_settings(),
                        ),
                        'loc_field' => array(
                            'type' => 'section',
                            'title' => __('Location', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_listings_loc_field_settings(),
                        ),
                        'tag_field' => array(
                            'type' => 'section',
                            'title' => __('Tag', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_listings_tag_field_settings(),
                        ),
                        'tagline_field' => array(
                            'type' => 'section',
                            'title' => __('Tagline', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_listings_tagline_field_settings(),
                        ),
                        'pricing_field' => array(
                            'type' => 'section',
                            'title' => __('Pricing', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_listings_pricing_field_settings(),
                        ),
                        'views_count' => array(
                            'type' => 'section',
                            'title' => __('Views Count', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_listings_views_count_settings(),
                        ),
                        's_desc_field' => array(
                            'type' => 'section',
                            'title' => __('Short Description / Excerpt', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_listings_short_desc_field_settings(),
                        ),
                        'address_field' => array(
                            'type' => 'section',
                            'title' => __('Address', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_listings_address_field_settings(),
                        ),
                        'phone_field' => array(
                            'type' => 'section',
                            'title' => __('Phone Number', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_listings_phone_field_settings(),
                        ),
                        'phone_field2' => array(
                            'type' => 'section',
                            'title' => __('Phone Number 2', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_listings_phone_field2_settings(),
                        ),
                        'pax' => array(
                            'type' => 'section',
                            'title' => __('Fax', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_listings_fax_settings(),
                        ),
                        'email_field' => array(
                            'type' => 'section',
                            'title' => __('Email', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_listings_email_field_settings(),
                        ),
                        'website_field' => array(
                            'type' => 'section',
                            'title' => __('Website', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_listings_website_field_settings(),
                        ),
                        'zip_field' => array(
                            'type' => 'section',
                            'title' => __('Zip/Post Code', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_listings_zip_field_settings(),
                        ),
                        'social_field' => array(
                            'type' => 'section',
                            'title' => __('Social Info', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_listings_social_field_settings(),
                        ),
                        'map_field' => array(
                            'type' => 'section',
                            'title' => __('Map', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_listings_map_field_settings(),
                        ),
                        'img_field' => array(
                            'type' => 'section',
                            'title' => __('Image', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_listings_image_field_settings(),
                        ),
                        'video_field' => array(
                            'type' => 'section',
                            'title' => __('Video', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_listings_video_field_settings(),
                        ),
                        'terms_field' => array(
                            'type' => 'section',
                            'title' => __('Terms and Condition', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_listings_terms_field_settings(),
                        ),
                    )),
                ),

                /*Submenu : Deshboard */
                array(
                    'title' => __('User Dashboard Setting', ATBDP_TEXTDOMAIN),
                    'name' => 'dashboard_setting',
                    'icon' => 'font-awesome:fa-bar-chart',
                    'controls' => apply_filters('atbdp_dashboard_controls', array(
                        'emails' => array(
                            'type' => 'section',
                            'title' => __('User Dashboard Setting', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_listings_dashboard_settings_fields(),
                        ),
                    )),
                ),

                /*Submenu : Deshboard */
                array(
                    'title' => __('Map Setting', ATBDP_TEXTDOMAIN),
                    'name' => 'map_setting',
                    'icon' => 'font-awesome:fa-map-signs',
                    'controls' => apply_filters('atbdp_dashboard_controls', array(
                        'emails' => array(
                            'type' => 'section',
                            'title' => __('Map Setting', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_listings_map_settings_fields(),
                        ),
                    )),
                ),
                /*Submenu : Style */
                /*'style_setting'=> array(
                    'title' => __('Style Setting', ATBDP_TEXTDOMAIN),
                    'name' => 'style_setting',
                    'icon' => 'font-awesome:fa-adjust',
                    'controls' => apply_filters('atbdp_style_controls', array(
                        'emails' => array(
                            'type' => 'section',
                            'title' => __('Style Setting', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_listings_style_settings_fields(),
                        ),
                    )),
                ),*/
            ));
        }


        /**
         * Get all the submenus for the style menu
         * @return array It returns an array of submenus
         * @since 5.5.1
         */
        public function get_listings_style_settings_fields(){
            return apply_filters('atbdp_style_settings_submenus', array(
                array(
                    'type' => 'color',
                    'name' => 'primary_color',
                    'label' => __('Primary Color', ATBDP_TEXTDOMAIN),
                    'default' => '#444752',
                ),
                array(
                    'type' => 'color',
                    'name' => 'back_primary_color',
                    'label' => __('Background Primary Color', ATBDP_TEXTDOMAIN),
                    'default' => '#444752',
                ),
                array(
                    'type' => 'color',
                    'name' => 'border_primary_color',
                    'label' => __('Border Primary Color', ATBDP_TEXTDOMAIN),
                    'default' => '#444752',
                ),
                array(
                    'type' => 'color',
                    'name' => 'secondary_color',
                    'label' => __('Secondary Color', ATBDP_TEXTDOMAIN),
                    'default' => '#122069',
                ),
                array(
                    'type' => 'color',
                    'name' => 'back_secondary_color',
                    'label' => __('Background Secondary Color', ATBDP_TEXTDOMAIN),
                    'default' => '#122069',
                ),
                array(
                    'type' => 'color',
                    'name' => 'success_color',
                    'label' => __('Success Color', ATBDP_TEXTDOMAIN),
                    'default' => '#32cc6f',
                ),
                array(
                    'type' => 'color',
                    'name' => 'back_success_color',
                    'label' => __('Background Success Color', ATBDP_TEXTDOMAIN),
                    'default' => '#32cc6f',
                ),
                array(
                    'type' => 'color',
                    'name' => 'info_color',
                    'label' => __('Info Color', ATBDP_TEXTDOMAIN),
                    'default' => '#3590ec',
                ),
                array(
                    'type' => 'color',
                    'name' => 'back_info_color',
                    'label' => __('Background Info Color', ATBDP_TEXTDOMAIN),
                    'default' => '#3590ec',
                ),
                array(
                    'type' => 'color',
                    'name' => 'warning_color',
                    'label' => __('Warning Color', ATBDP_TEXTDOMAIN),
                    'default' => '#ffaf00',
                ),
                array(
                    'type' => 'color',
                    'name' => 'back_warning_color',
                    'label' => __('Background Warning Color', ATBDP_TEXTDOMAIN),
                    'default' => '#ffaf00',
                ),
                array(
                    'type' => 'color',
                    'name' => 'danger_color',
                    'label' => __('Danger Color', ATBDP_TEXTDOMAIN),
                    'default' => '#e23636',
                ),
                array(
                    'type' => 'color',
                    'name' => 'back_danger_color',
                    'label' => __('Background Danger Color', ATBDP_TEXTDOMAIN),
                    'default' => '#e23636',
                ),
                array(
                    'type' => 'color',
                    'name' => 'dark_color',
                    'label' => __('Dark Color', ATBDP_TEXTDOMAIN),
                    'default' => '#202428',
                ),
                array(
                    'type' => 'color',
                    'name' => 'back_dark_color',
                    'label' => __('Background Dark Color', ATBDP_TEXTDOMAIN),
                    'default' => '#202428',
                ),
                array(
                    'type' => 'color',
                    'name' => 'featured_badge_color',
                    'label' => __('Featured Badge Color', ATBDP_TEXTDOMAIN),
                    'default' => '#fa8b0c',
                ),
                array(
                    'type' => 'color',
                    'name' => 'featured_back_color',
                    'label' => __('Featured Badge Background Color', ATBDP_TEXTDOMAIN),
                    'default' => '#fa8b0c',
                ),
                array(
                    'type' => 'color',
                    'name' => 'popular_badge_color',
                    'label' => __('Popular Badge Color', ATBDP_TEXTDOMAIN),
                    'default' => '#f51957',
                ),
                array(
                    'type' => 'color',
                    'name' => 'popular_back_color',
                    'label' => __('Popular Badge Background Color', ATBDP_TEXTDOMAIN),
                    'default' => '#f51957',
                ),
                array(
                    'type' => 'color',
                    'name' => 'heading_color',
                    'label' => __('Heading Color', ATBDP_TEXTDOMAIN),
                    'default' => '#272b41',
                ),
                array(
                    'type' => 'color',
                    'name' => 'text_color',
                    'label' => __('Text Color', ATBDP_TEXTDOMAIN),
                    'default' => '#7a82a6',
                ),
                array(
                    'type' => 'color',
                    'name' => 'rating_color',
                    'label' => __('Rating Color', ATBDP_TEXTDOMAIN),
                    'default' => '#fa8b0c',
                ),
            ));
        }
        /**
         * Get all the submenus for the email menu
         * @return array It returns an array of submenus
         * @since 3.1.0
         */
        public function get_email_settings_submenus()
        {
            return apply_filters('atbdp_email_settings_submenus', array(
                /*Submenu : Email General*/
                array(
                    'title' => __('Email General', ATBDP_TEXTDOMAIN),
                    'name' => 'emails_general',
                    'icon' => 'font-awesome:fa-home',
                    'controls' => apply_filters('atbdp_email_settings_controls', array(
                        'emails' => array(
                            'type' => 'section',
                            'title' => __('Email General Settings', ATBDP_TEXTDOMAIN),
                            'description' => __('You can Customize Email and Notification-related settings here. You can enable or disable any emails here. Here, YES means Enabled, and NO means disabled. After switching any option, Do not forget to save the changes.', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_email_settings_fields(),
                        ),
                    )),
                ),
                /*Submenu : Email Templates*/
                array(
                    'title' => __('Email Templates', ATBDP_TEXTDOMAIN),
                    'name' => 'emails_templates',
                    'icon' => 'font-awesome:fa-envelope',
                    'controls' => apply_filters('atbdp_email_templates_settings_controls', array(
                        'new_eml_templates' => array(
                            'type' => 'section',
                            'title' => __('For New Listing', ATBDP_TEXTDOMAIN),
                            'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_email_new_tmpl_settings_fields(),
                        ),
                        'publish_eml_templates' => array(
                            'type' => 'section',
                            'title' => __('For Approved/Published Listings', ATBDP_TEXTDOMAIN),
                            'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_email_pub_tmpl_settings_fields(),
                        ),
                        'edited_eml_templates' => array(
                            'type' => 'section',
                            'title' => __('For Edited Listings', ATBDP_TEXTDOMAIN),
                            'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_email_edit_tmpl_settings_fields(),
                        ),
                        'about_expire_eml_templates' => array(
                            'type' => 'section',
                            'title' => __('For About to Expire Listings', ATBDP_TEXTDOMAIN),
                            'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', ATBDP_TEXTDOMAIN),
                            'fields' => $this->email_about_expire_tmpl_settings_fields(),
                        ),
                        'expired_eml_templates' => array(
                            'type' => 'section',
                            'title' => __('For Expired Listings', ATBDP_TEXTDOMAIN),
                            'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', ATBDP_TEXTDOMAIN),
                            'fields' => $this->email_expired_tmpl_settings_fields(),
                        ),
                        'renewal_eml_templates' => array(
                            'type' => 'section',
                            'title' => __('For Renewal Listings (Remind to Renew)', ATBDP_TEXTDOMAIN),
                            'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', ATBDP_TEXTDOMAIN),
                            'fields' => $this->email_renewal_tmpl_settings_fields(),
                        ),
                        'renewed_eml_templates' => array(
                            'type' => 'section',
                            'title' => __('For Renewed Listings (After Renewed)', ATBDP_TEXTDOMAIN),
                            'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', ATBDP_TEXTDOMAIN),
                            'fields' => $this->email_renewed_tmpl_settings_fields(),
                        ),
                        'deleted_eml_templates' => array(
                            'type' => 'section',
                            'title' => __('For deleted/trashed Listings', ATBDP_TEXTDOMAIN),
                            'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', ATBDP_TEXTDOMAIN),
                            'fields' => $this->email_deleted_tmpl_settings_fields(),
                        ),
                        'new_order_created' => array(
                            'type' => 'section',
                            'title' => __('For New Order (Created)', ATBDP_TEXTDOMAIN),
                            'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', ATBDP_TEXTDOMAIN),
                            'fields' => $this->email_new_order_tmpl_settings_fields(),
                        ),
                        'offline_new_order_created' => array(
                            'type' => 'section',
                            'title' => __('For New Order (Created using Offline Bank Transfer)', ATBDP_TEXTDOMAIN),
                            'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', ATBDP_TEXTDOMAIN),
                            'fields' => $this->email_offline_new_order_tmpl_settings_fields(),
                        ),
                        'completed_order_created' => array(
                            'type' => 'section',
                            'title' => __('For Completed Order', ATBDP_TEXTDOMAIN),
                            'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', ATBDP_TEXTDOMAIN),
                            'fields' => $this->email_completed_order_tmpl_settings_fields(),
                        ),
                        'listing_contact_email' => array(
                            'type' => 'section',
                            'title' => __('For Listing contact email', ATBDP_TEXTDOMAIN),
                            'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', ATBDP_TEXTDOMAIN),
                            'fields' => $this->listing_contact_email(),
                        ),
                        'registration_confirmation' => array(
                            'type' => 'section',
                            'title' => __('Registration Confirmation', ATBDP_TEXTDOMAIN),
                            'description' => __('You can Customize Email and Notification Templates related settings here. Do not forget to save the changes.', ATBDP_TEXTDOMAIN),
                            'fields' => $this->registration_confirmation_email(),
                        ),
                    )),
                ),
            ));
        }

        /**
         * Get all the settings fields for the new listing email template section
         * @return array
         * @since 3.1.0
         */
        public function get_email_new_tmpl_settings_fields()
        {
            // let's define default data template
            $sub = __('[==SITE_NAME==] : Listing "==LISTING_TITLE==" Received', ATBDP_TEXTDOMAIN);

            $tmpl = __("
Dear ==NAME==,

This email is to notify you that your listing '==LISTING_TITLE==' has been received and it is under review now.
It may take up to 24 hours to complete the review.

Thanks,
The Administrator of ==SITE_NAME==
", ATBDP_TEXTDOMAIN);
            //create small var to highlight important texts
            $c = '<span style="color:#c71585;">'; //color start
            $e = '</span>'; // end color
            /*@todo; make this instruction translatable later*/
            $ph = <<<KAMAL
You can use the following keywords/placeholder in any of your email bodies/templates or subjects to output dynamic value. **Usage: place the placeholder name between $c == $e and $c == $e **. For Example: use **{$c}==SITE_NAME=={$e}** to output The Your Website Name etc. <br/><br/>
**{$c}==NAME=={$e}** : It outputs The listing owner's display name on the site<br/>
**{$c}==USERNAME=={$e}** : It outputs The listing owner's user name on the site<br/>
**{$c}==SITE_NAME=={$e}** : It outputs your site name<br/>
**{$c}==SITE_LINK=={$e}** : It outputs your site name with link<br/>
**{$c}==SITE_URL=={$e}** : It outputs your site url with link<br/>
**{$c}==EXPIRATION_DATE=={$e}** : It outputs Expiration date<br/>
**{$c}==CATEGORY_NAME=={$e}** : It outputs the category name that is going to expire<br/>
**{$c}==LISTING_ID=={$e}** : It outputs the listing's ID<br/>
**{$c}==RENEWAL_LINK=={$e}** : It outputs a link to renewal page<br/>
**{$c}==LISTING_TITLE=={$e}** : It outputs the listing's title<br/>
**{$c}==LISTING_LINK=={$e}** : It outputs the listing's title with link<br/>
**{$c}==LISTING_URL=={$e}** : It outputs the listing's url with link<br/>
**{$c}==ORDER_ID=={$e}** : It outputs the order id. It should be used for order related email only<br/>
**{$c}==ORDER_RECEIPT_URL=={$e}** : It outputs a link to the order receipt page. It should be used for order related email only<br/>
**{$c}==ORDER_DETAILS=={$e}** : It outputs order detailsc. It should be used for order related email only<br/>
**{$c}==TODAY=={$e}** : It outputs the current date<br/>
**{$c}==NOW=={$e}** : It outputs the current time<br/><br/>
**Additionally, you can also use HTML tags in your template.**
KAMAL;


            return apply_filters('atbdp_email_new_tmpl_settings_fields', array(
                array(
                    'type' => 'notebox',
                    'name' => 'email_placeholder_info',
                    'label' => $c . __('You can use Placeholders to output dynamic value', ATBDP_TEXTDOMAIN) . $e,
                    'description' => $ph,
                    'status' => 'normal',
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'email_sub_new_listing',
                    'label' => __('Email Subject', ATBDP_TEXTDOMAIN),
                    'description' => __('Edit the subject for sending to the user when a listing is submitted/received.', ATBDP_TEXTDOMAIN),
                    'default' => $sub,
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'email_tmpl_new_listing',
                    'label' => __('Email Body', ATBDP_TEXTDOMAIN),
                    'description' => __('Edit the email template for sending to the user when a listing is submitted/received. HTML content is allowed too.', ATBDP_TEXTDOMAIN),
                    'default' => $tmpl,
                ),


            ));
        }

        /**
         * Get all the settings fields for the published listing email template section
         * @return array
         * @since 3.1.0
         */
        public function get_email_pub_tmpl_settings_fields()
        {
            // let's define default data
            $sub = __('[==SITE_NAME==] : Listing "==LISTING_TITLE==" published', ATBDP_TEXTDOMAIN);
            $tmpl = __("
Dear ==NAME==,
Congratulations! Your listing '==LISTING_TITLE==' has been approved/published. Now it is publicly available at ==LISTING_URL==

Thanks,
The Administrator of ==SITE_NAME==
", ATBDP_TEXTDOMAIN);

            return apply_filters('atbdp_email_pub_tmpl_settings_fields', array(
                array(
                    'type' => 'textbox',
                    'name' => 'email_sub_pub_listing',
                    'label' => __('Email Subject', ATBDP_TEXTDOMAIN),
                    'description' => __('Edit the subject for sending to the user when a listing is approved/published.', ATBDP_TEXTDOMAIN),
                    'default' => $sub,
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'email_tmpl_pub_listing',
                    'label' => __('Email Body', ATBDP_TEXTDOMAIN),
                    'description' => __('Edit the email template for sending to the user when a listing is approved/published. HTML content is allowed too.', ATBDP_TEXTDOMAIN),
                    'default' => $tmpl,
                ),
            ));
        }

        /**
         * Get all the settings fields for the edited listing email template section
         * @return array
         * @since 3.1.0
         */
        public function get_email_edit_tmpl_settings_fields()
        {
            // let's define default data
            $sub = __('[==SITE_NAME==] : Listing "==LISTING_TITLE==" Edited', ATBDP_TEXTDOMAIN);
            $tmpl = __("
Dear ==NAME==,
Congratulations! Your listing '==LISTING_TITLE==' has been edited. It is publicly available at ==LISTING_URL==

Thanks,
The Administrator of ==SITE_NAME==
", ATBDP_TEXTDOMAIN);

            return apply_filters('atbdp_email_edit_tmpl_settings_fields', array(
                array(
                    'type' => 'textbox',
                    'name' => 'email_sub_edit_listing',
                    'label' => __('Email Subject', ATBDP_TEXTDOMAIN),
                    'description' => __('Edit the subject for sending to the user when a listing is edited.', ATBDP_TEXTDOMAIN),
                    'default' => $sub,
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'email_tmpl_edit_listing',
                    'label' => __('Email Body', ATBDP_TEXTDOMAIN),
                    'description' => __('Edit the email template for sending to the user when a listing is edited. HTML content is allowed too.', ATBDP_TEXTDOMAIN),
                    'default' => $tmpl,
                ),
            ));
        }

        /**
         * Get all the settings fields for the renew listing email template section
         * @return array
         * @since 3.1.0
         */
        public function email_about_expire_tmpl_settings_fields()
        {
            // let's define default data
            $sub = __('[==SITE_NAME==] : Your Listing "==LISTING_TITLE==" is about to expire.', ATBDP_TEXTDOMAIN);
            /*@todo; includes the number of days remaining to expire the listing*/
            $tmpl = __("
Dear ==NAME==,
Your listing '==LISTING_TITLE==' is about to expire. It will expire on ==EXPIRATION_DATE==. You can renew it at ==RENEWAL_LINK==

Thanks,
The Administrator of ==SITE_NAME==
", ATBDP_TEXTDOMAIN);

            return apply_filters('atbdp_email_about_expire_tmpl_settings_fields', array(
                array(
                    'type' => 'slider',
                    'name' => 'email_to_expire_day',
                    'label' => __('When to send expire notice', ATBDP_TEXTDOMAIN),
                    'description' => __('Select the days before a listing expires to send an expiration reminder email', ATBDP_TEXTDOMAIN),
                    'min' => '1',
                    'max' => '120',
                    'step' => '1',
                    'default' => '7',
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'email_sub_to_expire_listing',
                    'label' => __('Email Subject', ATBDP_TEXTDOMAIN),
                    'description' => __('Edit the subject for sending to the user when a listing is ABOUT TO EXPIRE.', ATBDP_TEXTDOMAIN),
                    'default' => $sub,
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'email_tmpl_to_expire_listing',
                    'label' => __('Email Body', ATBDP_TEXTDOMAIN),
                    'description' => __('Edit the email template for sending to the user when a listing is ABOUT TO EXPIRE. HTML content is allowed too.', ATBDP_TEXTDOMAIN),
                    'default' => $tmpl,
                ),
            ));
        }

        /**
         * Get all the settings fields for the renew listing email template section
         * @return array
         * @since 3.1.0
         */
        public function email_expired_tmpl_settings_fields()
        {
            // let's define default data
            $sub = __("[==SITE_NAME==] : Your Listing '==LISTING_TITLE==' has expired.", ATBDP_TEXTDOMAIN);
            $tmpl = __("
Dear ==NAME==,
Your listing '==LISTING_TITLE==' has expired on ==EXPIRATION_DATE==. You can renew it at ==RENEWAL_LINK==

Thanks,
The Administrator of ==SITE_NAME==
", ATBDP_TEXTDOMAIN);

            return apply_filters('atbdp_email_expired_tmpl_settings_fields', array(
                array(
                    'type' => 'textbox',
                    'name' => 'email_sub_expired_listing',
                    'label' => __('Email Subject', ATBDP_TEXTDOMAIN),
                    'description' => __('Edit the subject for sending to the user when a Listing HAS EXPIRED.', ATBDP_TEXTDOMAIN),
                    'default' => $sub,
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'email_tmpl_expired_listing',
                    'label' => __('Email Body', ATBDP_TEXTDOMAIN),
                    'description' => __('Edit the email template for sending to the user when a Listing HAS EXPIRED. HTML content is allowed too.', ATBDP_TEXTDOMAIN),
                    'default' => $tmpl,
                ),

            ));
        }

        /**
         * Get all the settings fields for the renew listing email template section
         * @return array
         * @since 3.1.0
         */
        public function email_renewal_tmpl_settings_fields()
        {
            // let's define default data
            $sub = __('[==SITE_NAME==] : A Reminder to Renew your listing "==LISTING_TITLE=="', ATBDP_TEXTDOMAIN);
            $tmpl = __("
Dear ==NAME==,

We have noticed that you might have forgot to renew your listing '==LISTING_TITLE==' at ==SITE_LINK==. We would like to remind you that it expired on ==EXPIRATION_DATE==. But please don't worry.  You can still renew it by clicking this link: ==RENEWAL_LINK==.

Thanks,
The Administrator of ==SITE_NAME==
", ATBDP_TEXTDOMAIN);

            return apply_filters('atbdp_email_renewal_tmpl_settings_fields', array(
                array(
                    'type' => 'slider',
                    'name' => 'email_renewal_day',
                    'label' => __('When to send renewal reminder', ATBDP_TEXTDOMAIN),
                    'description' => __('Select the days after a listing expires to send a renewal reminder email', ATBDP_TEXTDOMAIN),
                    'min' => '1',
                    'max' => '120',
                    'step' => '1',
                    'default' => '7',

                ),
                array(
                    'type' => 'textbox',
                    'name' => 'email_sub_to_renewal_listing',
                    'label' => __('Email Subject', ATBDP_TEXTDOMAIN),
                    'description' => __('Edit the subject for sending to the user to renew his/her listings.', ATBDP_TEXTDOMAIN),
                    'default' => $sub,
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'email_tmpl_to_renewal_listing',
                    'label' => __('Email Body', ATBDP_TEXTDOMAIN),
                    'description' => __('Edit the email template for sending to the user to renew his/her listings. HTML content is allowed too.', ATBDP_TEXTDOMAIN),
                    'default' => $tmpl,
                ),


            ));
        }

        /**
         * Get all the settings fields for the renewed listing email template section
         * @return array
         * @since 3.1.0
         */
        public function email_renewed_tmpl_settings_fields()
        {
            // let's define default data
            $sub = __('[==SITE_NAME==] : Your Listing "==LISTING_TITLE==" Has Renewed', ATBDP_TEXTDOMAIN);
            $tmpl = __("
Dear ==NAME==,

Congratulations!
Your listing '==LISTING_LINK==' with the ID #==LISTING_ID== has been renewed successfully at ==SITE_LINK==.
Your listing is now publicly viewable at ==LISTING_URL==

Thanks,
The Administrator of ==SITE_NAME==
", ATBDP_TEXTDOMAIN);

            return apply_filters('atbdp_email_renewed_tmpl_settings_fields', array(
                array(
                    'type' => 'textbox',
                    'name' => 'email_sub_renewed_listing',
                    'label' => __('Email Subject', ATBDP_TEXTDOMAIN),
                    'description' => __('Edit the subject for sending to the user his/her listings has renewed successfully.', ATBDP_TEXTDOMAIN),
                    'default' => $sub,
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'email_tmpl_renewed_listing',
                    'label' => __('Email Body', ATBDP_TEXTDOMAIN),
                    'description' => __('Edit the email template for sending to the user his/her listings has renewed successfully. HTML content is allowed too.', ATBDP_TEXTDOMAIN),
                    'default' => $tmpl,
                ),


            ));
        }

        /**
         * Get all the settings fields for the deleted listing email template section
         * @return array
         * @since 3.1.0
         */
        public function email_deleted_tmpl_settings_fields()
        {
            // let's define default data
            $sub = __('[==SITE_NAME==] : Your Listing "==LISTING_TITLE==" Has Been Deleted', ATBDP_TEXTDOMAIN);
            $tmpl = __("
Dear ==NAME==,

Your listing '==LISTING_LINK==' with the ID #==LISTING_ID== has been deleted successfully at ==SITE_LINK==.

Thanks,
The Administrator of ==SITE_NAME==
", ATBDP_TEXTDOMAIN);

            return apply_filters('atbdp_email_deleted_tmpl_settings_fields', array(
                array(
                    'type' => 'textbox',
                    'name' => 'email_sub_deleted_listing',
                    'label' => __('Email Subject', ATBDP_TEXTDOMAIN),
                    'description' => __('Edit the subject for sending to the user when his/her listings has deleted successfully.', ATBDP_TEXTDOMAIN),
                    'default' => $sub,
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'email_tmpl_deleted_listing',
                    'label' => __('Email Body', ATBDP_TEXTDOMAIN),
                    'description' => __('Edit the email template for sending to the user when his/her listings has deleted successfully. HTML content is allowed too.', ATBDP_TEXTDOMAIN),
                    'default' => $tmpl,
                ),


            ));
        }


        /**
         * Get all the settings fields for the new order email template section
         * @return array
         * @since 3.1.0
         */
        public function email_new_order_tmpl_settings_fields()
        {
            // let's define default data
            $sub = __('[==SITE_NAME==] : Your Order (#==ORDER_ID==) Received.', ATBDP_TEXTDOMAIN);
            $tmpl = __("
Dear ==NAME==,

Thank you very much for your order.
This email is to notify you that your order (#==ORDER_ID==) has been received. You can check your order details and progress by clicking the link below.

Order Details Page: ==ORDER_RECEIPT_URL==

Your order summery:
==ORDER_DETAILS==


NB. You need to be logged in your account to access the order details page.

Thanks,
The Administrator of ==SITE_NAME==
", ATBDP_TEXTDOMAIN);

            return apply_filters('atbdp_new_order_tmpl_settings_fields', array(
                array(
                    'type' => 'textbox',
                    'name' => 'email_sub_new_order',
                    'label' => __('Email Subject', ATBDP_TEXTDOMAIN),
                    'description' => __('Edit the subject for sending to the user when an order is created.', ATBDP_TEXTDOMAIN),
                    'default' => $sub,
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'email_tmpl_new_order',
                    'label' => __('Email Body', ATBDP_TEXTDOMAIN),
                    'description' => __('Edit the email template for sending to the user when an order is created.', ATBDP_TEXTDOMAIN),
                    'default' => $tmpl,
                ),


            ));
        }


        /**
         * Get all the settings fields for the offline new order email template section
         * @return array
         * @since 3.1.0
         */
        public function email_offline_new_order_tmpl_settings_fields()
        {
            // let's define default data
            $sub = __('[==SITE_NAME==] : Your Order (#==ORDER_ID==) Received.', ATBDP_TEXTDOMAIN);
            $tmpl = sprintf(__("
Dear ==NAME==,

Thank you very much for your order.
This email is to notify you that your order (#==ORDER_ID==) has been received.

%s

You can check your order details and progress by clicking the link below.
Order Details Page: ==ORDER_RECEIPT_URL==

Your order summery:
==ORDER_DETAILS==


NB. You need to be logged in your account to access the order details page.

Thanks,
The Administrator of ==SITE_NAME==
", ATBDP_TEXTDOMAIN), get_directorist_option('bank_transfer_instruction'));

            return apply_filters('atbdp_offline_new_order_tmpl_settings_fields', array(
                array(
                    'type' => 'textbox',
                    'name' => 'email_sub_offline_new_order',
                    'label' => __('Email Subject', ATBDP_TEXTDOMAIN),
                    'description' => __('Edit the subject for sending to the user when an order is created using offline payment like bank transfer.', ATBDP_TEXTDOMAIN),
                    'default' => $sub,
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'email_tmpl_offline_new_order',
                    'label' => __('Email Body', ATBDP_TEXTDOMAIN),
                    'description' => __('Edit the email template for sending to the user when an order is created using offline payment like bank transfer.', ATBDP_TEXTDOMAIN),
                    'default' => $tmpl,
                ),


            ));
        }

        /**
         * Get all the settings fields for the completed new order email template section
         * @return array
         * @since 3.1.0
         */
        public function email_completed_order_tmpl_settings_fields()
        {
            // let's define default data
            $sub = __('[==SITE_NAME==] : Congratulation! Your Order #==ORDER_ID== Completed.', ATBDP_TEXTDOMAIN);


            $tmpl = __("
Dear ==NAME==,

Congratulation! This email is to notify you that your order #==ORDER_ID== has been completed.

You can check your order details by clicking the link below.
Order Details Page: ==ORDER_RECEIPT_URL==

Your order summery:
==ORDER_DETAILS==


NB. You need to be logged in your account to access the order details page.

Thanks,
The Administrator of ==SITE_NAME==
", ATBDP_TEXTDOMAIN);

            return apply_filters('atbdp_completed_order_tmpl_settings_fields', array(
                array(
                    'type' => 'textbox',
                    'name' => 'email_sub_completed_order',
                    'label' => __('Email Subject', ATBDP_TEXTDOMAIN),
                    'description' => __('Edit the subject for sending to the user when an order is completed', ATBDP_TEXTDOMAIN),
                    'default' => $sub,
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'email_tmpl_completed_order',
                    'label' => __('Email Body', ATBDP_TEXTDOMAIN),
                    'description' => __('Edit the email template for sending to the user when an order is completed.', ATBDP_TEXTDOMAIN),
                    'default' => $tmpl,
                ),


            ));
        }


        /**
         * Get all the settings fields for the offline new order email template section
         * @return array
         * @since 3.1.0
         */
        public function registration_confirmation_email()
        {
            // let's define default data
            $sub = __('Registration Confirmation!', ATBDP_TEXTDOMAIN);

            $tmpl = __("
Dear User,

Congratulations! Your registration is completed!

This email is sent automatically for information purpose only. Please do not respond to this.
You can login now using the below credentials:

", ATBDP_TEXTDOMAIN);

            return apply_filters('atbdp_registration_confirmation_tmpl_settings_fields', array(
                array(
                    'type' => 'textbox',
                    'name' => 'email_sub_registration_confirmation',
                    'label' => __('Email Subject', ATBDP_TEXTDOMAIN),
                    'description' => __('Edit the subject for sending to the user when listing contact message send.', ATBDP_TEXTDOMAIN),
                    'default' => $sub,
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'email_tmpl_registration_confirmation',
                    'label' => __('Email Body', ATBDP_TEXTDOMAIN),
                    'description' => __('Edit the email template for sending to new user after registration [Note: Credentials details included at the bottom of the template]', ATBDP_TEXTDOMAIN),
                    'default' => $tmpl,
                ),


            ));
        }


     /**
         * Get all the settings fields for the offline new order email template section
         * @return array
         * @since 3.1.0
         */
        public function listing_contact_email()
        {
            // let's define default data
            $sub = __('[==SITE_NAME==] Contact via "[==LISTING_TITLE==]"', ATBDP_TEXTDOMAIN);

            $tmpl = __("
Dear [==NAME==],

You have received a reply from your listing at ==LISTING_URL==.

Name: ==SENDER_NAME==
Email: ==SENDER_EMAIL==
Message: ==MESSAGE==
Time: ==NOW==

Thanks,
The Administrator of ==SITE_NAME==
", ATBDP_TEXTDOMAIN);

            return apply_filters('atbdp_completed_order_tmpl_settings_fields', array(
                array(
                    'type' => 'textbox',
                    'name' => 'email_sub_listing_contact_email',
                    'label' => __('Email Subject', ATBDP_TEXTDOMAIN),
                    'description' => __('Edit the subject for sending to the user when listing contact message send.', ATBDP_TEXTDOMAIN),
                    'default' => $sub,
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'email_tmpl_listing_contact_email',
                    'label' => __('Email Body', ATBDP_TEXTDOMAIN),
                    'description' => __('Edit the email template for sending to the user when when listing contact message send', ATBDP_TEXTDOMAIN),
                    'default' => $tmpl,
                ),


            ));
        }


        /**
         * Get all the settings fields for the email settings section
         * @return array
         * @since 3.1.0
         */
        public function get_email_settings_fields()
        {
            return apply_filters('atbdp_email_settings_fields', array(
                    array(
                        'type' => 'toggle',
                        'name' => 'disable_email_notification',
                        'label' => __('Disable all Email Notifications', ATBDP_TEXTDOMAIN),
                        'default' => '',
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'email_from_name',
                        'label' => __('Email\'s "From Name"', ATBDP_TEXTDOMAIN),
                        'description' => __('The name should be used as From Name in the email generated by the plugin.', ATBDP_TEXTDOMAIN),
                        'default' => get_option('blogname'),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'email_from_email',
                        'label' => __('Email\'s "From Email"', ATBDP_TEXTDOMAIN),
                        'description' => __('The email should be used as From Email in the email generated by the plugin.', ATBDP_TEXTDOMAIN),
                        'default' => get_option('admin_email'),
                    ),
                    array(
                        'type' => 'textarea',
                        'name' => 'admin_email_lists',
                        'label' => __('Admin Email Address(es)', ATBDP_TEXTDOMAIN),
                        'description' => __('Enter the one or more admin email addresses (comma separated) to send notification. Eg. admin1@example.com, admin2@example.com etc', ATBDP_TEXTDOMAIN),
                        'default' => get_option('admin_email'),
                    ),
                    array(
                        'type' => 'multiselect',
                        'name' => 'notify_admin',
                        'label' => __('Notify the Admin when Any of the Selected Event Happens', ATBDP_TEXTDOMAIN),
                        'description' => __('Select the situation when you would like to send an email to the Admin', ATBDP_TEXTDOMAIN),
                        'items' => $this->events_to_notify_admin(),
                        'default' => $this->default_events_to_notify_admin(),
                    ),
                    array(
                        'type' => 'multiselect',
                        'name' => 'notify_user',
                        'label' => __('Notify the Listing Owner when Any of the Selected Event Happens', ATBDP_TEXTDOMAIN),
                        'description' => __('Select the situation when you would like to send an email to the Listing', ATBDP_TEXTDOMAIN),
                        'items' => $this->events_to_notify_user(),
                        'default' => $this->default_events_to_notify_user(),
                    ),
                )
            );
        }

        /**
         * Get the list of an array of notification events array to notify admin
         * @return array It returns an array of events when an admin should be notified
         * @since 3.1.0
         */
        public function events_to_notify_admin()
        {
            $events = array_merge($this->default_notifiable_events(), $this->only_admin_notifiable_events());
            return apply_filters('atbdp_events_to_notify_admin', $events);
        }

        /**
         * Get the list of an array of notification events array to notify user
         * @return array It returns an array of events when an user should be notified
         * @since 3.1.0
         */
        public function events_to_notify_user()
        {
            $events = array_merge($this->default_notifiable_events(), $this->only_user_notifiable_events());
            return apply_filters('atbdp_events_to_notify_user', $events);
        }

        /**
         * Get the default events to notify the admin.
         * @return array It returns an array of default events when an admin should be notified.
         * @since 3.1.0
         */
        public function default_events_to_notify_admin()
        {
            return apply_filters('atbdp_default_events_to_notify_admin', array(
                'order_created',
                'order_completed',
                'listing_submitted',
                'payment_received',
                'listing_published',
                'listing_deleted',
                'listing_contact_form',
                'listing_review'
            ));
        }

        /**
         * Get the default events to notify the user.
         * @return array It returns an array of default events when an user should be notified.
         * @since 3.1.0
         */
        public function default_events_to_notify_user()
        {
            return apply_filters('atbdp_default_events_to_notify_user', array(
                'order_created',
                'listing_submitted',
                'payment_received',
                'listing_published',
                'listing_to_expire',
                'listing_expired',
                'remind_to_renew',
                'listing_renewed',
                'order_completed',
                'listing_edited',
                'listing_deleted',
                'listing_contact_form',
            ));
        }

        /**
         * Get an array of events to notify both the admin and the users
         * @return array it returns an array of events
         * @since 3.1.0
         */
        private function default_notifiable_events()
        {
            return apply_filters('atbdp_default_notifiable_events', array(
                array(
                    'value' => 'order_created',
                    'label' => __('Order Created', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'value' => 'order_completed',
                    'label' => __('Order Completed', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'value' => 'listing_submitted',
                    'label' => __('New Listing Submitted', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'value' => 'listing_published',
                    'label' => __('Listing Approved/Published', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'value' => 'listing_edited',
                    'label' => __('Listing Edited', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'value' => 'payment_received',
                    'label' => __('Payment Received', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'value' => 'listing_deleted',
                    'label' => __('Listing Deleted', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'value' => 'listing_contact_form',
                    'label' => __('Listing Contact Form', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'value' => 'listing_review',
                    'label' => __('Listing Review', ATBDP_TEXTDOMAIN),
                ),
            ));
        }

        /**
         * Get an array of events to notify only the admin
         * @return array it returns an array of events
         * @since 3.1.0
         */
        private function only_admin_notifiable_events()
        {
            return apply_filters('atbdp_only_admin_notifiable_events', array(
                array(
                    'value' => 'listing_owner_contacted',
                    'label' => __('Listing owner is contacted', ATBDP_TEXTDOMAIN),
                ),
            ));
        }

        /**
         * Get an array of events to notify only users
         * @return array it returns an array of events
         * @since 3.1.0
         */
        private function only_user_notifiable_events()
        {
            return apply_filters('atbdp_only_user_notifiable_events', array(
                array(
                    'value' => 'listing_to_expire',
                    'label' => __('Listing nearly Expired', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'value' => 'listing_expired',
                    'label' => __('Listing Expired', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'value' => 'remind_to_renew',
                    'label' => __('Remind to renew', ATBDP_TEXTDOMAIN),
                ),
            ));
        }

        /**
         * Get all the pages in an array where each page is an array of key:value:id and key:label:name
         *
         * Example : array(
         *                  array('value'=> 1, 'label'=> 'page_name'),
         *                  array('value'=> 50, 'label'=> 'page_name'),
         *          )
         * @return array page names with key value pairs in a multi-dimensional array
         * @since 3.0.0
         */
        function get_pages_vl_arrays()
        {
            $pages = get_pages();
            $pages_options = array();
            if ($pages) {
                foreach ($pages as $page) {
                    $pages_options[] = array('value' => $page->ID, 'label' => $page->post_title);
                }
            }

            return $pages_options;
        }


        /**
         * Get all the submenus for the extension menu
         * @return array It returns an array of submenus
         * @since 3.0.0
         */
        function get_extension_settings_submenus()
        {

            return apply_filters('atbdp_extension_settings_submenus', array(
                'submenu_1' => array(
                    'title' => __('Extensions General', ATBDP_TEXTDOMAIN),
                    'name' => 'extensions_switch',
                    'icon' => 'font-awesome:fa-home',
                    'controls' => apply_filters('atbdp_extension_settings_controls', array(
                        'extensions' => array(
                            'type' => 'section',
                            'title' => __('Extensions General Settings', ATBDP_TEXTDOMAIN),
                            'description' => __('You can Customize Extensions-related settings here. You can enable or disable any extensions here. Here, YES means Enabled, and NO means disabled. After switching any option, Do not forget to save the changes.', ATBDP_TEXTDOMAIN),
                            'fields' => $this->get_extension_settings_fields(),
                        ),
                    )),
                ),
            ));
        }

        /**
         * @return array
         */
        function get_currency_settings_fields()
        {
            return apply_filters('atbdp_currency_settings_fields', array(
                    array(
                        'type' => 'notebox',
                        'name' => 'g_currency_note',
                        'label' => __('Note About This Currency Settings:', ATBDP_TEXTDOMAIN),
                        'description' => __('This currency settings lets you customize how you would like to display price amount in your website. However, you can accept currency in a different currency. Therefore, for accepting currency in a different currency, Go to Gateway Settings Tab.', ATBDP_TEXTDOMAIN),
                        'status' => 'info',
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'g_currency',
                        'label' => __('Currency Name', ATBDP_TEXTDOMAIN),
                        'description' => __('Enter the Name of the currency eg. USD or GBP etc.', ATBDP_TEXTDOMAIN),
                        'default' => 'USD',
                    ),
                    /*@todo; lets user use space as thousand separator in future. @see: https://docs.oracle.com/cd/E19455-01/806-0169/overview-9/index.html
                    */
                    array(
                        'type' => 'textbox',
                        'name' => 'g_thousand_separator',
                        'label' => __('Thousand Separator', ATBDP_TEXTDOMAIN),
                        'description' => __('Enter the currency thousand separator. Eg. , or . etc.', ATBDP_TEXTDOMAIN),
                        'default' => ',',
                    ),

                    array(
                        'type' => 'toggle',
                        'name' => 'allow_decimal',
                        'label' => __('Allow Decimal', ATBDP_TEXTDOMAIN),
                        'default' => 1,
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'g_decimal_separator',
                        'label' => __('Decimal Separator', ATBDP_TEXTDOMAIN),
                        'description' => __('Enter the currency decimal separator. Eg. "." or ",". Default is "."', ATBDP_TEXTDOMAIN),
                        'default' => '.',
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'g_currency_position',
                        'label' => __('Currency Position', ATBDP_TEXTDOMAIN),
                        'description' => __('Select where you would like to show the currency symbol. Default is before. Eg. $5', ATBDP_TEXTDOMAIN),
                        'default' => array(
                            'before',
                        ),
                        'items' => array(
                            array(
                                'value' => 'before',
                                'label' => __('$5 - Before', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'after',
                                'label' => __('After - 5$', ATBDP_TEXTDOMAIN),
                            ),
                        ),
                    ),
                )
            );
        }

        /**
         * @return array
         * @since 4.6.0
         */
        function get_seo_settings_fields()
        {
            return apply_filters('atbdp_seo_settings_fields', array(
                    array(
                        'type' => 'toggle',
                        'name' => 'overwrite_by_yoast',
                        'label' => __('Disable Overwrite by Yoast', ATBDP_TEXTDOMAIN),
                        'description' => __('Here Yes means Directorist pages will use titles & metas settings from bellow. Otherwise it will use titles & metas settings from Yoast.', ATBDP_TEXTDOMAIN),
                        'default' => 0,
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'add_listing_page_meta_title',
                        'label' => __('Add Listing Page Meta Title', ATBDP_TEXTDOMAIN),
                        'description' => __('Default the title of the page set as frontpage.', ATBDP_TEXTDOMAIN),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'add_listing_page_meta_desc',
                        'label' => __('Add Listing Page Meta Description', ATBDP_TEXTDOMAIN),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'all_listing_meta_title',
                        'label' => __('All Listing Page Meta Title', ATBDP_TEXTDOMAIN),
                        'description' => __('Default the title of the page set as frontpage.', ATBDP_TEXTDOMAIN),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'all_listing_meta_desc',
                        'label' => __('All Listing Page Meta Description', ATBDP_TEXTDOMAIN),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'dashboard_meta_title',
                        'label' => __('User Dashboard Page Meta Title', ATBDP_TEXTDOMAIN),
                        'description' => __('Default the title of the page set as frontpage.', ATBDP_TEXTDOMAIN),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'dashboard_meta_desc',
                        'label' => __('Dashboard Page Meta Description', ATBDP_TEXTDOMAIN),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'author_profile_meta_title',
                        'label' => __('Author Page Meta Title', ATBDP_TEXTDOMAIN),
                        'description' => __('Default the title of the page set as frontpage.', ATBDP_TEXTDOMAIN),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'author_page_meta_desc',
                        'label' => __('Author Page Meta Description', ATBDP_TEXTDOMAIN),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'category_meta_title',
                        'label' => __('Category Page Meta Title', ATBDP_TEXTDOMAIN),
                        'description' => __('Default the title of the page set as frontpage.', ATBDP_TEXTDOMAIN),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'category_meta_desc',
                        'label' => __('Category Page Meta Description', ATBDP_TEXTDOMAIN),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'single_category_meta_title',
                        'label' => __('Single Category Page Meta Title', ATBDP_TEXTDOMAIN),
                        'description' => __('Default the title of the category.', ATBDP_TEXTDOMAIN),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'single_category_meta_desc',
                        'label' => __('Single Category Page Meta Description', ATBDP_TEXTDOMAIN),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'all_locations_meta_title',
                        'label' => __('All Locations Page Meta Title', ATBDP_TEXTDOMAIN),
                        'description' => __('Default the title of the page set as frontpage.', ATBDP_TEXTDOMAIN),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'all_locations_meta_desc',
                        'label' => __('All Locations Page Meta Description', ATBDP_TEXTDOMAIN),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'single_locations_meta_title',
                        'label' => __('Single Location Page Meta Title', ATBDP_TEXTDOMAIN),
                        'description' => __('Default the title of the location.', ATBDP_TEXTDOMAIN),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'single_locations_meta_desc',
                        'label' => __('Single Locations Page Meta Description', ATBDP_TEXTDOMAIN),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'registration_meta_title',
                        'label' => __('Registration Page Meta Title', ATBDP_TEXTDOMAIN),
                        'description' => __('Default the title of the page set as frontpage.', ATBDP_TEXTDOMAIN),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'registration_meta_desc',
                        'label' => __('Registration Page Meta Description', ATBDP_TEXTDOMAIN),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'login_meta_title',
                        'label' => __('Login Page Meta Title', ATBDP_TEXTDOMAIN),
                        'description' => __('Default the title of the page set as frontpage.', ATBDP_TEXTDOMAIN),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'login_meta_desc',
                        'label' => __('Login Page Meta Description', ATBDP_TEXTDOMAIN),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'homepage_meta_title',
                        'label' => __('Search Home Page Meta Title', ATBDP_TEXTDOMAIN),
                        'description' => __('Default the title of the page set as frontpage.', ATBDP_TEXTDOMAIN),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'homepage_meta_desc',
                        'label' => __('Search Home Page Meta Description', ATBDP_TEXTDOMAIN),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'meta_title_for_search_result',
                        'label' => __('Search Result Page Meta Title', ATBDP_TEXTDOMAIN),
                        'items' => array(
                            array(
                                'value' => 'searched_value',
                                'label' => __('From User Search', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'custom',
                                'label' => __('Custom', ATBDP_TEXTDOMAIN),
                            ),
                        ),
                        'default' => array(
                            'value' => 'searched_value',
                            'label' => __('From User Search', ATBDP_TEXTDOMAIN),
                        ),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'search_result_meta_title',
                        'label' => __('Custom Meta Title', ATBDP_TEXTDOMAIN),
                        'description' => __('Default the title of the page set as frontpage.', ATBDP_TEXTDOMAIN),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'search_result_meta_desc',
                        'label' => __('Search Result Page Meta Description', ATBDP_TEXTDOMAIN),
                    ),

                )
            );
        }

        /**
         * Get all the settings fields for the listings page section
         * @return array
         * @since 4.0.0
         */
        function get_listings_page_settings_fields()
        {
            $business_hours = '(Requires <a style="color: red" href="https://aazztech.com/product/directorist-business-hours/" target="_blank">Business Hours</a> extension)';
            return apply_filters('atbdp_listings_settings_fields', array(

                    'new_listing_status' => array(
                        'type' => 'select',
                        'name' => 'new_listing_status',
                        'label' => __('New Listing\'s Default Status', ATBDP_TEXTDOMAIN),
                        'items' => array(
                            array(
                                'value' => 'publish',
                                'label' => __('Published', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'pending',
                                'label' => __('Pending', ATBDP_TEXTDOMAIN),
                            ),
                        ),
                        'default' => array(
                            'value' => 'publish',
                            'label' => __('Published', ATBDP_TEXTDOMAIN),
                        ),
                    ),
                    'edit_listing_status' => array(
                        'type' => 'select',
                        'name' => 'edit_listing_status',
                        'label' => __('Edited Listing\'s Default Status', ATBDP_TEXTDOMAIN),
                        'items' => array(
                            array(
                                'value' => 'publish',
                                'label' => __('Published', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'pending',
                                'label' => __('Pending', ATBDP_TEXTDOMAIN),
                            ),
                        ),
                        'default' => array(
                            'value' => 'publish',
                            'label' => __('Published', ATBDP_TEXTDOMAIN),
                        ),
                    ),

                    'display_listings_header' => array(
                        'type' => 'toggle',
                        'name' => 'display_listings_header',
                        'label' => __('Display Header', ATBDP_TEXTDOMAIN),
                        'default' => 1,
                    ),
                    'all_listing_title' => array(
                        'type' => 'textbox',
                        'name' => 'all_listing_title',
                        'label' => __('Header Title', ATBDP_TEXTDOMAIN),
                        'default' => __('Total Listings Found:', ATBDP_TEXTDOMAIN),
                    ),
                    'listing_filters_button' => array(
                        'type' => 'toggle',
                        'name' => 'listing_filters_button',
                        'label' => __('Display Filters Button', ATBDP_TEXTDOMAIN),
                        'default' => 1,
                    ),

                    'listings_filter_button_text' => array(
                        'type' => 'textbox',
                        'name' => 'listings_filter_button_text',
                        'label' => __('Filters Button Text', ATBDP_TEXTDOMAIN),
                        'default' => __('Filters', ATBDP_TEXTDOMAIN),
                    ),
                    'listings_display_filter' => array(
                        'type' => 'select',
                        'name' => 'listings_display_filter',
                        'label' => __('Open Filter Fields', ATBDP_TEXTDOMAIN),
                        'items' => array(
                            array(
                                'value' => 'overlapping',
                                'label' => __('Overlapping', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'sliding',
                                'label' => __('Sliding', ATBDP_TEXTDOMAIN),
                            ),
                        ),
                        'default' => array(
                            'value' => 'sliding',
                            'label' => __('Sliding', ATBDP_TEXTDOMAIN),
                        ),
                    ),
                    'listing_filters_fields' => array(
                        'type' => 'checkbox',
                        'name' => 'listing_filters_fields',
                        'label' => __('Filter Fields', ATBDP_TEXTDOMAIN),
                        'validation' => 'minselected[0]|maxselected[15]',
                        'items' => array(
                            array(
                                'value' => 'search_text',
                                'label' => __('Text', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_category',
                                'label' => __('Category', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_location',
                                'label' => __('Location', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_price',
                                'label' => __('Price (Min - Max)', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_price_range',
                                'label' => __('Price Range', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_rating',
                                'label' => __('Rating', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_tag',
                                'label' => __('Tag', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_open_now',
                                'label' => sprintf(__('Open Now %s', ATBDP_TEXTDOMAIN), !class_exists('BD_Business_Hour')?$business_hours:'')),
                            array(
                                'value' => 'search_custom_fields',
                                'label' => __('Custom Fields', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_website',
                                'label' => __('Website', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_email',
                                'label' => __('Email', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_phone',
                                'label' => __('Phone', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_fax',
                                'label' => __('Fax', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_address',
                                'label' => __('Address', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_zip_code',
                                'label' => __('Zip/Post Code', ATBDP_TEXTDOMAIN),
                            ),
                        ),
                        'default' => array(
                            'search_text',
                            'search_category',
                            'search_location',
                            'search_price',
                            'search_price_range',
                            'search_rating',
                            'search_tag',
                            'search_custom_fields'
                        ),
                    ),
                    'listings_filters_button' =>  array(
                        'type' => 'checkbox',
                        'name' => 'listings_filters_button',
                        'label' => __('Filter Buttons', ATBDP_TEXTDOMAIN),
                        'validation' => 'minselected[0]|maxselected[5]',
                        'items' => array(
                            array(
                                'value' => 'reset_button',
                                'label' => __('Reset', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'apply_button',
                                'label' => __('Apply', ATBDP_TEXTDOMAIN),
                            ),
                        ),
                        'default' => array(
                            'reset_button',
                            'apply_button',
                        ),
                    ),
                    'listings_reset_text' => array(
                        'type' => 'textbox',
                        'name' => 'listings_reset_text',
                        'label' => __('Reset Filters Button text', ATBDP_TEXTDOMAIN),
                        'default' => __('Reset Filters', ATBDP_TEXTDOMAIN),
                    ),
                    'listings_apply_text' =>  array(
                        'type' => 'textbox',
                        'name' => 'listings_apply_text',
                        'label' => __('Apply Filters Button text', ATBDP_TEXTDOMAIN),
                        'default' => __('Apply Filters', ATBDP_TEXTDOMAIN),
                    ),
                    'listings_search_text_placeholder' =>  array(
                        'type' => 'textbox',
                        'name' => 'listings_search_text_placeholder',
                        'label' => __('Search Bar Placeholder', ATBDP_TEXTDOMAIN),
                        'default' => __('What are you looking for?', ATBDP_TEXTDOMAIN),
                    ),
                    'listings_category_placeholder' =>  array(
                        'type' => 'textbox',
                        'name' => 'listings_category_placeholder',
                        'label' => __('Category Placeholder', ATBDP_TEXTDOMAIN),
                        'default' => __('Select a category', ATBDP_TEXTDOMAIN),
                    ),
                    'listings_location_placeholder' =>  array(
                        'type' => 'textbox',
                        'name' => 'listings_location_placeholder',
                        'label' => __('Location Placeholder', ATBDP_TEXTDOMAIN),
                        'default' => __('Select a location', ATBDP_TEXTDOMAIN),
                    ),
                    'display_sort_by' =>  array(
                        'type' => 'toggle',
                        'name' => 'display_sort_by',
                        'label' => __('Display "Sort By" Dropdown', ATBDP_TEXTDOMAIN),
                        'default' => 1,
                    ),
                    'sort_by_text' =>  array(
                        'type' => 'textbox',
                        'name' => 'sort_by_text',
                        'label' => __('"Sort By" Text', ATBDP_TEXTDOMAIN),
                        'default' => __('Sort By', ATBDP_TEXTDOMAIN),
                    ),
                    'listings_sort_by_items' =>  array(
                        'type' => 'checkbox',
                        'name' => 'listings_sort_by_items',
                        'label' => __('"Sort By" Dropdown', ATBDP_TEXTDOMAIN),
                        'validation' => 'minselected[0]|maxselected[10]',
                        'items' => array(
                            array(
                                'value' => 'a_z',
                                'label' => __('A to Z (title)', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'z_a',
                                'label' => __('Z to A (title)', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'latest',
                                'label' => __('Latest listings', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'oldest',
                                'label' => __('Oldest listings', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'popular',
                                'label' => __('Popular listings', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'price_low_high',
                                'label' => __('Price (low to high)', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'price_high_low',
                                'label' => __('Price (high to low)', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'random',
                                'label' => __('Random listings', ATBDP_TEXTDOMAIN),
                            ),
                        ),
                        'default' => array(
                            'a_z',
                            'z_a',
                            'latest',
                            'oldest',
                            'popular',
                            'price_low_high',
                            'price_high_low',
                            'random'
                        ),
                    ),
                    'display_view_as' => array(
                        'type' => 'toggle',
                        'name' => 'display_view_as',
                        'label' => __('Display "View As" Dropdown', ATBDP_TEXTDOMAIN),
                        'default' => 1,
                    ),
                    'view_as_text' =>  array(
                        'type' => 'textbox',
                        'name' => 'view_as_text',
                        'label' => __('"View As" Text', ATBDP_TEXTDOMAIN),
                        'default' => __('View As', ATBDP_TEXTDOMAIN),
                    ),
                    'listings_view_as_items' => array(
                        'type' => 'checkbox',
                        'name' => 'listings_view_as_items',
                        'label' => __('"View As" Dropdown', ATBDP_TEXTDOMAIN),
                        'validation' => 'minselected[0]|maxselected[3]',
                        'items' => array(
                            array(
                                'value' => 'listings_grid',
                                'label' => __('Grid', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'listings_list',
                                'label' => __('List', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'listings_map',
                                'label' => __('Map', ATBDP_TEXTDOMAIN),
                            ),
                        ),
                        'default' => array(
                            'listings_grid',
                            'listings_list',
                            'listings_map'
                        ),
                    ),
                    'default_listing_view' => array(
                        'type' => 'select',
                        'name' => 'default_listing_view',
                        'label' => __('Default View', ATBDP_TEXTDOMAIN),
                        'items' => array(
                            array(
                                'value' => 'grid',
                                'label' => __('Grid', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'list',
                                'label' => __('List', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'map',
                                'label' => __('Map', ATBDP_TEXTDOMAIN),
                            ),
                        ),
                        'default' => array(
                            'value' => 'grid',
                            'label' => __('Grid', ATBDP_TEXTDOMAIN),
                        ),
                    ),
                    'grid_view_as' => array(
                        'type' => 'select',
                        'name' => 'grid_view_as',
                        'label' => __('Grid View', ATBDP_TEXTDOMAIN),
                        'items' => array(
                            array(
                                'value' => 'masonry_grid',
                                'label' => __('Masonry', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'normal_grid',
                                'label' => __('Normal', ATBDP_TEXTDOMAIN),
                            ),
                        ),
                        'default' => array(
                            'value' => 'normal_grid',
                            'label' => __('Normal', ATBDP_TEXTDOMAIN),
                        ),
                    ),
                    'all_listing_columns' => array(
                        'type' => 'slider',
                        'name' => 'all_listing_columns',
                        'label' => __('Number of Columns', ATBDP_TEXTDOMAIN),
                        'min' => '1',
                        'max' => '5',
                        'step' => '1',
                        'default' => '3',
                        'validation' => 'numeric|minlength[1]',
                    ),
                    'order_listing_by' =>  array(
                        'type' => 'select',
                        'name' => 'order_listing_by',
                        'label' => __('Listings Order By', ATBDP_TEXTDOMAIN),
                        'items' => array(
                            array(
                                'value' => 'title',
                                'label' => __('Title', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'date',
                                'label' => __('Date', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'price',
                                'label' => __('Price', ATBDP_TEXTDOMAIN),
                            ),
                        ),
                        'default' => array(
                            'value' => 'date',
                            'label' => __('Date', ATBDP_TEXTDOMAIN),
                        ),
                    ),
                    'sort_listing_by' =>  array(
                        'type' => 'select',
                        'name' => 'sort_listing_by',
                        'label' => __('Listings Sort By', ATBDP_TEXTDOMAIN),
                        'items' => array(
                            array(
                                'value' => 'asc',
                                'label' => __('Ascending', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'desc',
                                'label' => __('Descending', ATBDP_TEXTDOMAIN),
                            ),
                        ),
                        'default' => array(
                            'value' => 'desc',
                            'label' => __('Descending', ATBDP_TEXTDOMAIN),
                        ),
                    ),

                )
            );
        }

        /**
         * Get all the settings fields for the categories page section
         * @return array
         * @since 4.0.0
         */
        function get_categories_settings_fields()
        {
            return apply_filters('atbdp_categories_settings_fields', array(

                    array(
                        'type' => 'select',
                        'name' => 'display_categories_as',
                        'label' => __('Default View', ATBDP_TEXTDOMAIN),
                        'items' => array(
                            array(
                                'value' => 'grid',
                                'label' => __('Grid', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'list',
                                'label' => __('List', ATBDP_TEXTDOMAIN),
                            ),
                        ),
                        'default' => array(
                            'value' => 'grid',
                            'label' => __('Grid', ATBDP_TEXTDOMAIN),
                        ),
                    ),
                    array(
                        'type' => 'slider',
                        'name' => 'categories_column_number',
                        'label' => __('Number of  Columns', ATBDP_TEXTDOMAIN),
                        'description' => __('Set how many columns to display on categories page.', ATBDP_TEXTDOMAIN),
                        'min' => '1',
                        'max' => '5',
                        'step' => '1',
                        'default' => '4',
                        'validation' => 'numeric|minlength[1]',
                    ),
                    array(
                        'type' => 'slider',
                        'name' => 'categories_depth_number',
                        'label' => __('Sub-category Depth', ATBDP_TEXTDOMAIN),
                        'description' => __('Set how many sub-categories to display.', ATBDP_TEXTDOMAIN),
                        'min' => '1',
                        'max' => '15',
                        'step' => '1',
                        'default' => '2',
                        'validation' => 'numeric|minlength[1]',
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'order_category_by',
                        'label' => __('Categories Order By', ATBDP_TEXTDOMAIN),
                        'items' => array(
                            array(
                                'value' => 'id',
                                'label' => __('ID', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'count',
                                'label' => __('Count', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'name',
                                'label' => __('Name', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'slug',
                                'label' => __('Slug', ATBDP_TEXTDOMAIN),
                            ),
                        ),
                        'default' => array(
                            'value' => 'id',
                            'label' => __('ID', ATBDP_TEXTDOMAIN),
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'sort_category_by',
                        'label' => __('Categories Sort By', ATBDP_TEXTDOMAIN),
                        'items' => array(
                            array(
                                'value' => 'asc',
                                'label' => __('Ascending', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'desc',
                                'label' => __('Descending', ATBDP_TEXTDOMAIN),
                            ),
                        ),
                        'default' => array(
                            'value' => 'asc',
                            'label' => __('Ascending', ATBDP_TEXTDOMAIN),
                        ),
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'display_listing_count',
                        'label' => __('Display Listing Count', ATBDP_TEXTDOMAIN),
                        'default' => 1,
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'hide_empty_categories',
                        'label' => __('Hide Empty Categories', ATBDP_TEXTDOMAIN),
                        'default' => 0,
                    ),

                )
            );
        }

        /**
         * Get all the settings fields for the categories page section
         * @return array
         * @since 4.0.0
         */
        function get_locations_settings_fields()
        {
            return apply_filters('atbdp_locations_settings_fields', array(
                    array(
                        'type' => 'select',
                        'name' => 'display_locations_as',
                        'label' => __('Default View', ATBDP_TEXTDOMAIN),
                        'items' => array(
                            array(
                                'value' => 'grid',
                                'label' => __('Grid', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'list',
                                'label' => __('List', ATBDP_TEXTDOMAIN),
                            ),
                        ),
                        'default' => array(
                            'value' => 'grid',
                            'label' => __('Grid', ATBDP_TEXTDOMAIN),
                        ),
                    ),
                    array(
                        'type' => 'slider',
                        'name' => 'locations_column_number',
                        'label' => __('Number of  Columns', ATBDP_TEXTDOMAIN),
                        'description' => __('Set how many columns to display on locations page.', ATBDP_TEXTDOMAIN),
                        'min' => '1',
                        'max' => '5',
                        'step' => '1',
                        'default' => '4',
                        'validation' => 'numeric|minlength[1]',
                    ),
                    array(
                        'type' => 'slider',
                        'name' => 'locations_depth_number',
                        'label' => __('Sub-location Depth', ATBDP_TEXTDOMAIN),
                        'description' => __('Set how many sub-locations to display.', ATBDP_TEXTDOMAIN),
                        'min' => '1',
                        'max' => '15',
                        'step' => '1',
                        'default' => '2',
                        'validation' => 'numeric|minlength[1]',
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'order_location_by',
                        'label' => __('Locations Order By', ATBDP_TEXTDOMAIN),
                        'items' => array(
                            array(
                                'value' => 'id',
                                'label' => __('ID', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'count',
                                'label' => __('Count', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'name',
                                'label' => __('Name', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'slug',
                                'label' => __('Slug', ATBDP_TEXTDOMAIN),
                            ),
                        ),
                        'default' => array(
                            'value' => 'id',
                            'label' => __('ID', ATBDP_TEXTDOMAIN),
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'sort_location_by',
                        'label' => __('Locations Sort By', ATBDP_TEXTDOMAIN),
                        'items' => array(
                            array(
                                'value' => 'asc',
                                'label' => __('Ascending', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'desc',
                                'label' => __('Descending', ATBDP_TEXTDOMAIN),
                            ),
                        ),
                        'default' => array(
                            'value' => 'asc',
                            'label' => __('Ascending', ATBDP_TEXTDOMAIN),
                        ),
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'display_location_listing_count',
                        'label' => __('Display Listing Count', ATBDP_TEXTDOMAIN),
                        'default' => 1,
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'hide_empty_locations',
                        'label' => __('Hide Empty Locations', ATBDP_TEXTDOMAIN),
                        'default' => 0,
                    ),

                )
            );
        }


        /**
         * Get all the settings fields for the search settings section
         * @return array
         * @since 3.0.0
         */
        function get_search_settings_fields()
        {
            $business_hours = '(Requires <a style="color: red" href="https://aazztech.com/product/directorist-business-hours/" target="_blank">Business Hours</a> extension)';
            return apply_filters('atbdp_search_settings_fields', array(
                    array(
                        'type' => 'textbox',
                        'name' => 'search_title',
                        'label' => __('Search Bar Title', ATBDP_TEXTDOMAIN),
                        'description' => __('Enter the title for search bar on Home Page.', ATBDP_TEXTDOMAIN),
                        'default' => atbdp_get_option('search_title', 'atbdp_general'),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'search_subtitle',
                        'label' => __('Search Bar Sub-title', ATBDP_TEXTDOMAIN),
                        'default' => atbdp_get_option('search_subtitle', 'atbdp_general'),
                    ),
                   'search_border_show' => array(
                        'type' => 'toggle',
                        'name' => 'search_border',
                        'label' => __('Search Bar Border', ATBDP_TEXTDOMAIN),
                        'default' => 1,
                    ),
                    array(
                        'type' => 'checkbox',
                        'name' => 'search_tsc_fields',
                        'label' => __('Search Fields', ATBDP_TEXTDOMAIN),
                        'validation' => 'minselected[0]|maxselected[3]',
                        'items' => array(
                            array(
                                'value' => 'search_text',
                                'label' => __('Text', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_category',
                                'label' => __('Category', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_location',
                                'label' => __('Location', ATBDP_TEXTDOMAIN),
                            ),
                        ),
                        'default' => array(
                            'search_text', 'search_category', 'search_location'
                        ),
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'require_search_text',
                        'label' => __('Required Text Field', ATBDP_TEXTDOMAIN),
                        'default' => 0,
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'require_search_category',
                        'label' => __('Required Category Field', ATBDP_TEXTDOMAIN),
                        'default' => 0,
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'require_search_location',
                        'label' => __('Required Location Field', ATBDP_TEXTDOMAIN),
                        'default' => 0,
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'search_placeholder',
                        'label' => __('Search Bar Placeholder', ATBDP_TEXTDOMAIN),
                        'default' => __('What are you looking for?', ATBDP_TEXTDOMAIN),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'search_category_placeholder',
                        'label' => __('Category Placeholder', ATBDP_TEXTDOMAIN),
                        'default' => __('Select a category', ATBDP_TEXTDOMAIN),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'search_location_placeholder',
                        'label' => __('Location Placeholder', ATBDP_TEXTDOMAIN),
                        'default' => __('Select a location', ATBDP_TEXTDOMAIN),
                    ),
                   'display_more_filter' => array(
                        'type' => 'toggle',
                        'name' => 'search_more_filter',
                        'label' => __('Display More Filters', ATBDP_TEXTDOMAIN),
                        'default' => 1,
                    ),
                    'display_search_button' => array(
                        'type' => 'toggle',
                        'name' => 'search_button',
                        'label' => __('Display Search Button', ATBDP_TEXTDOMAIN),
                        'default' => 1,
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'home_display_filter',
                        'label' => __('Open Filter Fields', ATBDP_TEXTDOMAIN),
                        'items' => array(
                            array(
                                'value' => 'overlapping',
                                'label' => __('Overlapping', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'sliding',
                                'label' => __('Sliding', ATBDP_TEXTDOMAIN),
                            ),
                        ),
                        'default' => array(
                            'value' => 'sliding',
                            'label' => __('Sliding', ATBDP_TEXTDOMAIN),
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'name' => 'search_more_filters_fields',
                        'label' => __('Filter Fields', ATBDP_TEXTDOMAIN),
                        'validation' => 'minselected[0]|maxselected[12]',
                        'items' => array(
                            array(
                                'value' => 'search_price',
                                'label' => __('Price (Min - Max)', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_price_range',
                                'label' => __('Price Range', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_rating',
                                'label' => __('Rating', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_tag',
                                'label' => __('Tag', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_open_now',
                                'label' => sprintf(__('Open Now %s', ATBDP_TEXTDOMAIN), !class_exists('BD_Business_Hour')?$business_hours:'')),
                            array(
                                'value' => 'search_custom_fields',
                                'label' => __('Custom Fields', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_website',
                                'label' => __('Website', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_email',
                                'label' => __('Email', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_phone',
                                'label' => __('Phone', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_fax',
                                'label' => __('Fax', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_address',
                                'label' => __('Address', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_zip_code',
                                'label' => __('Zip/Post Code', ATBDP_TEXTDOMAIN),
                            ),
                        ),
                        'default' => array(
                            'search_price', 'search_price_range', 'search_rating', 'search_tag', 'search_custom_fields'
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'name' => 'search_filters',
                        'label' => __('Filters Button', ATBDP_TEXTDOMAIN),
                        'validation' => 'minselected[0]|maxselected[2]',
                        'items' => array(
                            array(
                                'value' => 'search_reset_filters',
                                'label' => __('Reset', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_apply_filters',
                                'label' => __('Apply', ATBDP_TEXTDOMAIN),
                            ),
                        ),
                        'default' => array(
                            'search_reset_filters', 'search_apply_filters'
                        ),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'search_listing_text',
                        'label' => __('Search Button Text', ATBDP_TEXTDOMAIN),
                        'default' => __('Search Listing', ATBDP_TEXTDOMAIN)
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'search_more_filters',
                        'label' => __('More Filters Button Text', ATBDP_TEXTDOMAIN),
                        'default' => __('More Filters', ATBDP_TEXTDOMAIN)
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'search_reset_text',
                        'label' => __('Reset Filters Button Text', ATBDP_TEXTDOMAIN),
                        'default' => __('Reset Filters', ATBDP_TEXTDOMAIN)
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'search_apply_filter',
                        'label' => __('Apply Filters Button Text', ATBDP_TEXTDOMAIN),
                        'default' => __('Apply Filters', ATBDP_TEXTDOMAIN)
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'show_popular_category',
                        'label' => __('Display Popular Categories', ATBDP_TEXTDOMAIN),
                        'default' => '0',
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'show_connector',
                        'label' => __('Display Connector', ATBDP_TEXTDOMAIN),
                        'description' => __('Whether to display a connector between Search Bar and Popular Categories.', ATBDP_TEXTDOMAIN),
                        'default' => '0',
                    ),

                    array(
                        'type' => 'textbox',
                        'name' => 'connectors_title',
                        'label' => __('Connector Text', ATBDP_TEXTDOMAIN),
                        'default' => __('Or', ATBDP_TEXTDOMAIN),
                    ),

                    array(
                        'type' => 'textbox',
                        'name' => 'popular_cat_title',
                        'label' => __('Popular Categories Title', ATBDP_TEXTDOMAIN),
                        'default' => __('Browse by popular categories', ATBDP_TEXTDOMAIN),
                    ),

                    array(
                        'type' => 'slider',
                        'name' => 'popular_cat_num',
                        'label' => __('Number of Popular Categories', ATBDP_TEXTDOMAIN),
                        'min' => '1',
                        'max' => '30',
                        'step' => '1',
                        'default' => '10',
                        'validation' => 'numeric|minlength[1]',
                    ),
                   'search_home_background' => array(
                        'type' => 'upload',
                        'name' => 'search_home_bg',
                        'label' => __('Search Page Background', ATBDP_TEXTDOMAIN),
                    ),

                )
            );
        }

        /**
         * Get all the settings fields for the listings search result section
         * @return array
         * @since 4.0.0
         */
        function get_search_form_settings_fields()
        {
            $business_hours = '(Requires <a style="color: red" href="https://aazztech.com/product/directorist-business-hours/" target="_blank">Business Hours</a> extension)';
            return apply_filters('atbdp_search_result_settings_fields', array(
                    array(
                        'type' => 'toggle',
                        'name' => 'search_header',
                        'label' => __('Display Header', ATBDP_TEXTDOMAIN),
                        'default' => 1,
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'search_header_title',
                        'label' => __('Header Title', ATBDP_TEXTDOMAIN),
                        'default' => __('Search Result ', ATBDP_TEXTDOMAIN),
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'search_result_filters_button_display',
                        'label' => __('Display Filters Button', ATBDP_TEXTDOMAIN),
                        'default' => 1,
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'search_result_filter_button_text',
                        'label' => __('Filters Button Text', ATBDP_TEXTDOMAIN),
                        'default' => __('Filters', ATBDP_TEXTDOMAIN),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'search_result_display_filter',
                        'label' => __('Open Filter Fields', ATBDP_TEXTDOMAIN),
                        'items' => array(
                            array(
                                'value' => 'overlapping',
                                'label' => __('Overlapping', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'sliding',
                                'label' => __('Sliding', ATBDP_TEXTDOMAIN),
                            ),
                        ),
                        'default' => array(
                            'value' => 'sliding',
                            'label' => __('Sliding', ATBDP_TEXTDOMAIN),
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'name' => 'search_result_filters_fields',
                        'label' => __('Filter Fields', ATBDP_TEXTDOMAIN),
                        'validation' => 'minselected[0]|maxselected[15]',
                        'items' => array(
                            array(
                                'value' => 'search_text',
                                'label' => __('Text', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_category',
                                'label' => __('Category', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_location',
                                'label' => __('Location', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_price',
                                'label' => __('Price (Min - Max)', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_price_range',
                                'label' => __('Price Range', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_rating',
                                'label' => __('Rating', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_tag',
                                'label' => __('Tag', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_open_now',
                                'label' => sprintf(__('Open Now %s', ATBDP_TEXTDOMAIN), !class_exists('BD_Business_Hour')?$business_hours:'')),
                            array(
                                'value' => 'search_custom_fields',
                                'label' => __('Custom Fields', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_website',
                                'label' => __('Website', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_email',
                                'label' => __('Email', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_phone',
                                'label' => __('Phone', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_address',
                                'label' => __('Address', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'search_zip_code',
                                'label' => __('Zip/Post Code', ATBDP_TEXTDOMAIN),
                            ),
                        ),
                        'default' => array(
                            'search_text',
                            'search_category',
                            'search_location',
                            'search_price',
                            'search_price_range',
                            'search_rating',
                            'search_tag',
                            'search_custom_fields'
                        ),
                    ),
                    array(
                        'type' => 'checkbox',
                        'name' => 'search_result_filters_button',
                        'label' => __('Filters Button', ATBDP_TEXTDOMAIN),
                        'validation' => 'minselected[0]|maxselected[5]',
                        'items' => array(
                            array(
                                'value' => 'reset_button',
                                'label' => __('Reset', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'apply_button',
                                'label' => __('Apply', ATBDP_TEXTDOMAIN),
                            ),
                        ),
                        'default' => array(
                            'reset_button',
                            'apply_button',
                        ),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'sresult_reset_text',
                        'label' => __('Reset Filters Button text', ATBDP_TEXTDOMAIN),
                        'default' => __('Reset Filters', ATBDP_TEXTDOMAIN),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'sresult_apply_text',
                        'label' => __('Apply Filters Button text', ATBDP_TEXTDOMAIN),
                        'default' => __('Apply Filters', ATBDP_TEXTDOMAIN),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'search_result_search_text_placeholder',
                        'label' => __('Search Bar Placeholder', ATBDP_TEXTDOMAIN),
                        'default' => __('What are you looking for?', ATBDP_TEXTDOMAIN),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'search_result_category_placeholder',
                        'label' => __('Category Placeholder', ATBDP_TEXTDOMAIN),
                        'default' => __('Select a category', ATBDP_TEXTDOMAIN),
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'search_result_location_placeholder',
                        'label' => __('Location Placeholder', ATBDP_TEXTDOMAIN),
                        'default' => __('Select a location', ATBDP_TEXTDOMAIN),
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'search_view_as',
                        'label' => __('Display "View As" Dropdown', ATBDP_TEXTDOMAIN),
                        'default' => 1,
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'search_viewas_text',
                        'label' => __('"View As" Text', ATBDP_TEXTDOMAIN),
                        'default' => __('View As', ATBDP_TEXTDOMAIN),
                    ),
                    array(
                        'type' => 'checkbox',
                        'name' => 'search_view_as_items',
                        'label' => __('"View As" Dropdown', ATBDP_TEXTDOMAIN),
                        'validation' => 'minselected[0]|maxselected[3]',
                        'items' => array(
                            array(
                                'value' => 'listings_grid',
                                'label' => __('Grid', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'listings_list',
                                'label' => __('List', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'listings_map',
                                'label' => __('Map', ATBDP_TEXTDOMAIN),
                            ),
                        ),
                        'default' => array(
                            'listings_grid',
                            'listings_list',
                            'listings_map'
                        ),
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'search_sort_by',
                        'label' => __('Display "Sort By" Dropdown', ATBDP_TEXTDOMAIN),
                        'default' => 1,
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'search_sortby_text',
                        'label' => __('"Sort By" Text', ATBDP_TEXTDOMAIN),
                        'default' => __('Sort By', ATBDP_TEXTDOMAIN),
                    ),
                    array(
                        'type' => 'checkbox',
                        'name' => 'search_sort_by_items',
                        'label' => __('"Sort By" Dropdown', ATBDP_TEXTDOMAIN),
                        'validation' => 'minselected[0]|maxselected[10]',
                        'items' => array(
                            array(
                                'value' => 'a_z',
                                'label' => __('A to Z (title)', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'z_a',
                                'label' => __('Z to A (title)', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'latest',
                                'label' => __('Latest listings', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'oldest',
                                'label' => __('Oldest listings', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'popular',
                                'label' => __('Popular listings', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'price_low_high',
                                'label' => __('Price (low to high)', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'price_high_low',
                                'label' => __('Price (high to low)', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'random',
                                'label' => __('Random listings', ATBDP_TEXTDOMAIN),
                            ),
                        ),
                        'default' => array(
                            'a_z',
                            'z_a',
                            'latest',
                            'oldest',
                            'popular',
                            'price_low_high',
                            'price_high_low',
                            'random'
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'search_order_listing_by',
                        'label' => __('Order By', ATBDP_TEXTDOMAIN),
                        'items' => array(
                            array(
                                'value' => 'title',
                                'label' => __('Title', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'date',
                                'label' => __('Date', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'price',
                                'label' => __('Price', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'rand',
                                'label' => __('Random', ATBDP_TEXTDOMAIN),
                            ),
                        ),
                        'default' => array(
                            'value' => 'date',
                            'label' => __('Date', ATBDP_TEXTDOMAIN),
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'search_sort_listing_by',
                        'label' => __('Sort By', ATBDP_TEXTDOMAIN),
                        'items' => array(
                            array(
                                'value' => 'asc',
                                'label' => __('Ascending', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'desc',
                                'label' => __('Descending', ATBDP_TEXTDOMAIN),
                            ),
                        ),
                        'default' => array(
                            'value' => 'desc',
                            'label' => __('Descending', ATBDP_TEXTDOMAIN),
                        ),
                    ),
                    array(
                        'type' => 'slider',
                        'name' => 'search_listing_columns',
                        'label' => __('Number of Columns', ATBDP_TEXTDOMAIN),
                        'min' => '1',
                        'max' => '5',
                        'step' => '1',
                        'default' => '3',
                        'validation' => 'numeric|minlength[1]',
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'paginate_search_results',
                        'label' => __('Paginate Search Result', ATBDP_TEXTDOMAIN),
                        'default' => 1,
                    ),
                    array(
                        'type' => 'slider',
                        'name' => 'search_posts_num',
                        'label' => __('Search Results Per Page', ATBDP_TEXTDOMAIN),
                        'min' => '1',
                        'max' => '100',
                        'step' => '1',
                        'default' => atbdp_get_option('search_posts_num', 'atbdp_general', 6),
                        'validation' => 'numeric|minlength[1]',
                    ),
                )
            );
        }

        /**
         * Get all the settings fields for the listings settings section
         * @return array
         * @since 4.0.0
         */
        function get_badge_settings_fields()
        {
            return apply_filters('atbdp_badge_settings_fields', array(
                array(
                    'type' => 'toggle',
                    'name' => 'display_new_badge_cart',
                    'label' => __('Display New Badge', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'new_badge_text',
                    'label' => __('New Badge Text', ATBDP_TEXTDOMAIN),
                    'default' => __('New', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'slider',
                    'name' => 'new_listing_day',
                    'label' => __('New Badge Duration in Days', ATBDP_TEXTDOMAIN),
                    'min' => '1',
                    'max' => '100',
                    'step' => '1',
                    'default' => '3',
                    'validation' => 'numeric|minlength[1]',
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_feature_badge_cart',
                    'label' => __('Display Featured Badge', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'feature_badge_text',
                    'label' => __('Featured Badge Text', ATBDP_TEXTDOMAIN),
                    'default' => __('Featured', ATBDP_TEXTDOMAIN),
                ),
            ));
        }

        /**
         * Get all the settings fields for the listings settings section
         * @return array
         * @since 4.0.0
         */
        function get_popular_badge_settings_fields()
        {
            return apply_filters('atbdp_badge_settings_fields', array(

                array(
                    'type' => 'toggle',
                    'name' => 'display_popular_badge_cart',
                    'label' => __('Display Popular Badge', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'popular_badge_text',
                    'label' => __('Popular Badge Text', ATBDP_TEXTDOMAIN),
                    'default' => __('Popular', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'select',
                    'name' => 'listing_popular_by',
                    'label' => __('Popular Based on', ATBDP_TEXTDOMAIN),
                    'items' => array(
                        array(
                            'value' => 'view_count',
                            'label' => __('View Count', ATBDP_TEXTDOMAIN),
                        ),
                        array(
                            'value' => 'average_rating',
                            'label' => __('Average Rating', ATBDP_TEXTDOMAIN),
                        ),
                        array(
                            'value' => 'both_view_rating',
                            'label' => __('Both', ATBDP_TEXTDOMAIN),
                        ),
                    ),
                    'default' => array(
                        'value' => 'view_count',
                        'label' => __('View Count', ATBDP_TEXTDOMAIN),
                    ),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'views_for_popular',
                    'label' => __('Threshold in Views Count', ATBDP_TEXTDOMAIN),
                    'default' => 5,
                ),
                array(
                    'type' => 'slider',
                    'name' => 'average_review_for_popular',
                    'label' => __('Threshold in Average Ratings (equal or grater than)', ATBDP_TEXTDOMAIN),
                    'min' => '.5',
                    'max' => '4.5',
                    'step' => '.5',
                    'default' => '4',
                    'validation' => 'numeric|minlength[1]',
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'count_loggedin_user',
                    'label' => __('Count Logged-in User View', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),

            ));
        }


        /**
         * Get title settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_title_field_settings()
        {
            $req_title = atbdp_get_option('title_field_setting', 'atbdp_general', 'yes');
            return apply_filters('atbdp_title_field_setting', array(
                array(
                    'type' => 'textbox',
                    'name' => 'title_label',
                    'label' => __('Label', ATBDP_TEXTDOMAIN),
                    'default' => __('Title', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_title',
                    'label' => __('Required', ATBDP_TEXTDOMAIN),
                    'default' => atbdp_yes_to_bool($req_title),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_title_for',
                    'label' => __('Only For Admin Use', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
            ));
        }

        /**
         * Get all the settings fields for description section
         * @return array
         * @since 4.7.2
         */
        public function get_listings_desc_field_settings()
        {
            return apply_filters('atbdp_desc_field_setting', array(
                array(
                    'type' => 'textbox',
                    'name' => 'long_details_label',
                    'label' => __('Label', ATBDP_TEXTDOMAIN),
                    'default' => __('Long Details', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_long_details',
                    'label' => __('Required', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_desc_for',
                    'label' => __('Only For Admin Use', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
            ));
        }

        /**
         * Get category settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_cat_field_settings()
        {
            return apply_filters('atbdp_cat_field_setting', array(
                array(
                    'type' => 'textbox',
                    'name' => 'category_label',
                    'label' => __('Label', ATBDP_TEXTDOMAIN),
                    'default' => __('Select Category', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_category',
                    'label' => __('Required', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'cat_placeholder',
                    'label' => __('Placeholder for User', ATBDP_TEXTDOMAIN),
                    'default' => __('Select Category', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'multiple_cat_for_user',
                    'label' => __('Multi Category for User', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                /*array(
                    'type' => 'select',
                    'name' => 'display_cat_for',
                    'label' => __( 'Display For', ATBDP_TEXTDOMAIN ),
                    'items' => array(
                        array(
                            'value' => 'users',
                            'label' => __('Users', ATBDP_TEXTDOMAIN),
                        ),
                        array(
                            'value' => 'none',
                            'label' => __('None', ATBDP_TEXTDOMAIN),
                        ),
                    ),
                    'default' => array(
                        'value' => 'users',
                        'label' => __('Users', ATBDP_TEXTDOMAIN),
                    ),
                ),*/
            ));
        }

        /**
         * Get location settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_loc_field_settings()
        {
            return apply_filters('atbdp_loc_field_setting', array(
                array(
                    'type' => 'textbox',
                    'name' => 'location_label',
                    'label' => __('Label', ATBDP_TEXTDOMAIN),
                    'default' => __('Location', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_location',
                    'label' => __('Required', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'loc_placeholder',
                    'label' => __('Placeholder for User', ATBDP_TEXTDOMAIN),
                    'default' => __('Select Location', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_loc_for',
                    'label' => __('Only For Admin Use', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'multiple_loc_for_user',
                    'label' => __('Multi Location for User', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
            ));
        }

        /**
         * Get tag settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_tag_field_settings()
        {
            return apply_filters('atbdp_tag_field_setting', array(
                array(
                    'type' => 'textbox',
                    'name' => 'tag_label',
                    'label' => __('Label', ATBDP_TEXTDOMAIN),
                    'default' => __('Tag', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'tag_placeholder',
                    'label' => __('Placeholder for User', ATBDP_TEXTDOMAIN),
                    'default' => __('Select or insert new tags separated by a comma, or space', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'create_new_tag',
                    'label' => __('Allow Creating New Tag', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_tags',
                    'label' => __('Required', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_tag_for',
                    'label' => __('Only For Admin Use', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
            ));
        }

        /**
         * Get tagline settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_tagline_field_settings()
        {
            return apply_filters('atbdp_tagline_field_setting', array(

                array(
                    'type' => 'toggle',
                    'name' => 'display_tagline_field',
                    'label' => __('Enable', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'tagline_label',
                    'label' => __('Label', ATBDP_TEXTDOMAIN),
                    'default' => __('Tagline', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'tagline_placeholder',
                    'label' => __('Placeholder', ATBDP_TEXTDOMAIN),
                    'default' => __('Your Listing\'s motto or tag-line', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_tagline_for',
                    'label' => __('Only For Admin Use', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
            ));
        }

        /**
         * Get pricing settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_pricing_field_settings()
        {
            return apply_filters('atbdp_pricing_field_setting', array(
                array(
                    'type' => 'toggle',
                    'name' => 'display_pricing_field',
                    'label' => __('Enable', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'price_label',
                    'label' => __('Label', ATBDP_TEXTDOMAIN),
                    'default' => __('Price', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'price_placeholder',
                    'label' => __('Placeholder', ATBDP_TEXTDOMAIN),
                    'default' => __('Price of this listing. Eg. 100', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_price',
                    'label' => __('Required Price', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_price_range',
                    'label' => __('Required Price Range', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_price_for',
                    'label' => __('Only For Admin Use', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
            ));
        }

        public function get_listings_views_count_settings(){
            return apply_filters('atbdp_views_count_field_setting', array(
                array(
                    'type' => 'toggle',
                    'name' => 'display_views_count',
                    'label' => __('Enable', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'views_count_label',
                    'label' => __('Label', ATBDP_TEXTDOMAIN),
                    'default' => __('Views Count', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_views_count_for',
                    'label' => __('Only For Admin Use', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
            ));
        }
        /**
         * Get excerpt settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_short_desc_field_settings()
        {
            return apply_filters('atbdp_short_desc_field_setting', array(
                array(
                    'type'    => 'toggle',
                    'name'    => 'display_excerpt_field',
                    'label'   => __('Enable', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
                array(
                    'type'    => 'textbox',
                    'name'    => 'excerpt_label',
                    'label'   => __('Label', ATBDP_TEXTDOMAIN),
                    'default' => __('Short Description/Excerpt', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'excerpt_placeholder',
                    'label' => __('Placeholder', ATBDP_TEXTDOMAIN),
                    'default' => __('Short Description or Excerpt', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type'    => 'toggle',
                    'name'    => 'require_excerpt',
                    'label'   => __('Required', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
                array(
                    'type'    => 'toggle',
                    'name'    => 'display_short_desc_for',
                    'label'   => __('Only For Admin Use', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
            ));
        }

        /**
         * Get address settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_address_field_settings()
        {
            return apply_filters('atbdp_address_field_setting', array(
                array(
                    'type' => 'toggle',
                    'name' => 'display_address_field',
                    'label' => __('Enable', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'address_label',
                    'label' => __('Label', ATBDP_TEXTDOMAIN),
                    'default' => __('Address', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'address_placeholder',
                    'label' => __('Placeholder', ATBDP_TEXTDOMAIN),
                    'default' => __('Listing address eg. New York, USA', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_address',
                    'label' => __('Required', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_address_for',
                    'label' => __('Only For Admin Use', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
            ));
        }

        /**
         * Get phone number settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_phone_field_settings()
        {
            return apply_filters('atbdp_phone_field_setting', array(

                array(
                    'type' => 'toggle',
                    'name' => 'display_phone_field',
                    'label' => __('Enable', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'phone_label',
                    'label' => __('Label', ATBDP_TEXTDOMAIN),
                    'default' => __('Phone', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'phone_placeholder',
                    'label' => __('Placeholder', ATBDP_TEXTDOMAIN),
                    'default' => __('Phone Number', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_phone_number',
                    'label' => __('Required', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_phone_for',
                    'label' => __('Only For Admin Use', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),

            ));
        }
        /**
         * Get phone number settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_phone_field2_settings()
        {
            return apply_filters('atbdp_phone_field2_setting', array(

                array(
                    'type' => 'toggle',
                    'name' => 'display_phone_field2',
                    'label' => __('Enable', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'phone_label2',
                    'label' => __('Label', ATBDP_TEXTDOMAIN),
                    'default' => __('Phone 2', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'phone_placeholder2',
                    'label' => __('Placeholder', ATBDP_TEXTDOMAIN),
                    'default' => __('Phone Number 2', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_phone_number2',
                    'label' => __('Required', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_phone2_for',
                    'label' => __('Only For Admin Use', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),

            ));
        }

        /**
         * Get phone number settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_fax_settings()
        {
            return apply_filters('atbdp_fax_setting', array(

                array(
                    'type' => 'toggle',
                    'name' => 'display_fax',
                    'label' => __('Enable', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'fax_label',
                    'label' => __('Label', ATBDP_TEXTDOMAIN),
                    'default' => __('Fax', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'fax_placeholder',
                    'label' => __('Placeholder', ATBDP_TEXTDOMAIN),
                    'default' => __('Enter Fax', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_fax',
                    'label' => __('Required', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_fax_for',
                    'label' => __('Only For Admin Use', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),

            ));
        }

        /**
         * Get email settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_email_field_settings()
        {
            return apply_filters('atbdp_email_field_setting', array(

                array(
                    'type' => 'toggle',
                    'name' => 'display_email_field',
                    'label' => __('Enable', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'email_label',
                    'label' => __('Label', ATBDP_TEXTDOMAIN),
                    'default' => __('Email', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'email_placeholder',
                    'label' => __('Placeholder', ATBDP_TEXTDOMAIN),
                    'default' => __('Enter Email', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_email',
                    'label' => __('Required', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_email_for',
                    'label' => __('Only For Admin Use', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),

            ));
        }

        /**
         * Get website settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_website_field_settings()
        {
            return apply_filters('atbdp_website_field_setting', array(

                array(
                    'type' => 'toggle',
                    'name' => 'display_website_field',
                    'label' => __('Enable', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'website_label',
                    'label' => __('Label', ATBDP_TEXTDOMAIN),
                    'default' => __('Website', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'website_placeholder',
                    'label' => __('Placeholder', ATBDP_TEXTDOMAIN),
                    'default' => __('Listing Website eg. http://example.com', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_website',
                    'label' => __('Required', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_website_for',
                    'label' => __('Only For Admin Use', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
            ));
        }

        /**
         * Get website settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_zip_field_settings()
        {
            return apply_filters('atbdp_zip_field_setting', array(

                array(
                    'type' => 'toggle',
                    'name' => 'display_zip_field',
                    'label' => __('Enable', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'zip_label',
                    'label' => __('Label', ATBDP_TEXTDOMAIN),
                    'default' => __('Zip/Post Code', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'zip_placeholder',
                    'label' => __('Placeholder', ATBDP_TEXTDOMAIN),
                    'default' => __('Enter Zip/Post Code', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_zip',
                    'label' => __('Required', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_zip_for',
                    'label' => __('Only For Admin Use', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
            ));
        }

        /**
         * Get social info settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_social_field_settings()
        {
            return apply_filters('atbdp_social_field_setting', array(

                array(
                    'type' => 'toggle',
                    'name' => 'display_social_info_field',
                    'label' => __('Enable', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'social_label',
                    'label' => __('Label', ATBDP_TEXTDOMAIN),
                    'default' => __('Social Information', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_social_info',
                    'label' => __('Required', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_social_info_for',
                    'label' => __('Only For Admin Use', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
            ));
        }

        /**
         * Get map settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_map_field_settings()
        {
            return apply_filters('atbdp_map_field_setting', array(
                array(
                    'type' => 'toggle',
                    'name' => 'display_map_field',
                    'label' => __('Enable', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),

                array(
                    'type' => 'toggle',
                    'name' => 'display_map_for',
                    'label' => __('Only For Admin Use', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),

            ));
        }

        /**
         * Get  image settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_image_field_settings()
        {
            return apply_filters('atbdp_image_field_setting', array(

                array(
                    'type' => 'toggle',
                    'name' => 'display_prv_field',
                    'label' => __('Enable Preview Image', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'preview_label',
                    'label' => __('Preview Image Label', ATBDP_TEXTDOMAIN),
                    'default' => __('Upload Preview Image', ATBDP_TEXTDOMAIN),
                ),

                array(
                    'type' => 'toggle',
                    'name' => 'display_gellery_field',
                    'label' => __('Enable Gallery Image', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'gellery_label',
                    'label' => __('Gallery Image Label', ATBDP_TEXTDOMAIN),
                    'default' => __('Upload Slider Images', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_preview_img',
                    'label' => __('Required Preview Image', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_gallery_img',
                    'label' => __('Required Gallery', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_prv_img_for',
                    'label' => __('Preview Image Only For Admin Use', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_glr_img_for',
                    'label' => __('Gallery Image Only For Admin Use', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),


            ));
        }

        /**
         * Get  video settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_video_field_settings()
        {
            return apply_filters('atbdp_video_field_setting', array(

                array(
                    'type' => 'toggle',
                    'name' => 'display_video_field',
                    'label' => __('Enable', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'video_label',
                    'label' => __('Label', ATBDP_TEXTDOMAIN),
                    'default' => __('Video Url', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'video_placeholder',
                    'label' => __('Placeholder', ATBDP_TEXTDOMAIN),
                    'default' => __('Only YouTube & Vimeo URLs.', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_video',
                    'label' => __('Required', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_video_for',
                    'label' => __('Only For Admin Use', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),

            ));
        }

        /**
         * Get  term & condition settings field
         * @return array
         * @since 4.7.2
         */
        public function get_listings_terms_field_settings()
        {
            return apply_filters('atbdp_video_field_setting', array(
                array(
                    'type' => 'toggle',
                    'name' => 'listing_terms_condition',
                    'label' => __('Enable Terms & Conditions', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'require_terms_conditions',
                    'label' => __('Required', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                    'description' => __('Here YES means users must agree to before submitting a listing from frontend.

', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'wpeditor',
                    'name' => 'listing_terms_condition_text',
                    'label' => __('Terms & Conditions Text', ATBDP_TEXTDOMAIN),
                    'description' => __('If Terms & Conditions is enabled, enter the agreement terms and conditions here.', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'submit_label',
                    'label' => __('Submit listing label', ATBDP_TEXTDOMAIN),
                    'default' => __('Submit listing', ATBDP_TEXTDOMAIN),
                ),


            ));
        }

        function get_listings_dashboard_settings_fields()
        {
            return apply_filters('atbdp_dashboard_field_setting', array(
                array(
                    'type' => 'toggle',
                    'name' => 'my_listing_tab',
                    'label' => __('Display My Listing Tab', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'my_listing_tab_text',
                    'label' => __('"My Listing" Tab Text', ATBDP_TEXTDOMAIN),
                    'default' => __('My Listing', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'user_listings_pagination',
                    'label' => __('Listings Pagination', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'slider',
                    'name' => 'user_listings_per_page',
                    'label' => __('Listings Per Page', ATBDP_TEXTDOMAIN),
                    'min' => '1',
                    'max' => '30',
                    'step' => '1',
                    'default' => '9',

                ),
                array(
                    'type' => 'toggle',
                    'name' => 'my_profile_tab',
                    'label' => __('Display My Profile Tab', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'my_profile_tab_text',
                    'label' => __('"My Profile" Tab Text', ATBDP_TEXTDOMAIN),
                    'default' => __('My Profile', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'fav_listings_tab',
                    'label' => __('Display Favourite Listings Tab', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'fav_listings_tab_text',
                    'label' => __('"Favourite Listings" Tab Text', ATBDP_TEXTDOMAIN),
                    'default' => __('Favorite Listings', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'submit_listing_button',
                    'label' => __('Display Submit Listing Button', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
            ));
        }

        function get_listings_map_settings_fields()
        {
            return apply_filters('atbdp_map_field_setting', array(
                array(
                    'type' => 'select',
                    'name' => 'select_listing_map',
                    'label' => __('Select Map', ATBDP_TEXTDOMAIN),
                    'items' => array(
                        array(
                            'value' => 'google',
                            'label' => __('Google Map', ATBDP_TEXTDOMAIN),
                        ),
                        array(
                            'value' => 'openstreet',
                            'label' => __('OpenStreetMap', ATBDP_TEXTDOMAIN),
                        ),
                    ),
                    'default' => array(
                        'value' => 'openstreet',
                        'label' => __('OpenStreetMap', ATBDP_TEXTDOMAIN),
                    ),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'map_api_key',
                    'label' => __('Google Map API key', ATBDP_TEXTDOMAIN),
                    'description' => sprintf(__('Please replace it by your own API. It\'s required to use Google Map. You can find detailed information %s.', ATBDP_TEXTDOMAIN), '<a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank"> <strong style="color: red;">here</strong> </a>'),
                    'default' => 'AIzaSyCwxELCisw4mYqSv_cBfgOahfrPFjjQLLo',
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'default_latitude',
                    'label' => __('Default Latitude', ATBDP_TEXTDOMAIN),
                    'description' => sprintf(__('You can find it %s.', ATBDP_TEXTDOMAIN), '<a href="https://www.maps.ie/coordinates.html" target="_blank"> <strong style="color: red;">here</strong> </a>'),
                    'default' => '40.7127753',
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'default_longitude',
                    'label' => __('Default Longitude', ATBDP_TEXTDOMAIN),
                    'description' => sprintf(__('You can find it %s.', ATBDP_TEXTDOMAIN), '<a href="https://www.maps.ie/coordinates.html" target="_blank"> <strong style="color: red;">here</strong> </a>'),
                    'default' => '-74.0059728',
                ),
                array(
                    'type' => 'slider',
                    'name' => 'map_zoom_level',
                    'label' => __('Adjust Map Zoom Level', ATBDP_TEXTDOMAIN),
                    'description' => __('Here 0 means 100% zoom-out. 22 means 100% zoom-in. Minimum Zoom Allowed = 1. Max Zoom Allowed = 22.', ATBDP_TEXTDOMAIN),
                    'min' => '1',
                    'max' => '22',
                    'step' => '1',
                    'default' => '4',

                ),
                array(
                    'type' => 'slider',
                    'name' => 'listings_map_height',
                    'label' => __('Map Height', ATBDP_TEXTDOMAIN),
                    'description' => __('In pixel.', ATBDP_TEXTDOMAIN),
                    'min' => '5',
                    'max' => '1200',
                    'step' => '5',
                    'default' => '350',

                ),
            ));
        }

        /**
         * Get all the settings fields for the listings settings section
         * @return array
         * @since 4.0.0
         */
        function get_listings_review_settings_fields()
        {
            $e_review = atbdp_get_option('enable_review', 'atbdp_general', 'yes');
            return apply_filters('atbdp_review_settings_fields', array(
                array(
                    'type' => 'toggle',
                    'name' => 'enable_review',
                    'label' => __('Enable Reviews & Rating', ATBDP_TEXTDOMAIN),
                    'default' => atbdp_yes_to_bool($e_review),
                ),

                array(
                    'type' => 'toggle',
                    'name' => 'enable_owner_review',
                    'label' => __('Enable Owner Review', ATBDP_TEXTDOMAIN),
                    'description' => __('Allow a listing owner to post a review on his/her own listing.', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),

                array(
                    'type' => 'toggle',
                    'name' => 'enable_reviewer_img',
                    'label' => __('Enable Reviewer Image', ATBDP_TEXTDOMAIN),
                    'description' => __('Allow to display image of reviewer on single listing page.', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),

                array(
                    'type' => 'slider',
                    'name' => 'review_num',
                    'label' => __('Number of Reviews', ATBDP_TEXTDOMAIN),
                    'description' => __('Enter how many reviews to show on Single listing page.', ATBDP_TEXTDOMAIN),
                    'min' => '1',
                    'max' => '20',
                    'step' => '1',
                    'default' => '5',
                    'validation' => 'numeric|minlength[1]',
                ),
            ));
        }

        /**
         * Get all the settings fields for the listings settings section
         * @return array
         * @since 3.0.0
         */
        function get_listings_form_settings_fields()
        {
            return apply_filters('atbdp_single_listings_settings_fields', array(
                array(
                    'type' => 'toggle',
                    'name' => 'disable_single_listing',
                    'label' => __('Disable Single Listing View', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'atbdp_listing_slug',
                    'label' => __('Listing Slug', ATBDP_TEXTDOMAIN),
                    'default' => 'directory',
                ),
                array(
                    'type' => 'select',
                    'name' => 'edit_listing_redirect',
                    'label' => __('Redirect after Editing a Listing', ATBDP_TEXTDOMAIN),
                    'items' => array(
                        array(
                            'value' => 'view_listing',
                            'label' => __('Frontend of the Listing', ATBDP_TEXTDOMAIN),
                        ),
                        array(
                            'value' => 'dashboard',
                            'label' => __('User Dashboard', ATBDP_TEXTDOMAIN),
                        ),
                    ),
                    'description' => __('Select where user will be redirected after editing a listing on the frontend.', ATBDP_TEXTDOMAIN),

                    'default' => array(
                        'value' => 'view_listing',
                        'label' => __('View Listing', ATBDP_TEXTDOMAIN),
                    ),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'listing_details_text',
                    'label' => __('Section Title of Listing Details', ATBDP_TEXTDOMAIN),
                    'default' => __('Listing Details', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'custom_section_lable',
                    'label' => __('Section Title of Custom Fields', ATBDP_TEXTDOMAIN),
                    'default' => __('Features', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'listing_location_text',
                    'label' => __('Section Title of Location', ATBDP_TEXTDOMAIN),
                    'default' => __('Location', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'contact_info_text',
                    'label' => __('Section Title of Contact Info', ATBDP_TEXTDOMAIN),
                    'default' => __('Contact Information', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'contact_listing_owner',
                    'label' => __('Section Title of Contact Owner', ATBDP_TEXTDOMAIN),
                    'default' => __('Contact Listing Owner', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'atbd_video_title',
                    'label' => __('Section Title of Video', ATBDP_TEXTDOMAIN),
                    'default' => __('Video', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'textbox',
                    'name' => 'atbd_author_info_title',
                    'label' => __('Section Title of Author Info', ATBDP_TEXTDOMAIN),
                    'default' => __('Author Info', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'dsiplay_prv_single_page',
                    'label' => __('Show Preview Image', ATBDP_TEXTDOMAIN),
                    'description' => __('Hide/show preview image from single listing page.', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'display_back_link',
                    'label' => __('Show Back Link', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'dsiplay_slider_single_page',
                    'label' => __('Show Slider Image', ATBDP_TEXTDOMAIN),
                    'description' => __('Hide/show slider image from single listing page.', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'dsiplay_thumbnail_img',
                    'label' => __('Show Slider Thumbnail', ATBDP_TEXTDOMAIN),
                    'description' => __('Hide/show slider thumbnail from single listing page.', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'gallery_cropping',
                    'label' => __('Slider Image Cropping', ATBDP_TEXTDOMAIN),
                    'description' => __('If the slider images are not in the same size, it helps automatically resizing.', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'slider',
                    'name' => 'gallery_crop_width',
                    'label' => __('Image Cropping Width', ATBDP_TEXTDOMAIN),
                    'min' => '1',
                    'max' => '1200',
                    'step' => '1',
                    'default' => '740',

                ),

                array(
                    'type' => 'slider',
                    'name' => 'gallery_crop_height',
                    'label' => __('Image Cropping Height', ATBDP_TEXTDOMAIN),
                    'min' => '1',
                    'max' => '1200',
                    'step' => '1',
                    'default' => '580',

                ),
                array(
                    'type' => 'toggle',
                    'name' => 'enable_social_share',
                    'label' => __('Display Social Share Button', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'enable_favourite',
                    'label' => __('Display Favourite Button', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'enable_report_abuse',
                    'label' => __('Display Report Abuse', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'disable_list_price',
                    'label' => __('Disable Listing Price', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'disable_contact_info',
                    'label' => __('Disable Contact Information', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'disable_contact_owner',
                    'label' => __('Disable Contact Listing Owner Form', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'select',
                    'name' => 'user_email',
                    'label' => __('Email Send to', ATBDP_TEXTDOMAIN),
                    'description' => __('Email recipient for receiving email from Contact Listing Owner Form.', ATBDP_TEXTDOMAIN),
                    'items' => array(
                        array(
                            'value' => 'author',
                            'label' => __('Author Email', ATBDP_TEXTDOMAIN),
                        ),
                        array(
                            'value' => 'listing_email',
                            'label' => __('Listing\'s Email', ATBDP_TEXTDOMAIN),
                        ),
                    ),
                    'default' => array(
                        'value' => 'author',
                        'label' => __('Author Email', ATBDP_TEXTDOMAIN),
                    ),
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'use_nofollow',
                    'label' => __('Use rel="nofollow" in Website Link', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'disable_map',
                    'label' => __('Disable Google Map', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),

                array(
                    'type' => 'toggle',
                    'name' => 'atbd_video_url',
                    'label' => __('Display Listing Video', ATBDP_TEXTDOMAIN),
                    'default' => 1,
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'enable_rel_listing',
                    'label' => __('Display Related Listings', ATBDP_TEXTDOMAIN),
                    'default' => 4,
                ),

                array(
                    'type' => 'textbox',
                    'name' => 'rel_listing_title',
                    'label' => __('Related Listings Title', ATBDP_TEXTDOMAIN),
                    'default' => __('Related Listings', ATBDP_TEXTDOMAIN),
                ),
                array(
                    'type' => 'slider',
                    'name' => 'rel_listing_num',
                    'label' => __('Number of Related Listings', ATBDP_TEXTDOMAIN),
                    'min' => '1',
                    'max' => '10',
                    'step' => '1',
                    'default' => '5',
                    'validation' => 'numeric|minlength[1]',
                ),
                array(
                    'type' => 'slider',
                    'name' => 'rel_listing_column',
                    'label' => __('Columns of Related Listings', ATBDP_TEXTDOMAIN),
                    'min' => '1',
                    'max' => '10',
                    'step' => '1',
                    'default' => '2',
                    'validation' => 'numeric|minlength[1]',
                ),
                array(
                    'type' => 'toggle',
                    'name' => 'fix_listing_double_thumb',
                    'label' => __('Fix Repeated Thumbnail of Single Listing', ATBDP_TEXTDOMAIN),
                    'default' => 0,
                ),


            ));
        }

        /**
         * Get all the settings fields for the listings settings section
         * @return array
         * @since 3.0.0
         */
        function get_general_listings_settings_fields()
        {
            // BACKWARD COMPATIBILITY:  OLD SETTINGS DATA that should be adapted by using them as default value, will be removed in future
            $s_p_cat = atbdp_get_option('show_popular_category', 'atbdp_general', 'yes');
            $e_p_list = atbdp_get_option('enable_pop_listing', 'atbdp_general', 'yes');
            $e_r_list = atbdp_get_option('enable_rel_listing', 'atbdp_general', 'yes');

            return apply_filters('atbdp_all_listings_settings_fields', array(
                    array(
                        'type' => 'toggle',
                        'name' => 'fix_js_conflict',
                        'label' => __('Fix Conflict with Bootstrap JS', ATBDP_TEXTDOMAIN),
                        'description' => __('If you use a theme that uses Bootstrap Framework especially Bootstrap JS, then Check this setting to fix any conflict with theme bootstrap js.', ATBDP_TEXTDOMAIN),
                        'default' => 0,
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'font_type',
                        'label' => __('Icon Library', ATBDP_TEXTDOMAIN),
                        'items' => array(
                            array(
                                'value' => 'font',
                                'label' => __('Font Awesome', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'line',
                                'label' => __('Line Awesome', ATBDP_TEXTDOMAIN),
                            ),
                        ),
                        'default' => array(
                            'value' => 'line',
                            'label' => __('Line Awesome', ATBDP_TEXTDOMAIN),
                        ),
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'display_preview_image',
                        'label' => __('Show Preview Image', ATBDP_TEXTDOMAIN),
                        'description' => __('Hide/show preview image from all listing page.', ATBDP_TEXTDOMAIN),
                        'default' => 1,
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'thumbnail_cropping',
                        'label' => __('Preview Image Cropping', ATBDP_TEXTDOMAIN),
                        'description' => __('If the preview images are not in the same size, it helps automatically resizing.', ATBDP_TEXTDOMAIN),
                        'default' => 1,
                    ),
                    array(
                        'type' => 'upload',
                        'name' => 'default_preview_image',
                        'label' => __('Default Preview Image', ATBDP_TEXTDOMAIN),
                        'default' => ATBDP_PUBLIC_ASSETS . 'images/grid.jpg',
                    ),

                    array(
                        'type' => 'slider',
                        'name' => 'crop_width',
                        'label' => __('Image Cropping Width', ATBDP_TEXTDOMAIN),
                        'min' => '1',
                        'max' => '1200',
                        'step' => '1',
                        'default' => '350',

                    ),

                    array(
                        'type' => 'slider',
                        'name' => 'crop_height',
                        'label' => __('Image Cropping Height', ATBDP_TEXTDOMAIN),
                        'min' => '1',
                        'max' => '1200',
                        'step' => '1',
                        'default' => '260',

                    ),

                    array(
                        'type' => 'toggle',
                        'name' => 'info_display_in_single_line',
                        'label' => __('Display Each Grid Info on Single Line', ATBDP_TEXTDOMAIN),
                        'description' => __('Here Yes means display all the informations (i.e. title, tagline, excerpt etc.) of grid view on single line', ATBDP_TEXTDOMAIN),
                        'default' => '0',
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'display_title',
                        'label' => __('Display Title', ATBDP_TEXTDOMAIN),
                        'default' => '1',
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'enable_tagline',
                        'label' => __('Display Tagline', ATBDP_TEXTDOMAIN),
                        'default' => 0,
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'enable_excerpt',
                        'label' => __('Display Excerpt', ATBDP_TEXTDOMAIN),
                        'default' => 0,
                    ),
                    array(
                        'type' => 'slider',
                        'name' => 'excerpt_limit',
                        'label' => __('Excerpt Words Limit', ATBDP_TEXTDOMAIN),
                        'min' => '5',
                        'max' => '200',
                        'step' => '1',
                        'default' => '20',
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'display_readmore',
                        'label' => __('Display Excerpt Readmore', ATBDP_TEXTDOMAIN),
                        'default' => '0',
                    ),
                    array(
                        'type' => 'textbox',
                        'name' => 'readmore_text',
                        'label' => __('Read More Text', ATBDP_TEXTDOMAIN),
                        'default' => __('Read More', ATBDP_TEXTDOMAIN),
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'display_price',
                        'label' => __('Display Price', ATBDP_TEXTDOMAIN),
                        'default' => '1',
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'display_contact_info',
                        'label' => __('Display Contact Information', ATBDP_TEXTDOMAIN),
                        'default' => '1',
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'address_location',
                        'label' => __('Address', ATBDP_TEXTDOMAIN),
                        'items' => array(
                            array(
                                'value' => 'location',
                                'label' => __('Display From Location', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'contact',
                                'label' => __('Display From Contact Information', ATBDP_TEXTDOMAIN),
                            ),
                        ),
                        'description' => __('Choose which address you want to show on listings page', ATBDP_TEXTDOMAIN),
                        /*@todo; later add option to make listing status hidden or invalid for expired listing, so that admin may retain expired listings without having them deleted after the deletion threshold */
                        'default' => array(
                            'value' => 'contact',
                            'label' => __('Contact Information', ATBDP_TEXTDOMAIN),
                        ),
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'display_publish_date',
                        'label' => __('Hide Publish Date', ATBDP_TEXTDOMAIN),
                        'default' => '1',
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'display_category',
                        'label' => __('Display Category', ATBDP_TEXTDOMAIN),
                        'default' => '1',
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'display_view_count',
                        'label' => __('Display View Count', ATBDP_TEXTDOMAIN),
                        'default' => '1',
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'display_author_image',
                        'label' => __('Display Author Image', ATBDP_TEXTDOMAIN),
                        'default' => '1',
                    ),
                    array(
                        'type' => 'slider',
                        'name' => 'listing_expire_in_days',
                        'label' => __('Default Listing Expires in Days', ATBDP_TEXTDOMAIN),
                        'description' => __('Set it to 0 to keep it alive forever.', ATBDP_TEXTDOMAIN),
                        'min' => '0',
                        'max' => '730',
                        'step' => '1',
                        'default' => 365,
                        'validation' => 'numeric',
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'can_renew_listing',
                        'label' => __('Can User Renew Listing?', ATBDP_TEXTDOMAIN),
                        'description' => __('Here YES means users can renew their listings.', ATBDP_TEXTDOMAIN),
                        'default' => 1,
                    ),
                    array(
                        'type' => 'toggle',
                        'name' => 'delete_expired_listing',
                        'label' => __('Delete/Trash Expired Listings', ATBDP_TEXTDOMAIN),
                        'default' => 1,
                    ),
                    array(
                        'type' => 'slider',
                        'name' => 'delete_expired_listings_after',
                        'label' => __('Delete/Trash Expired Listings After (days) of Expiration', ATBDP_TEXTDOMAIN),
                        'min' => '0',
                        'max' => '180',
                        'step' => '1',
                        'default' => 15,
                        'validation' => 'numeric',
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'deletion_mode',
                        'label' => __('Delete or Trash Expired Listings', ATBDP_TEXTDOMAIN),
                        'items' => array(
                            array(
                                'value' => 'force_delete',
                                'label' => __('Delete Permanently', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'trash',
                                'label' => __('Move to Trash', ATBDP_TEXTDOMAIN),
                            ),
                        ),
                        'description' => __('Choose the Default actions after a listing reaches its deletion threshold.', ATBDP_TEXTDOMAIN),
                        /*@todo; later add option to make listing status hidden or invalid for expired listing, so that admin may retain expired listings without having them deleted after the deletion threshold */
                        'default' => array(
                            'value' => 'trash',
                            'label' => __('Move to Trash', ATBDP_TEXTDOMAIN),
                        ),
                    ),

                    array(
                        'type' => 'toggle',
                        'name' => 'paginate_all_listings',
                        'label' => __('Paginate Listings', ATBDP_TEXTDOMAIN),
                        'default' => '1',
                    ),

                    array(
                        'type' => 'toggle',
                        'name' => 'paginate_author_listings',
                        'label' => __('Paginate Author Listings', ATBDP_TEXTDOMAIN),
                        'default' => '1',
                    ),
                    array(
                        'type' => 'slider',
                        'name' => 'all_listing_page_items',
                        'label' => __('Listings Per Page', ATBDP_TEXTDOMAIN),
                        'min' => '1',
                        'max' => '100',
                        'step' => '1',
                        'default' => '6',
                        'validation' => 'numeric|minlength[1]',
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'display_author_email',
                        'label' => __('Author Email', ATBDP_TEXTDOMAIN),
                        'items' => array(
                            array(
                                'value' => 'public',
                                'label' => __('Display', ATBDP_TEXTDOMAIN),
                            ),
                            array(
                                'value' => 'logged_in',
                                'label' => __('Display only for Logged in Users', ATBDP_TEXTDOMAIN),
                            ),

                            array(
                                'value' => 'none_to_display',
                                'label' => __('Hide', ATBDP_TEXTDOMAIN),
                            ),
                        ),
                        'default' => array(
                            'value' => 'public',
                            'label' => __('Display', ATBDP_TEXTDOMAIN),
                        ),
                    ),
                )
            );
        }
        

        /**
         * @since 5.0
         */
        function get_pages_regenerate_settings_fields()
        {
            return apply_filters('atbdp_regenerate_pages_settings_fields', array(
                array(
                    'type' => 'toggle',
                    'name' => 'shortcode-updated',
                    'label' => __('Upgrade/Regenerate Pages', ATBDP_TEXTDOMAIN),
                    'validation' => 'numeric',

                ),
            ));
        }

        /**
         * Get all the settings fields for the pages settings section
         * @return array
         * @since 3.0.0
         */
        function get_pages_settings_fields()
        {
            return apply_filters('atbdp_pages_settings_fields', array(
                    array(
                        'type' => 'select',
                        'name' => 'add_listing_page',
                        'label' => __('Add Listing Page', ATBDP_TEXTDOMAIN),
                        'items' => $this->get_pages_vl_arrays(), // eg. array( array('value'=> 123, 'label'=> 'page_name') );
                        'description' => sprintf(__('Following shortcode must be in the selected page %s', ATBDP_TEXTDOMAIN), '<strong style="color: #ff4500;">[directorist_add_listing]</strong>'),
                        'default' => atbdp_get_option('add_listing_page', 'atbdp_general'),
                        'validation' => 'numeric',

                    ),

                    array(
                        'type' => 'select',
                        'name' => 'all_listing_page',
                        'label' => __('All Listings Page', ATBDP_TEXTDOMAIN),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__('Following shortcode must be in the selected page %s', ATBDP_TEXTDOMAIN), '<strong style="color: #ff4500;">[directorist_all_listing]</strong>'),

                        'default' => atbdp_get_option('all_listing_page', 'atbdp_general'),
                        'validation' => 'numeric',
                    ),
                   'single_listing_page' => array(
                        'type' => 'select',
                        'name' => 'single_listing_page',
                        'label' => __('Single Listing Page', ATBDP_TEXTDOMAIN),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__('Following shortcodes can be used for the selected page %s', ATBDP_TEXTDOMAIN), '<strong style="color: #ff4500;">[directorist_listing_top_area][directorist_listing_custom_fields][directorist_listing_video][directorist_listing_map][directorist_listing_contact_information][directorist_listing_contact_owner][directorist_listing_author_info][directorist_listing_review][directorist_related_listings]</strong>'),
                        'default' => atbdp_get_option('single_listing_page', 'atbdp_general'),
                        'validation' => 'numeric',
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'user_dashboard',
                        'label' => __('Dashboard Page', ATBDP_TEXTDOMAIN),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__('Following shortcode must be in the selected page %s', ATBDP_TEXTDOMAIN), '<strong style="color: #ff4500;">[directorist_user_dashboard]</strong>'),
                        'default' => atbdp_get_option('user_dashboard', 'atbdp_general'),
                        'validation' => 'numeric',

                    ),

                    array(
                        'type' => 'select',
                        'name' => 'author_profile_page',
                        'label' => __('User Profile Page', ATBDP_TEXTDOMAIN),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__('Following shortcode must be in the selected page %s', ATBDP_TEXTDOMAIN), '<strong style="color: #ff4500;">[directorist_author_profile]</strong>'),
                        'default' => atbdp_get_option('author_profile', 'atbdp_general'),
                        'validation' => 'numeric',

                    ),

                    array(
                        'type' => 'select',
                        'name' => 'all_categories_page',
                        'label' => __('All Categories Page', ATBDP_TEXTDOMAIN),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__('Following shortcode must be in the selected page %s', ATBDP_TEXTDOMAIN), '<strong style="color: #ff4500;">[directorist_all_categories]</strong>'),

                        'default' => atbdp_get_option('all_categories', 'atbdp_general'),
                        'validation' => 'numeric',
                    ),

                    array(
                        'type' => 'select',
                        'name' => 'single_category_page',
                        'label' => __('Single Category Page', ATBDP_TEXTDOMAIN),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__('Following shortcode must be in the selected page %s', ATBDP_TEXTDOMAIN), '<strong style="color: #ff4500;">[directorist_category]</strong>'),

                        'default' => atbdp_get_option('single_category_page', 'atbdp_general'),
                        'validation' => 'numeric',
                    ),

                    array(
                        'type' => 'select',
                        'name' => 'all_locations_page',
                        'label' => __('All Locations Page', ATBDP_TEXTDOMAIN),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__('Following shortcode must be in the selected page %s', ATBDP_TEXTDOMAIN), '<strong style="color: #ff4500;">[directorist_all_locations]</strong>'),

                        'default' => atbdp_get_option('all_locations', 'atbdp_general'),
                        'validation' => 'numeric',
                    ),

                    array(
                        'type' => 'select',
                        'name' => 'single_location_page',
                        'label' => __('Single Location Page', ATBDP_TEXTDOMAIN),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__('Following shortcode must be in the selected page %s', ATBDP_TEXTDOMAIN), '<strong style="color: #ff4500;">[directorist_location]</strong>'),

                        'default' => atbdp_get_option('single_location_page', 'atbdp_general'),
                        'validation' => 'numeric',
                    ),

                    array(
                        'type' => 'select',
                        'name' => 'single_tag_page',
                        'label' => __('Single Tag Page', ATBDP_TEXTDOMAIN),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__('Following shortcode must be in the selected page %s', ATBDP_TEXTDOMAIN), '<strong style="color: #ff4500;">[directorist_tag]</strong>'),
                        'default' => atbdp_get_option('single_tag_page', 'atbdp_general'),
                        'validation' => 'numeric',
                    ),

                    array(
                        'type' => 'select',
                        'name' => 'custom_registration',
                        'label' => __('Registration Page', ATBDP_TEXTDOMAIN),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__('Following shortcode must be in the selected page %s', ATBDP_TEXTDOMAIN), '<strong style="color: #ff4500;">[directorist_custom_registration]</strong>'),
                        'default' => atbdp_get_option('custom_registration', 'atbdp_general'),
                        'validation' => 'numeric',
                    ),

                    array(
                        'type' => 'select',
                        'name' => 'user_login',
                        'label' => __('Login Page', ATBDP_TEXTDOMAIN),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__('Following shortcode must be in the selected page %s', ATBDP_TEXTDOMAIN), '<strong style="color: #ff4500;">[directorist_user_login]</strong>'),
                        'default' => atbdp_get_option('user_login', 'atbdp_general'),
                        'validation' => 'numeric',

                    ),

                    array(
                        'type' => 'select',
                        'name' => 'search_listing',
                        'label' => __('Listing Search Page', ATBDP_TEXTDOMAIN),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__('Following shortcode must be in the selected page %s', ATBDP_TEXTDOMAIN), '<strong style="color: #ff4500;">[directorist_search_listing]</strong>'),
                        'default' => atbdp_get_option('search_listing', 'atbdp_general'),
                        'validation' => 'numeric',
                    ),

                    array(
                        'type' => 'select',
                        'name' => 'search_result_page',
                        'label' => __('Listing Search Result Page', ATBDP_TEXTDOMAIN),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__('Following shortcode must be in the selected page %s', ATBDP_TEXTDOMAIN), '<strong style="color: #ff4500;">[directorist_search_result]</strong>'),
                        'default' => atbdp_get_option('search_result_page', 'atbdp_general'),
                        'validation' => 'numeric',
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'checkout_page',
                        'label' => __('Checkout Page', ATBDP_TEXTDOMAIN),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__('Following shortcode must be in the selected page %s', ATBDP_TEXTDOMAIN), '<strong style="color: #ff4500;">[directorist_checkout]</strong>'),
                        'default' => '',
                        'validation' => 'numeric',
                    ),

                    array(
                        'type' => 'select',
                        'name' => 'payment_receipt_page',
                        'label' => __('Payment/Order Receipt Page', ATBDP_TEXTDOMAIN),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__('Following shortcode must be in the selected page %s', ATBDP_TEXTDOMAIN), '<strong style="color: #ff4500;">[directorist_payment_receipt]</strong>'),
                        'default' => '',
                        'validation' => 'numeric',
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'transaction_failure_page',
                        'label' => __('Transaction Failure Page', ATBDP_TEXTDOMAIN),
                        'items' => $this->get_pages_vl_arrays(),
                        'description' => sprintf(__('Following shortcode must be in the selected page %s', ATBDP_TEXTDOMAIN), '<strong style="color: #ff4500;">[directorist_transaction_failure]</strong>'),
                        'default' => '',
                        'validation' => 'numeric',
                    ),


                )
            );
        }

        /**
         * Get all the settings fields for the extension settings section
         * @return array
         * @since 3.0.0
         */
        function get_extension_settings_fields()
        {
            return apply_filters('atbdp_extension_settings_fields', array(
                   'extension_promotion_set' => array(
                        'type' => 'notebox',
                        'name' => 'extension_promotion',
                        'label' => __('Need more Features?', ATBDP_TEXTDOMAIN),
                        'description' => sprintf(__('You can add new features and expand the functionality of the plugin even more by using extensions. %s', ATBDP_TEXTDOMAIN), $this->extension_url),
                        'status' => 'warning',
                    ),
                )
            );
        }


    } // ends ATBDP_Settings_Manager
endif;
