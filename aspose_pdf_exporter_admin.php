<?php
require __DIR__.'/vendor/autoload.php';

use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Token;

/**
 * Create the admin menu for this plugin
 * @param no-param
 * @return no-return
 */
function AsposePdfExporterAdminMenu() {
    add_options_page('Aspose.PDF Exporter', __('Aspose.PDF Exporter', 'aspose-pdf-exporter'), 'activate_plugins', 'AsposePdfExporterAdminMenu', 'AsposePdfExporterAdminContent');
}

add_action('admin_menu', 'AsposePdfExporterAdminMenu');

// Defineing the Activator URL
if (!defined("ASPOSE_CLOUD_MARKETPLACE_ACTIVATOR_URL")) {
	define("ASPOSE_CLOUD_MARKETPLACE_ACTIVATOR_URL","https://activator.marketplace.aspose.cloud/activate");
}
// Setting up Secret key	
if(!get_option("aspose-cloud-activation-secret")){
	update_option("aspose-cloud-activation-secret", bin2hex(random_bytes(64)));						
}

/**
 * Pluing settings page
 * @param no-param
 * @return jwt based token
 */	
    function getToken_aspsoe_pdf_exporter() {
        if (!array_key_exists("token", $_REQUEST) || !get_option("aspose-cloud-activation-secret")) {
            return null;
        }
        try {
            $token = (new Parser())->parse($_REQUEST["token"]);
        } catch (Exception $x) {
            return null;
        }
        if (!($token->hasClaim("iss")) || $token->getClaim("iss") !== "https://activator.marketplace.aspose.cloud/") {
            return null;
        }
        $signer = new Sha256();
        $key = new Key(get_option("aspose-cloud-activation-secret"));
        if (!$token->verify($signer, $key)) {
            update_option("aspose-cloud-activation-secret", null);
            wp_die("Unable to verify token signature.");
        }
        return $token;
    }	


/**
 * Pluing settings page
 * @param no-param
 * @return no-return
 */
