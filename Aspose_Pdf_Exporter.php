<?php
/*
Plugin Name: Aspose.PDF Exporter
Plugin URI:
Description: Aspose.PDF Exporter capable of exporting one or many posts to a single pdf file.
Version: 3.2
Author: aspose.cloud Marketplace
Author URI: https://www.aspose.cloud/

*/

#### INSTALLATION PROCESS ####
/*
1. Download the plugin and extract it
2. Upload the directory '/Aspose-Pdf-Exporter/' to the '/wp-content/plugins/' directory
3. Activate the plugin through the 'Plugins' menu in WordPress.
4. Click on 'Aspose.PDF Importer' link under Settings menu to access the admin section.
5. Click `Enable Free and Unlimited Access`. No Sign Up required.
*/

add_filter('plugin_action_links', 'AsposePdfExporterPluginLinks', 10, 2);

define("ASPOSE_PDF_EXPORTER_PLUGIN_FILE", __FILE__);

/**
 * Create the settings link for this plugin
 * @param $links array
 * @param $file string
 * @return $links array
 */
function AsposePdfExporterPluginLinks($links, $file) {
     static $this_plugin;

     if (!$this_plugin) {
		$this_plugin = plugin_basename(__FILE__);
     }

     if ($file == $this_plugin) {
		$settings_link = '<a href="' . admin_url('options-general.php?page=AsposePdfExporterAdminMenu') . '">' . __('Settings', 'Aspose-Pdf-Exporter') . '</a>';
		array_unshift($links, $settings_link);
     }

     return $links;
}


/**
 * For removing options
 * @param no-param
 * @return no-return
 */
function UnsetOptionsAsposePdfExporter() {
    // Deleting the added options on plugin uninstall
	// Removing older version Keys
    delete_option('aspose_pdf_exporter_app_sid');
    delete_option('aspose_pdf_exporter_app_key');
	// Removing the keys
    delete_option('aspose-cloud-app-sid');
    delete_option('aspose-cloud-app-key');	

}

register_uninstall_hook(__FILE__, 'UnsetOptionsAsposePdfExporter');

function AsposePdfExporterAdminRegisterSettings() {
     // Registering the settings

     register_setting('aspose_pdf_exporter_options', 'aspose-cloud-app-sid');
     register_setting('aspose_pdf_exporter_options', 'aspose-cloud-app-key');
}

add_action('admin_init', 'AsposePdfExporterAdminRegisterSettings');


if (is_admin()) {
	// Include the file for loading plugin settings
	require_once('aspose_pdf_exporter_admin.php');
}

