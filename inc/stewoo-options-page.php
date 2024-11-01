<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
?>

<script>
( function( $ ) {
    'use strict';
    $( function() {
        function getQueryParameterByName(name, url) {
            if (!url) url = window.location.href;
            name = name.replace(/[\[\]]/g, "\\$&");
            var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, " "));
        }
        $('#email_type_selector').change( function() {
            var url = $('#stewoo_customizer').attr('href');
            var current_email_type = getQueryParameterByName('email_type', $('#stewoo_customizer').attr('href') );
            var new_email_type = $(this).val();
            url = url.replace(new RegExp( current_email_type, 'g'), new_email_type );
            $('#stewoo_customizer').attr('href', url);
        });
        $('.toggle_list_emails').click( function(e){
            e.preventDefault();
            $('.list_emails').toggle();
        } );

        $('.select_file_lnk').click( function(e){
            e.preventDefault();
            $('.input_file_selector').click();
        } );

        $('#input_stewoo_file').on( 'change', function(e){
            $("#import_form").submit();
        })

        $('a.button.export').click(function(e){
            e.preventDefault();
            var now = new Date();
            var label = prompt( "Export name ?", now.getFullYear().toString() + ((now.getMonth()+1) < 10 ? '0' + String(now.getMonth()+1) : String(now.getMonth()+1) ) + (now.getDate()<10 ? '0' + now.getDate().toString() : now.getDate().toString()) + '_' + (now.getHours()<10 ? '0' + now.getHours().toString() : now.getHours().toString()) + (now.getMinutes()<10 ? '0' + now.getMinutes().toString() : now.getMinutes().toString()) + (now.getSeconds()<10 ? '0' + now.getSeconds().toString() : now.getSeconds().toString()) );
            if( label ) {
                window.location = $(this).attr('href') + '&label=' + encodeURI(label);
            }
        })

        $('#export_action a.button.apply').click(function(e){
            e.preventDefault();
            if (confirm('<?php 
_e( 'Apply selected settings ?', 'stewoo' );
?>')) {
                window.location = $(this).attr('href');
            }
        })

        $('#export_action a.button.delete').click(function(e){
            e.preventDefault();
            if (confirm('<?php 
_e( 'Delete selected settings ?', 'stewoo' );
?>')) {
                window.location = $(this).attr('href');
            }
        })
    });
})( jQuery );
</script>
<style>
    .stewoo_wrap {
        margin: 10px 20px 0 2px;
    }
    .list_emails{
        display: none;
    }
    .list_emails + h4{
        margin-top: 30px;
    }
    .stewoo_options_page_hld{
        margin: 35px 30px 30px 30px;
        background-color: #fff;
        border-radius: 4px 4px 0 0;
        border: solid 1px #ddd;
    }
    .stewoo_options_page_hld h2{
        background-color: #72a55c;
        padding: 25px 28px;
        line-height: .8;
        position: relative;
        border-radius: 4px 4px 0 0;
        font-weight: 300;
        font-size: 22px;
        color: #fff;
        margin: 0;
        letter-spacing: 2px;
        background-image: url(<?php 
echo  STEWOO_PLUGIN_URL ;
?>/assets/images/stewoo_logo.png);
        background-repeat: no-repeat;
        background-position: 15px 13px;
        padding-left: 90px;
    }
    .stewoo_options_page_main{
        padding: 20px;
        background-color: #e6e6e6;
        position: relative;
    }
    .button.customize_button{
        margin-top: 10px;
        font-size: 1.4em;
        padding: 7px 16px;
        height: auto;
        background-color: #72a55c;
        color: #fff;
    }
    .button.customize_button:hover{
        background-color: #5c8a48;
        color: #fff;
    }
    .toggle_list_emails{
        text-decoration: none;
        text-transform: uppercase;
    }
    .wrap .notice{
        margin: 0;
    }
    .input_file_selector{
        position: absolute;
        opacity: 0;
        z-index: -1;
    }
    .sublevel_hld{
        background-color: #eee;
        padding: 0 20px;
    }
    .link_to_documentation_hld{
        position: absolute;
        right: 0;
        width: 200px;
        margin-right: 20px;
    }
    #link_to_documentation{
        display: block;
        background-color: #ffd229;
        color: #000;
        letter-spacing: 1px;
        width: 200px;
        padding: 20px;
        text-decoration: none;
        font-size: 15px;
        line-height: 25px;
        text-align: center;
        border-radius: 4px;
        box-shadow: 8px 8px 12px #aaa;
        background: linear-gradient(to bottom, #d3a82a 0%,#fbd633 100%);
        margin-bottom: 20px;
    box-sizing: border-box;
    }
    #link_to_documentation:hover{
        box-shadow: 8px 8px 12px #888;
        text-shadow: none;
    }
    .import_export_hld{
        border: 1px solid;
        padding: 20px;
        display: inline-block;
        margin-top: 40px;
    }
    .import_export_hld legend{
        background-color: #505050;
        color: #fff;
        padding: 3px 6px;
    }
    #import_form{
        margin: 10px 0 30px 0;
    }
    #export_action{
        border: 1px solid #9a9a9a;
        margin-top: 20px;
        border-collapse: collapse;
        border-width: 0 1px;
    }
    #export_action caption{
        text-align: left;
        font-weight: bold;
        margin-bottom: 5px;
    }
    #export_action tr:nth-child(odd){
        background-color: #ccc;
    }
    #export_action td.label{
        text-align: left;
        padding-right: 50px;
        padding-left: 5px;
    }
    #export_action td.action{
        text-align: center;
        display: inline-block;
        white-space: nowrap;
        padding: 5px;
    }
    @media screen and (max-width: 870px) {
        #link_to_documentation{
            display: none;
        }    
    }
