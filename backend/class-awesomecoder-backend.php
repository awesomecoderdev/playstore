<?php

namespace AwesomeCoder\Plugin\Playstore\Backend;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Awesomecoder
 * @subpackage Awesomecoder/controller
 * @author     Mohammad Ibrahim <awesomecoder.dev@gmail.com>
 *                                                              __
 *                                                             | |
 *    __ ___      _____  ___  ___  _ __ ___   ___  ___ ___   __| | ___ _ ____
 *   / _` \ \ /\ / / _ \/ __|/ _ \| '_ ` _ \ / _ \/ __/ _ \ / _` |/ _ \ ' __|
 *  | (_| |\ V  V /  __/\__ \ (_) | | | | | |  __/ (_| (_) | (_| |  __/	 |
 *  \__,_| \_/\_/ \___||___/\___/|_| |_| |_|\___|\___\___/ \__,_|\___|__|
 *
 */
class Awesomecoder_Backend
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The pages of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $pages    The pages of this plugin.
	 */
	private  $pages;

	/**
	 * The metabox of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $metabox    The metabox of this plugin.
	 */
	private  $metabox;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->pages = [
			"toplevel_page_playstore",
		];

		$this->metabox = [
			"post.php",
			"post-new.php",
		];
	}

	/**
	 * Initialize the main menu and set its properties.
	 *
	 * @since    1.0.0
	 *
	 */
	public function awesomecoder_admin_menu()
	{
		add_menu_page(__("Playstore", "awesomecoder"), __("Playstore", "awesomecoder"), 'manage_options', 'playstore', array($this, 'menu_activator_callback'), 'dashicons-image-filter', 50);
		add_submenu_page('playstore', __("Dashboard", "awesomecoder"), __("Dashboard", "awesomecoder"), 'manage_options', 'playstore', array($this, 'awesomecoder_dashboard_callback'));
	}

	/**
	 * Initialize the menu.
	 *
	 * @since    1.0.0
	 *
	 */
	public function menu_activator_callback()
	{
		// activate admin menu
	}

	/**
	 * Initialize the view of dashboard page.
	 *
	 * @since    1.0.0
	 *
	 */
	public function awesomecoder_dashboard_callback()
	{
		ob_start();
		include_once AWESOMECODER_PLAYSTORE_PATH . 'backend/views/index.php';
		$index = ob_get_contents();
		ob_end_clean();
		echo $index;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles($hook)
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Awesomecoder_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Awesomecoder_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// if (in_array($hook, $this->pages)) {
		// 	wp_enqueue_style("{$this->plugin_name}", AWESOMECODER_PLAYSTORE_URL . 'assets/css/app.css', array(), (filemtime(AWESOMECODER_PLAYSTORE_PATH . "assets/css/app.css") ?? $this->version), 'all');
		// 	wp_enqueue_style("{$this->plugin_name}", AWESOMECODER_PLAYSTORE_URL . 'backend/css/awesomecoder-backend.css', array(), (filemtime(AWESOMECODER_PLAYSTORE_PATH . "backend/css/awesomecoder-backend.css") ?? $this->version), 'all');
		// }

		// metabox css
		if (in_array($hook, $this->metabox)) {
			wp_enqueue_style("{$this->plugin_name}-metabox", AWESOMECODER_PLAYSTORE_URL . 'backend/css/metabox.css', array(), filemtime(AWESOMECODER_PLAYSTORE_PATH . "backend/css/metabox.css"), 'all');
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts($hook)
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Awesomecoder_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Awesomecoder_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script("{$this->plugin_name}", AWESOMECODER_PLAYSTORE_URL . 'backend/js/awesomecoder-init.js', array('jquery'), (filemtime(AWESOMECODER_PLAYSTORE_PATH . "backend/js/awesomecoder-init.js") ?? $this->version), false);
		// Some local vairable to get ajax url
		wp_localize_script($this->plugin_name, 'awesomecoder', array(
			"plugin" => [
				"name"		=> 	"PlayStore Data Scraper",
				"author" 	=>	"Mohammad Ibrahim",
				"email" 	=>	"awesomecoder.dev@gmail.com",
				"website" 	=>	"https://awesomecoder.dev",
			],
			"url" 		=> get_bloginfo('url'),
			"ajaxurl"	=> admin_url("admin-ajax.php?action=awesomecoder_backend"),
			"http"	=> AWESOMECODER,
			"auth" => AWESOMECODER_AUTH,
			"modified_date" =>	get_the_modified_date(get_option('date_format')),
			"metabox" => [
				"fields" => [
					[
						"label" => __("App Link", "awesomecoder"),
						"placeholder" => __("App Link", "awesomecoder"),
						"name" => "awesomecoder_app_link",
						"type" => "text",
					],
					[
						"label" => __("Icon", "awesomecoder"),
						"placeholder" => __("Icon", "awesomecoder"),
						"name" => "awesomecoder_app_icon",
						"type" => "text",
					],
					[
						"label" => __("Downloads", "awesomecoder"),
						"placeholder" => __("Downloads", "awesomecoder"),
						"name" => "awesomecoder_app_downloads",
						"type" => "text",
					],
					[
						"label" => __("Stars", "awesomecoder"),
						"placeholder" => __("Stars", "awesomecoder"),
						"name" => "awesomecoder_app_stars",
						"type" => "text",
					],
					[
						"label" => __("Ratings", "awesomecoder"),
						"placeholder" => __("Ratings", "awesomecoder"),
						"name" => "awesomecoder_app_ratings",
						"type" => "text",
					],
					[
						"label" => __("Company Name", "awesomecoder"),
						"placeholder" => __("Company Name", "awesomecoder"),
						"name" => "awesomecoder_app_devName",
						"type" => "text",
					],
					[
						"label" => __("Company Link", "awesomecoder"),
						"placeholder" => __("Company Link", "awesomecoder"),
						"name" => "awesomecoder_app_devLink",
						"type" => "text",
					],
					[
						"label" => __("Last Version", "awesomecoder"),
						"placeholder" => __("Last Version", "awesomecoder"),
						"name" => "awesomecoder_app_last_version",
						"type" => "text",
					],
					[
						"label" => __("Last Version Size", "awesomecoder"),
						"placeholder" => __("Size", "awesomecoder"),
						"name" => "awesomecoder_app_size",
						"type" => "text",
						"disabled" => true,
					],
					[
						"label" => __("Compatible With", "awesomecoder"),
						"placeholder" => __("Compatible With", "awesomecoder"),
						"name" => "awesomecoder_app_compatible_with",
						"type" => "text",
					],
					[
						"label" => __("Price", "awesomecoder"),
						"placeholder" => __("Price", "awesomecoder"),
						"name" => "awesomecoder_app_price",
						"type" => "text",
					],

				],
				"states" => [
					"awesomecoder_app_icon" => get_post_meta(get_the_ID(), "awesomecoder_app_icon", true),
					"awesomecoder_app_downloads" => get_post_meta(get_the_ID(), "awesomecoder_app_downloads", true),
					"awesomecoder_app_stars" => get_post_meta(get_the_ID(), "awesomecoder_app_stars", true),
					"awesomecoder_app_ratings" => get_post_meta(get_the_ID(), "awesomecoder_app_ratings", true),
					"awesomecoder_app_devName" => get_post_meta(get_the_ID(), "awesomecoder_app_devName", true),
					"awesomecoder_app_devLink" => get_post_meta(get_the_ID(), "awesomecoder_app_devLink", true),
					"awesomecoder_app_compatible_with" => get_post_meta(get_the_ID(), "awesomecoder_app_compatible_with", true),
					"awesomecoder_app_size" => get_post_meta(get_the_ID(), "awesomecoder_app_size", true),
					"awesomecoder_app_last_version" => get_post_meta(get_the_ID(), "awesomecoder_app_last_version", true),
					"awesomecoder_app_link" => get_post_meta(get_the_ID(), "awesomecoder_app_link", true),
					"awesomecoder_app_price" => get_post_meta(get_the_ID(), "awesomecoder_app_price", true),
					"awesomecoder_app_upload" => get_post_meta(get_the_ID(), "awesomecoder_app_upload", true),
					"post_id" => get_the_ID(),
				]
			],
			"uploads" => [
				[
					"version" => "0.0.0",
					"file" => "file.apk",
					"size" => "25mb"
				],
				[
					"version" => "0.0.1",
					"file" => "file1.apk",
					"size" => "25mb"
				],
				[
					"version" => "0.0.2",
					"file" => "fil2.apk",
					"size" => "25mb"
				],
				[
					"version" => "0.0.0",
					"file" => "file.apk",
					"size" => "25mb"
				],
				[
					"version" => "0.0.1",
					"file" => "file1.apk",
					"size" => "25mb"
				],
				[
					"version" => "0.0.2",
					"file" => "fil2.apk",
					"size" => "25mb"
				]
			]
		));

		// if (in_array($hook, $this->pages)) {
		// 	wp_enqueue_script("{$this->plugin_name}-backend", AWESOMECODER_PLAYSTORE_URL . 'backend/js/backend.js', array('jquery'), (filemtime(AWESOMECODER_PLAYSTORE_PATH . "backend/js/backend.js") ?? $this->version), true);
		// }

		// metabox css
		if (in_array($hook, $this->metabox)) {
			wp_enqueue_script("{$this->plugin_name}-metabox", AWESOMECODER_PLAYSTORE_URL . 'backend/js/metabox.js', array(), (filemtime(AWESOMECODER_PLAYSTORE_PATH . "backend/js/metabox.js") ? filemtime(AWESOMECODER_PLAYSTORE_PATH . "backend/js/metabox.js") : $this->version), true);
		}
	}
}
