

//Auto add and update Title field from ACF:

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
