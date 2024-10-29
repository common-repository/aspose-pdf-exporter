<?php

/*
 * Including the sdk of php
 */

require __DIR__.'/vendor/autoload.php';

use Aspose\PDF\Api\PdfApi;
use Aspose\PDF\Configuration;

$upload_dir = wp_upload_dir();
$upload_path = $upload_dir['path'] . '/';

/*
 *  Assign appSID and appKey of your Aspose App
 */
$appSID = get_option('aspose-cloud-app-sid');
$appKey = get_option('aspose-cloud-app-key');
$outPutLocation = $upload_path;


/*
 * Assign Base Product URL
 */
 
 
$baseProductUri = 'https://api.aspose.cloud'; 

global $html_filename;


$zip_file_name = str_replace('.html','.zip',$html_filename);
$zip_file_path = str_replace('.html','.zip',$file_name);

$config = new Configuration();

$config->setAppKey($appKey);
$config->setAppSid($appSID);


$pdfApi = new PdfApi(null, $config);

global $wp_version;
$pdfApi->getConfig()->setUserAgent(sprintf("%s/%s WordPress/$wp_version PHP/%s",
				get_plugin_data(ASPOSE_PDF_EXPORTER_PLUGIN_FILE)["Name"],
				get_plugin_data(ASPOSE_PDF_EXPORTER_PLUGIN_FILE)["Version"],
				PHP_VERSION
			));

$zip = new ZipArchive;
if ($zip->open($zip_file_path, ZipArchive::CREATE)!==TRUE) {
    exit("cannot open <$zip_file_name>\n");
}
    $zip->addFile($file_name,$html_filename); 
    // File is added, so close the zip file.
    $zip->close();
$pdfApi->uploadFile($zip_file_name, $zip_file_path );
$result = $pdfApi->getHtmlInStorageToPdf($zip_file_name, $html_filename);
file_put_contents(str_replace('.html','.pdf',$file_name), file_get_contents($result->getPathname()));

