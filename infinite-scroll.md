install JETPACK



/**
 * Add theme support for infinite scroll.
 *
 * @uses add_theme_support
 * @return void
 */
function my_child_setup() {
    add_theme_support( 'infinite-scroll', array(
        'container'      => 'loop-content',
        'render'         => 'infinite_scroll_render',
        'type'           => 'scroll',
        'footer_widgets' => false,
        'footer' => false,
        'wrapper' => false,
    ) );
}

function infinite_scroll_render() {
    while ( have_posts() ) {
        the_post();
        get_template_part( 'template-parts/content', 'looparticle' );
    }
}
add_action( 'after_setup_theme', 'my_child_setup' );

