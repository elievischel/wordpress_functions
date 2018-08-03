<?php
if ( ! ( is_user_logged_in() || current_user_can('publish_posts') ) ) {
    echo '<p>You must be authenticated</p>';
} else { ?>
<div class="edit-form">Edit this submission</div>
<div class="form-off"><?php
    acf_form(
        array(
            'field_groups' => array( XXXXXXXXXXXXXXXXXXX ), // Used ID of the field groups here.
            'post_title'   => true, // This will show the title filed
            'post_content' => true, // This will show the content field
            'form'         => true,
            'new_post'     => array(
                'post_type'   => 'products',
                'post_status' => 'publish' // You may use other post statuses like draft, private etc.
            ),
            'return'       => '%post_url%',
            'submit_value' => 'Submit product',
        )
    );
    }; ?>
</div>

<script>
    $ = jQuery;
    $(".edit-form").click(function(){
        $(".form-off").toggleClass("form-on");
        $(this).toggleClass("form-on");
    }, function() {
        $(".form-off").toggleClass("form-on");
    });
</script>
<style>
    .form-off {
        display:none;
    }

    .form-on {
        display:block;
    }
    .entry-footer{
        display: none;
    }
</style>
