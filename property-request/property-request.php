<?php

/*
	Plugin Name: Property Request
  	Description: Use to manage all property request and their details.
  	Version: 1.0
  	License: GPLv2+
  	Text Domain: Property Request

*/

if(is_admin())
{
    new WP_Program_Manager();
}  	

class WP_Program_Manager {

	function __construct()
	{
		add_action( 'admin_menu', array( $this, 'program_add_menu' ));
		register_activation_hook( __FILE__, array( $this, 'program_install' ) );
        register_deactivation_hook( __FILE__, array( $this, 'program_uninstall' ) ); 
    }

	/* add into menu */
	function program_add_menu() {
		add_menu_page( 'Property Request', 'Property Request', 'manage_options', 'manage-propertyrequest', array(__CLASS__,'buysell_request_list_table'),'dashicons-building');
		add_submenu_page( 'manage-propertyrequest', 'Buy List Request', 'Buy List Request', 'manage_options', 'manage-buylistrequest', array(__CLASS__,'buylistrequest_list_table'));
	}

    /* Activation plugin,  create table */
    function program_install() {
    	global $wpdb;
		$wp_buysell_request = 'wp_buysell_request';

		if ( $wpdb->get_var( "SHOW TABLES LIKE '{$wp_buysell_request}'" ) != $wp_buysell_request ) {

			$wp_buysell_request_create = "CREATE TABLE $wp_buysell_request (
			  	`bsr_id` bigint(20) NOT NULL,
				`bsr_first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
				`bsr_last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
				`bsr_owner` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
				`bsr_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
				`bsr_phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
				`bsr_contact_by_email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
				`bsr_contact_by_phone` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
				`bsr_address` text COLLATE utf8_unicode_ci NOT NULL,
				`bsr_city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
				`bsr_state` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
				`bsr_zipcode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
				`bsr_property_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
				`bsr_request_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
				`bsr_mortgage` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
				`bsr_codeviolations` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
				`bsr_information` text COLLATE utf8_unicode_ci NOT NULL,
				`bsr_created_date` datetime NOT NULL,
			  	PRIMARY KEY (`bsr_id`)
			); ";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $wp_buysell_request_create );
		}
    }

    /* Deactive Plugin */
    function wpa_uninstall() {
    }

    /** Display the buysell request table page **/
    public function buysell_request_list_table()
    {
    	$action = ($_GET['action']) ? $_GET['action'] : '';

    	if($action == 'view')
    	{
    		self::view_buysell_request();
    		exit; die();
    	}
    	
        $buysell_requestListTable = new PropertyRequest_List_Table();
        $buysell_requestListTable->prepare_items();
        ?>
            <div class="wrap">
                <h1 class="wp-heading-inline"> Manage Buy/Sell Request</h1> 
                <?php $buysell_requestListTable->display(); ?>
            </div>
        <?php
    }

    /** function for view buysell_request */
    function view_buysell_request()
    { 
    	global $wpdb;
    	$bsr_id = ($_GET['bsr']) ? $_GET['bsr'] : '';
    	$title = 'View Buy/Sell Request Details';

    	$buysell_requestdetails = array();
    	if($bsr_id)
    	{
    		$buysell_requestdetails = $wpdb->get_results( 'SELECT * FROM wp_buysell_request WHERE bsr_id='.$bsr_id);
    		$bsr_created_date = date('d M Y H:i:s',strtotime($buysell_requestdetails[0]->bsr_created_date));
    	}
    	?>
    	<div class="wrap">
    		<h1 class="wp-heading-inline"> <?=  $title ?></h1>
			<table class="form-table">
				<tbody>
					<tr class="form-field">
						<th><label for="bsr_name">Name</label></th>
						<td><input id ="bsr_name" type="text" value="<?= $buysell_requestdetails[0]->bsr_first_name.' '.$buysell_requestdetails[0]->bsr_last_name ?>" readonly></td>
					</tr>
					<tr class="form-field">
						<th><label for="bsr_owner">Property Owner ?</label></th>
						<td><input id ="bsr_owner" type="text" value="<?= $buysell_requestdetails[0]->bsr_owner ?>" readonly></td>
					</tr>
					<tr class="form-field">
						<th><label for="bsr_email">Email</label></th>
						<td><input id ="bsr_email" type="text" value="<?= $buysell_requestdetails[0]->bsr_email ?>" readonly></td>
					</tr>
					<tr class="form-field">
						<th><label for="bsr_contact_by_email">Contact By Email</label></th>
						<td><input id ="bsr_contact_by_email" type="text" value="<?= $buysell_requestdetails[0]->bsr_contact_by_email ?>" readonly></td>
					</tr>
					<tr class="form-field">
						<th><label for="bsr_phone">Phone</label></th>
						<td><input id ="bsr_phone" type="text" value="<?= $buysell_requestdetails[0]->bsr_phone ?>" readonly></td>
					</tr>
					<tr class="form-field">
						<th><label for="bsr_contact_by_phone">Contact By Phone</label></th>
						<td><input id ="bsr_contact_by_phone" type="text" value="<?= $buysell_requestdetails[0]->bsr_contact_by_phone ?>" readonly></td>
					</tr>
					<tr class="form-field">
						<th><label for="bsr_address">Address</label></th>
						<td><input id ="bsr_address" type="text" value="<?= $buysell_requestdetails[0]->bsr_address ?>" readonly></td>
					</tr>
					
					<tr class="form-field">
						<th><label for="bsr_city">City</label></th>
						<td><input id ="bsr_city" type="text" value="<?= $buysell_requestdetails[0]->bsr_city ?>" readonly></td>
					</tr>
					<tr class="form-field">
						<th><label for="bsr_state">State</label></th>
						<td><input id ="bsr_state" type="text" value="<?= $buysell_requestdetails[0]->bsr_state ?>" readonly></td>
					</tr>
					<tr class="form-field">
						<th><label for="bsr_zipcode">Zipcode</label></th>
						<td><input id ="bsr_zipcode" type="text" value="<?= $buysell_requestdetails[0]->bsr_zipcode ?>" readonly></td>
					</tr>
					<tr class="form-field">
						<th><label for="bsr_property_type">Property Type</label></th>
						<td><input id ="bsr_property_type" type="text" value="<?= $buysell_requestdetails[0]->bsr_property_type ?>" readonly></td>
					</tr>
					<tr class="form-field">
						<th><label for="bsr_request_type">Request Type</label></th>
						<td><input id ="bsr_request_type" type="text" value="<?= $buysell_requestdetails[0]->bsr_request_type ?>" readonly></td>
					</tr>
					<tr class="form-field">
						<th><label for="bsr_mortgage">Property have mortgage ?</label></th>
						<td><input id ="bsr_mortgage" type="text" value="<?= $buysell_requestdetails[0]->bsr_mortgage ?>" readonly></td>
					</tr>
					<tr class="form-field">
						<th><label for="bsr_codeviolations">Property have code violations or liens ?</label></th>
						<td><input id ="bsr_codeviolations" type="text" value="<?= $buysell_requestdetails[0]->bsr_codeviolations ?>" readonly></td>
					</tr>

					<tr class="form-field">
						<th><label for="bsr_information">Property Information</label></th>
						<td><textarea id ="bsr_information" readonly><?= $buysell_requestdetails[0]->bsr_information ?></textarea></td>
					</tr>

					<tr class="form-field">
						<th><label for="bsr_created_date">Requested Time</label></th>
						<td><input id ="bsr_created_date" type="text" value="<?= $bsr_created_date ?>" readonly></td>
					</tr>
					
				</tbody>
			</table>
    	</div>
    <?php }

}

// WP_List_Table is not loaded automatically so we need to load it in our application
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/** Create a new program bookings table class that will extend the WP_List_Table */
class PropertyRequest_List_Table extends WP_List_Table
{
	/** Get the table data */
    private function table_data()
    {
        $data = array();
		global $wpdb;
		$buysell_requestresults = $wpdb->get_results( 'SELECT * FROM wp_buysell_request ORDER BY bsr_created_date desc');
		$count = 1;
		foreach($buysell_requestresults as $buysell_requestdata){

    		$data[] = array(
    			'sr_no'   			=> $count,
    			'bsr_id'			=> $buysell_requestdata->bsr_id,
    			'bsr_name'   		=> $buysell_requestdata->bsr_first_name.' '.$buysell_requestdata->bsr_last_name,
    			'bsr_email'   		=> $buysell_requestdata->bsr_email,
    			'bsr_phone'   		=> $buysell_requestdata->bsr_phone,
    			'bsr_city'   		=> $buysell_requestdata->bsr_city,
    			'bsr_state'  		=> $buysell_requestdata->bsr_state,
    			'bsr_request_type'	=> $buysell_requestdata->bsr_request_type,
    			'bsr_created_date'	=> date('d M Y H:i:s',strtotime($buysell_requestdata->bsr_created_date)),
            );
       		$count++;
       	}
        
        return $data;
    }

    /** Override the parent columns method. Defines the columns to use in your listing table */
    public function get_columns()
    {
        $columns = array(
        	'sr_no'        		=> 'No',
            'bsr_name'       	=> 'Name',
            'bsr_email'       	=> 'Email',
            'bsr_phone' 		=> 'Phone',
            'bsr_city' 			=> 'City',
            'bsr_state'			=> 'State',
            'bsr_request_type'	=> 'Req. Type',
            'bsr_created_date'	=> 'Req. Time'
        );
        return $columns;
    }

    /** Prepare the items for the table to process */
    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $data = $this->table_data();
        usort( $data, array( &$this, 'sort_data' ) );

        $perPage = 10;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);

        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );
        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }

    /** Define what data to show on each column of the table */
    public function column_default( $item, $column_name )
    {
        switch( $column_name ) {
            case 'sr_no':
            case 'bsr_name':
            case 'bsr_email':
            case 'bsr_phone':
            case 'bsr_city':
            case 'bsr_state':
            case 'bsr_request_type':
            case 'bsr_created_date':
                return $item[ $column_name ];
            default:
                return print_r( $item, true ) ;
        }
    }
    
    /** Define which columns are hidden */
    public function get_hidden_columns()
    {
        return array();
    }
    /** Define the sortable columns */
    public function get_sortable_columns()
    {
    	$sortable_columns = array(
    		'sr_no'  => array('sr_no',false),
		    'bsr_request_type'  => array('bsr_request_type',false),
		    'bsr_created_date'  => array('bsr_created_date',false),
		    'bsr_city'  => array('bsr_city',false),
		    'bsr_state'  => array('bsr_state',false),
		);
        return $sortable_columns;
    }
    
    
    /** Allows you to sort the data by the variables set in the $_GET */
    private function sort_data( $a, $b )
    {
        $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'sr_no'; //If no sort, default to title
	  	$order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc'; //If no order, default to asc
	  	$result = strnatcmp($a[$orderby], $b[$orderby]); //Determine sort order
	  	return ($order==='asc') ? $result : -$result; //Send final sort direction to usort
    }

    /* display edit - delete action */
    function column_bsr_name($item) {
	  $actions = array(
	            'view'      => sprintf('<a href="?page=%s&action=%s&bsr=%s">View Details</a>',$_REQUEST['page'],'view',$item['bsr_id']),
	        );

	  return sprintf('%1$s %2$s', $item['bsr_name'], $this->row_actions($actions) );
	}
}


?>