<?php
/**
 * Main Manager Class for WOO Product Table Plugin.
 * All Important file included here.
 * Set Path and Constant also set WOO_Product_Table Class
 * Already set $_instance, So no need again call
 */
class WOO_Product_Table{
    
    /**
     * It's need for Varification purchase code of CodeCanyon
     *
     * @var type int
     */
    public static $item_id = 20676867;
    
    public static $options_name = 'wpt_codecanyon_purchase_code';

    /**
     * To set Default Value for Woo Product Table, So that, we can set Default Value in Plugin Start and 
     * can get Any were
     *
     * @var Array 
     */
    public static $default = array();
    
    /*
     * List of Path
     * 
     * @since 1.0.0
     * @var array
     */
    protected $paths = array();
    
    /**
     * Set like Constant static array
     * Get this by getPath() method
     * Set this by setConstant() method
     *  
     * @var type array
     */
    private static $constant = array();
    
    public static $shortCode;

    
    /**
     * Only for Admin Section, Collumn Array
     * 
     * @since 1.7
     * @var Array
     */
    public static $columns_array = array();

    
    /**
     * Only for Admin Section, Disable Collumn Array
     * 
     * @since 1.7
     * @var Array
     */
    public static $colums_disable_array = array();

    /**
     * Set Array for Style Form Section Options
     *
     * @var type 
     */
    public static $style_form_options = array();
    
    /**
    * Core singleton class
    * @var self - pattern realization
    */
    private static $_instance;
   
    /**
     * Set Plugin Mode as 1 for Giving Data to UPdate Options
     *
     * @var type Int
     */
    protected static $mode = 1;
   
    /**
     * Get the instane of WOO_Product_Table
     *
     * @return self
     */
    public static function getInstance() {
        if ( ! ( self::$_instance instanceof self ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }   
   
    public function __construct() {

        $dir = dirname( __FILE__ ); //dirname( __FILE__ )
       
        /**
         * See $path_args for Set Path and set Constant
         * 
         * @since 1.0.0
         */
        $path_args = array(
            'PLUGIN_BASE_FOLDER' =>  plugin_basename( $dir ),
            'PLUGIN_BASE_FILE' =>  plugin_basename( __FILE__ ),
            'BASE_URL' =>  plugins_url() . '/'. plugin_basename( $dir ) . '/', //using plugins_url() instead of WP_PLUGIN_URL
            'BASE_DIR' =>  str_replace( '\\', '/', $dir . '/' ),
        );

        /**
         * Set Path Full with Constant as Array
         * 
         * @since 1.0.0
         */
        $this->setPath($path_args);

        /**
         * Set Constant
         * 
         * @since 1.0.0
         */
        $this->setConstant($path_args);
        
        //Load File
        if( is_admin() ){
            require_once $this->path('BASE_DIR','admin/style_js_adding_admin.php');
            require_once $this->path('BASE_DIR','admin/menu_plugin_setting_link.php');
            require_once $this->path('BASE_DIR','admin/wpt_product_table_post.php');
            require_once $this->path('BASE_DIR','admin/configuration_page.php');
            require_once $this->path('BASE_DIR','admin/post_metabox.php');            
            //require_once $this->path('BASE_DIR','admin/fac_support_page.php');
            //require_once $this->path('BASE_DIR','admin/updater.php');
        }
       
        //Load these bellow file, Only woocommerce installed as well as Only for Front-End
        if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
           require_once $this->path('BASE_DIR','includes/style_js_adding.php');
           require_once $this->path('BASE_DIR','includes/functions.php');
           require_once $this->path('BASE_DIR','includes/ajax_add_to_cart.php'); 
           require_once $this->path('BASE_DIR','includes/shortcode.php');
        }else{
           require_once $this->path('BASE_DIR','includes/no_woocommerce.php');
        }
        
    }
    
    /**
     * Set Path
     * 
     * @param type $path_array
     * 
     * @since 1.0.0
     */
    public function setPath( $path_array ) {
        $this->paths = $path_array;
    }
   
    private function setConstant( $contanst_array ) {
        self::$constant = $this->paths;
    }
    
    /**
     * Set Path as like Constant Will Return Full Path
     * Name should like Constant and full Capitalize
     * 
     * @param type $name
     * @return string
     */
    public function path( $name, $_complete_full_file_path = false ) {
        $path = $this->paths[$name] . $_complete_full_file_path;
        return $path;
    }
   
    /**
     * To Get Full path to Anywhere based on Constant
     * 
     * @param type $constant_name
     * @return type String
     */
    public static function getPath( $constant_name = false ) {
        $path = self::$constant[$constant_name];
        return $path;
    }
   
    /**
     * Update Options when Installing
     * This method has update at Version 3.6
     * 
     * @since 1.0.0
     * @updated since 3.6_29.10.2018 d/m/y
     */
    public static function install() {
        ob_start();
        //check current value
        $current_value = get_option('wpt_configure_options');
        $default_value = self::$default;
        $changed_value = false;
        //Set default value in Options
        if($current_value){
            foreach( $default_value as $key=>$value ){
                if( isset($current_value[$key]) && $key != 'plugin_version' ){
                    $changed_value[$key] = $current_value[$key];
                }else{
                    $changed_value[$key] = $value;
                }
            }
            update_option( 'wpt_configure_options', $changed_value );
        }else{
            update_option( 'wpt_configure_options', $default_value );
        }       
    }
   
    /**
     * Plugin Uninsall Activation Hook 
     * Static Method
     * 
     * @since 1.0.0
     */
    public function uninstall() {
       //Nothing for now
    }
   
    /**
     * Getting full Plugin data. We have used __FILE__ for the main plugin file.
     * 
     * @since V 1.5
     * @return Array Returnning Array of full Plugin's data for This Woo Product Table plugin
     */
    public static function getPluginData(){
        return get_plugin_data( __FILE__ );
    }
   
    /**
     * Getting Version by this Function/Method
     * 
     * @return type static String
     */
    public static function getVersion() {
        $data = self::getPluginData();
        return $data['Version'];
    }
   
    /**
     * Getting Version by this Function/Method
     * 
     * @return type static String
     */
    public static function getName() {
        $data = self::getPluginData();
        return $data['Name'];
    }

    public static function getDefault( $indexKey = false ){
        $default = self::$default;
        if( $indexKey && isset( $default[$indexKey] ) ){
            return $default[$indexKey];
        }
        return $default;
    }
}

/**
* Plugin Install and Uninstall
*/
register_activation_hook(__FILE__, array( 'WOO_Product_Table','install' ) );
register_deactivation_hook( __FILE__, array( 'WOO_Product_Table','uninstall' ) );