function AsposePdfExporterAdminContent() {

    // Creating the admin configuration interface
    ?>
    <div class="wrap">
    <h2><?php echo __('Aspose.PDF Exporter Options', 'aspose-pdf-exporter');?></h2>
    <br class="clear" />

    <div class="metabox-holder has-right-sidebar" id="poststuff">
    <div class="inner-sidebar" id="side-info-column">
        <div class="meta-box-sortables ui-sortable" id="side-sortables">
            <div id="AsposePdfExporterOptions" class="postbox">
                <div title="Click to toggle" class="handlediv"><br /></div>
                <h3 class="hndle"><?php echo __('Support / Manual', 'aspose-pdf-exporter'); ?></h3>
                <div class="inside">
                    <p style="margin:15px 0px;"><?php echo __('For any suggestion / query / issue / requirement, please feel free to drop an email to', 'aspose-pdf-importer'); ?> <a href="mailto:marketplace@aspose.cloud?subject=Aspose.PDF Importer">marketplace@aspose.cloud</a>.</p>
                </div>
            </div>

            <div id="AsposePdfExporterOptions" class="postbox">
                <div title="Click to toggle" class="handlediv"><br /></div>
                <h3 class="hndle"><?php echo __('Review', 'aspose-pdf-exporter'); ?></h3>
                <div class="inside">
                    <p style="margin:15px 0px;">
                        <?php echo __('Please feel free to add your reviews on', 'aspose-pdf-exporter'); ?> <a href="http://wordpress.org/support/view/plugin-reviews/aspose-pdf-exporter" target="_blank"><?php echo __('Wordpress', 'aspose-pdf-exporter');?></a>.</p>
                    </p>

                </div>
            </div>
        </div>
    </div>

    <div id="post-body">
        <div id="post-body-content">
		                <div class="postbox">
                    <h3 class="hndle">aspose.cloud Subscription</h3>
                    <div class="inside">
                        <p>
						<?php if (array_key_exists("token", $_REQUEST) ){
								if (!(getToken_aspsoe_pdf_exporter()->hasClaim("aspose-cloud-app-sid")) || !(getToken_aspsoe_pdf_exporter()->hasClaim("aspose-cloud-app-key"))) {
									wp_die("The token has some invalid data");
								}
								update_option("aspose-cloud-app-sid", getToken_aspsoe_pdf_exporter()->getClaim("aspose-cloud-app-sid"));
								update_option("aspose-cloud-app-key", getToken_aspsoe_pdf_exporter()->getClaim("aspose-cloud-app-key"));
								update_option("aspose-cloud-activation-secret", null);
							}
							?>                            
                        </p>
                        <?php if (strlen(get_option("aspose-cloud-app-sid")) < 1): ?>
                            <p>
                                <a class="button-primary" href="<?php echo ASPOSE_CLOUD_MARKETPLACE_ACTIVATOR_URL; ?>?callback=<?php echo urlencode(site_url()."/wp-admin/options-general.php?page=AsposePdfExporterAdminMenu"); ?>&secret=<?php echo get_option("aspose-cloud-activation-secret"); ?>">
                                    <b>Enable FREE and Unlimited Access</b>
                                </a>
                            </p>
                            <p style="font-size: xx-small">
                                Your website URL
                                <i><?php echo site_url(); ?></i>
                                and admin email
                                <i><?php echo get_bloginfo("admin_email"); ?></i>
                                will be sent to
                                <i>aspose.cloud</i>
                                during the process.
                            </p>
                        <?php else: ?>
                            <h4>
                                <button disabled="disabled">FREE Unlimited Access is enabled</button>                                
                            </h4>
                            <p style="font-size: xx-small">
							App SID:<?php echo get_option("aspose-cloud-app-sid"); ?><br>
                                You can disable FREE Unlimited Access by deactivating/uninstalling the plugin.
                            </p>
                        <?php endif; ?>
                    </div>
                </div>		
            <!--div id="WtiLikePostOptions" class="postbox">
                <h3><?php echo __('Configuration / Settings', 'aspose-pdf-exporter'); ?></h3>

                <div class="inside">
                    <form method="post" action="options.php">
                        <?php settings_fields('aspose_pdf_exporter_options'); ?>
                        <table class="form-table">



                            <tr valign="top">
                                <td colspan="2">
                                    <p> If you don't have an account with Aspose Cloud, <a target="_blank" href="https://cloud.aspose.com/SignUp?src=total-api"> Click here </a> to Sign Up.</p>
                                </td>

                            </tr>

                            <tr valign="top">
                                <th scope="row"><label><?php _e('App SID', 'aspose-pdf-exporter'); ?></label></th>
                                <td>
                                    <input type="text" size="40" name="aspose_pdf_exporter_app_sid" id="aspose_pdf_exporter_app_sid" value="<?php echo get_option('aspose_pdf_exporter_app_sid'); ?>" />
                                    <span class="description"><?php _e('Aspose for Cloud App sID.', 'aspose-pdf-exporter');?></span>
                                </td>
                            </tr>

                            <tr valign="top">
                                <th scope="row"><label><?php _e('App key', 'aspose-pdf-exporter'); ?></label></th>
                                <td>
                                    <input type="text" size="40" name="aspose_pdf_exporter_app_key" id="aspose_pdf_exporter_app_key" value="<?php echo get_option('aspose_pdf_exporter_app_key'); ?>" />
                                    <span class="description"><?php _e('Aspose for Cloud App Key.', 'aspose-pdf-exporter');?></span>
                                </td>
                            </tr>


                            <tr valign="top">
                                <th scope="row"></th>
                                <td>
                                    <input class="button-primary" type="submit" name="Save" value="<?php _e('Save Options', 'aspose-pdf-exporter'); ?>" />
                                    <input class="button-secondary" type="reset" name="Reset" value="<?php _e('Reset', 'aspose-pdf-exporter'); ?>" />
                                </td>
                            </tr>
                        </table>
                    </form-->
                </div>
            </div>
        </div>
    </div>
<?php
}

add_action( 'admin_footer',  'aspose_pdf_exporter_add_export_option' );
function aspose_pdf_exporter_add_export_option() {
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            jQuery('<option>').val('aspose_export_pdf').text('<?php echo('Export To PDF \(Aspose.PDF Exporter\) ')?>').appendTo("select[name='action']");
            jQuery('<option>').val('aspose_export_pdf').text('<?php echo('Export To PDF \(Aspose.PDF Exporter\)')?>').appendTo("select[name='action2']");
        });
    </script>