</style>
<div class="stewoo_wrap">
<div class="stewoo_options_page_hld">
<h2><b>S</b>uper <b>T</b>ransactional <b>E</b>mails for <b>Woo</b>Commerce</h2>
<div class="stewoo_options_page_main">
<form method="post" action="options.php">
<?php 
// list of customers emails
$mailer = WC()->mailer();
$email_templates = $mailer->get_emails();
settings_fields( 'stewoo-settings-group' );
do_settings_sections( 'stewoo-settings-group' );
?>


<?php 
echo  '<div class="link_to_documentation_hld"><a href="' . add_query_arg( array(
    'page' => 'stewoo_presentation',
), admin_url( 'options-general.php' ) ) . '" id="link_to_documentation"><u><b>' . __( 'Need help?', 'stewoo' ) . '</b></u></a>' ;
if ( !stewoo_fs()->can_use_premium_code() ) {
    echo  '<a href="' . add_query_arg( array(
        'page' => 'stewoo_presentation',
    ), admin_url( 'options-general.php' ) ) . '" id="link_to_documentation">' . __( 'Try STEWoo Pro for free and grow your sales!', 'stewoo' ) . '</a>' ;
}
echo  '</div>' ;
echo  '<table class="form-table">' ;
echo  '<tr valign="top"><th scope="row">' . __( 'Email type to preview', 'stewoo' ) . '</th><td>' ;
echo  "<select id='email_type_selector' name='stewoo_email_type_selector'>" ;
$email_type_selector_option = get_option( 'stewoo_email_type_selector' );
foreach ( $email_templates as $email_key => $email ) {
    if ( $email->is_customer_email() ) {
        echo  sprintf( "<option value='%s'" . selected( $email_type_selector_option, $email_key, false ) . ">%s</option>", $email_key, __( $email->title, 'woocommerce' ) ) ;
    }
}
echo  "</select>" ;
echo  '<small>' . __( 'Just for preview, design applies to all email templates', 'stewoo' ) . '</small>' ;
echo  '</td></tr>' ;
echo  '<tr valign="top"><td colspan="2" style="padding-left:0">' ;
echo  sprintf( "<a href='%s' class='button customize_button' style='margin-top: 10px' id='stewoo_customizer'>%s</a><br>", $this->customizer_url, __( 'Customize Emails', 'stewoo' ) ) ;
echo  '</td></tr>' ;
?>
</table>

<?php 
?>
</form>

<fieldset class="import_export_hld">
<legend><?php 
_e( 'Import / Export', 'stewoo' );
?></legend>

<form action="options.php" method="post" enctype="multipart/form-data" id="import_form">
    <input type="file" name="settings_to_import" class="input_file_selector" accept=".txt" id="input_stewoo_file"/>
    <a href="#" class="button select_file_lnk button-primary"><?php 
_e( 'Import and Apply Customizer Settings', 'stewoo' );
?></a>
    <input type="hidden" name="upload"/>
    <?php 
wp_nonce_field( 'stewoo_import_settings', 'stewoo_import_settings' );
?>
</form>

<a href="<?php 
echo  wp_nonce_url( add_query_arg( array(
    'page'   => 'stewoo',
    'action' => 'stewoo_save_settings',
), admin_url( 'admin.php' ) ) ) ;
?>" class="button export"><?php 
_e( 'Save Current Customizer Settings', 'stewoo' );
?></a>

<?php 
global  $wpdb ;
$options = $wpdb->get_col( "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE 'stewoo\\_export\\_%'" );
$exports = array_map( 'get_option', $options );

if ( !empty($exports) ) {
    echo  '<table id="export_action"><caption>' . __( 'Saved Settings', 'stewoo' ) . '</caption>' ;
    foreach ( $exports as $key => $export ) {
        echo  '<tr><td class="label">' . $export['label'] . '</td>' ;
        echo  '<td class="action"><a href="' . wp_nonce_url( add_query_arg( array(
            'page'   => 'stewoo',
            'action' => 'stewoo_apply_settings',
            'key'    => $options[$key],
        ), admin_url( 'admin.php' ) ) ) . '" class="button apply">' . __( 'Apply', 'stewoo' ) . '</td>' ;
        echo  '<td class="action"><a href="' . wp_nonce_url( add_query_arg( array(
            'page'   => 'stewoo',
            'action' => 'stewoo_download_settings',
            'key'    => $options[$key],
        ), admin_url( 'admin.php' ) ) ) . '" class="button download">' . __( 'Download', 'stewoo' ) . '</td>' ;
        echo  '<td class="action"><a href="' . wp_nonce_url( add_query_arg( array(
            'page'   => 'stewoo',
            'action' => 'stewoo_delete_settings',
            'key'    => $options[$key],
        ), admin_url( 'admin.php' ) ) ) . '" class="button delete">' . __( 'Delete', 'stewoo' ) . '</td>' ;
        echo  '</tr>' ;
    }
    echo  '</table>' ;
}

?>

</fieldset>

</div>
</div>
</div>
