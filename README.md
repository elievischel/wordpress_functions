## If the title should be named after ACF fields, it will add and update the field title from the ACF
Auto add and update Title field from ACF:

	function my_post_title_updater( $post_id ) {
		$my_post = array();
		$my_post['ID'] = $post_id;
		
		$manufacturer = get_field('manufacturer');
		$target_product = get_field('target_product');
		
		$manufacturer_target = get_field('manufacturer', $target_product);

		if ( get_post_type() == 'manufacturer' ) {
			$my_post['post_title'] = get_field('manufacturer_name');
		} elseif ( get_post_type() == 'products' ) {
			$my_post['post_title'] = get_field('kitName') . ' (' . get_field('manufacturer_name', $manufacturer->ID) . ' ' . get_field('kitNumber') . ')';
		}

		// Update the post into the database
		wp_update_post( $my_post );
	}
	
	// run after ACF saves the $_POST['fields'] data
	add_action('acf/save_post', 'my_post_title_updater', 20);


## Pour afficher Google Maps avec les ACFs : 

	function my_acf_init() {
	    acf_update_setting('google_api_key', 'XXXXXXXXXXXXXXXXXXXXXXXXXXX');
	}
	add_action('acf/init', 'my_acf_init');


## Creating a bidirectional relationship between posts using only the Advanced Custom Fields plugin.

	function bidirectional_acf_update_value( $value, $post_id, $field  ) {

	    // vars
	    $field_name = $field['name'];
	    $field_key = $field['key'];
	    $global_name = 'is_updating_' . $field_name;


	    // bail early if this filter was triggered from the update_field() function called within the loop below
	    // - this prevents an inifinte loop
	    if( !empty($GLOBALS[ $global_name ]) ) return $value;


	    // set global variable to avoid inifite loop
	    // - could also remove_filter() then add_filter() again, but this is simpler
	    $GLOBALS[ $global_name ] = 1;


	    // loop over selected posts and add this $post_id
	    if( is_array($value) ) {

		foreach( $value as $post_id2 ) {

		    // load existing related posts
		    $value2 = get_field($field_name, $post_id2, false);


		    // allow for selected posts to not contain a value
		    if( empty($value2) ) {

			$value2 = array();

		    }


		    // bail early if the current $post_id is already found in selected post's $value2
		    if( in_array($post_id, $value2) ) continue;


		    // append the current $post_id to the selected post's 'linked_to' value
		    $value2[] = $post_id;


		    // update the selected post's value (use field's key for performance)
		    update_field($field_key, $value2, $post_id2);

		}

	    }


	    // find posts which have been removed
	    $old_value = get_field($field_name, $post_id, false);

	    if( is_array($old_value) ) {

		foreach( $old_value as $post_id2 ) {

		    // bail early if this value has not been removed
		    if( is_array($value) && in_array($post_id2, $value) ) continue;


		    // load existing related posts
		    $value2 = get_field($field_name, $post_id2, false);


		    // bail early if no value
		    if( empty($value2) ) continue;


		    // find the position of $post_id within $value2 so we can remove it
		    $pos = array_search($post_id, $value2);


		    // remove
		    unset( $value2[ $pos] );


		    // update the un-selected post's value (use field's key for performance)
		    update_field($field_key, $value2, $post_id2);

		}

	    }


	    // reset global varibale to allow this filter to function as per normal
	    $GLOBALS[ $global_name ] = 0;


	    // return
	    return $value;

	}
	add_filter('acf/update_value/name=putherethenameoftheACF', 'bidirectional_acf_update_value', 10, 3);