<?php
}

add_action('load-edit.php','aspose_pdf_exporter_bulk_action');

function aspose_pdf_exporter_bulk_action(){

    $upload_dir = wp_upload_dir();
    $upload_path = $upload_dir['path'] . '/';

    $html_file = $upload_path . 'outpuut.html';
    $pdf_file = $upload_path . 'outpuut.pdf';

    @unlink($html_file);
    @unlink($pdf_file);

    global $typenow;
    $post_type = $typenow;

    // get the action
    $wp_list_table = _get_list_table('WP_Posts_List_Table');  // depending on your resource type this could be WP_Users_List_Table, WP_Comments_List_Table, etc
    $action = $wp_list_table->current_action();

    $allowed_actions = array("aspose_export_pdf");

    if(!in_array($action, $allowed_actions)) return;

    // security check
    check_admin_referer('bulk-posts');

    // make sure ids are submitted.  depending on the resource type, this may be 'media' or 'ids'
    if(isset($_REQUEST['post'])) {
        $post_ids = array_map('intval', $_REQUEST['post']);
    }

    if(empty($post_ids)) return;

    // this is based on wp-admin/edit.php
    $sendback = remove_query_arg( array('exported', 'untrashed', 'deleted', 'ids'), wp_get_referer() );

    if ( ! $sendback )
        $sendback = admin_url( "edit.php?post_type=$post_type" );

    $pagenum = $wp_list_table->get_pagenum();

    $sendback = add_query_arg( 'paged', $pagenum, $sendback );

    switch($action) {
        case 'aspose_export_pdf':

            $exported = count($post_ids);

            $post_contents = aspose_pdf_exporter_array_builder($post_ids);

            if ( is_array($post_contents) && count($post_contents) > 0 ) {
                $file_name = aspose_pdf_exporter_array_to_html($post_contents);
                include_once('asposePdfConverter.php');
            } else {
                wp_die( __('Error exporting post.') );
            }

            global $html_filename;
            $file_name = str_replace('.html','.pdf',$html_filename);

            $upload_dir = wp_upload_dir();
            $upload_path = $upload_dir['path'] . '/';

            $file_name = $upload_path . $file_name;

            $file_details = pathinfo($file_name);

            if($file_details['extension'] == 'pdf') {
                header ("Content-type: octet/stream");

                header ("Content-disposition: attachment; filename=".basename($file_name).";");

                header("Content-Length: ".filesize($file_name));

                readfile($file_name);
                exit;

            } else {
                echo "Invalid File!";
            }


            break;

        default:
            return;
    }



    $sendback = remove_query_arg( array('action', 'action2', 'tags_input', 'post_author', 'comment_status', 'ping_status', '_status',  'post', 'bulk_edit', 'post_view'), $sendback );

    wp_redirect($sendback);
    exit();
}

function aspose_pdf_exporter_array_builder($post_ids) {

    foreach($post_ids as $post_id) {

        $post_data = get_post($post_id);

        $post_title = $post_data->post_title;
        $post_content = apply_filters('the_content',$post_data->post_content) ;

        $post_contents[$post_title] = $post_content;
    }


    if(is_array($post_contents)) {
        return $post_contents;
    } else {
        return false;
    }

}

function aspose_pdf_exporter_array_to_html($post_contents){
    global $html_filename;
    $upload_dir = wp_upload_dir();
    $upload_path = $upload_dir['path'] . '/';
    $html_filename = 'output_'.time().'.html';

    $output_string = <<<EOD
<!DOCTYPE html>
<html>
    <head>
        <title></title>
    </head>
    <body>
EOD;

    foreach($post_contents as $post_title=>$post_content) {
        $output_string .= <<<EOD
        <h1> {$post_title} </h1>
        <p> {$post_content} </p>
        <hr />
EOD;
    }

    $output_string .= <<<EOD
</body>
</html>
EOD;

    @unlink($upload_path . $html_filename);
    file_put_contents($upload_path . $html_filename,$output_string);

    return $upload_path . $html_filename;
}

