<?php

namespace AwesomeCoder\Plugin\Playstore\Controller;

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
class Awesomecoder_Activator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{
		Awesomecoder_Handler::setup();

		$download__dir = ABSPATH . "download";
		if (!file_exists($download__dir) && !is_dir($download__dir)) {
			mkdir($download__dir);
		}
		if (is_dir($download__dir)) {
			$index = ABSPATH . "download/index.php";
			file_put_contents(
				$index,
				'<?php
			if (isset($_REQUEST["token"])) {
				$token = $_REQUEST["token"];
				$file = base64_decode($token);
				if (file_exists($file) && is_readable($file)) {
					$type = pathinfo($file, PATHINFO_EXTENSION) ?? "application/force-download";
					header("Content-Description: File Transfer");
					header("Content-Type: " . $type);
					header("Content-Disposition: attachment; filename=" . basename($file));
					header("Content-Transfer-Encoding: binary");
					header("Expires: 0");
					header("Cache-Control: must-revalidate");
					header("Pragma: public");
					header("Content-Length: " . filesize($file));
					ob_clean();
					flush();
					readfile($file);
					exit();
				} else {
					header("Location: ' . site_url("/") . '");
				}
			}else {
				header("Location: ' . site_url("/") . '");
			}
			die;'
			);
		}
	}
}
