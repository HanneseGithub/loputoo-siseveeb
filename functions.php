<?php

/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

/**
 * If you are installing Timber as a Composer dependency in your theme, you'll need this block
 * to load your dependencies and initialize Timber. If you are using Timber via the WordPress.org
 * plug-in, you can safely delete this block.
 */
$composer_autoload = __DIR__ . '/vendor/autoload.php';
if (file_exists($composer_autoload)) {
	require_once $composer_autoload;
	$timber = new Timber\Timber();
}

/**
 * This ensures that Timber is loaded and available as a PHP class.
 * If not, it gives an error message to help direct developers on where to activate
 */
if (!class_exists('Timber')) {

	add_action(
		'admin_notices',
		function () {
			echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url(admin_url('plugins.php#timber')) . '">' . esc_url(admin_url('plugins.php')) . '</a></p></div>';
		}
	);

	add_filter(
		'template_include',
		function ($template) {
			return get_stylesheet_directory() . '/static/no-timber.html';
		}
	);
	return;
}

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array('templates', 'views');

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;


/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class StarterSite extends Timber\Site
{
	/** Add timber support. */

	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
		add_filter( 'timber/context', array( $this, 'add_to_context' ) );
		add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'create_roles' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_my_scripts' ) );
		add_filter('login_headerurl', array($this, 'tyan_login_url'));
		parent::__construct();
	}

	// Create the roles when the theme is applied for the first time

	function create_roles(){
		add_role('singer', 'Laulja');
		add_role('bookie', 'Raamatupidaja');
		add_role('conductor', 'Koorivanem');
		add_role('choirManager', 'Koori juht');
		add_role('secretary', 'Sekretär');
		add_role('Note-handler', 'Noodihaldur');
		$bookie  = get_role('bookie');
		$singer = get_role('singer');
		$conductor = get_role('conductor');
		$choirManager = get_role('choirManager');
		$secretary = get_role('secretary');
		$noteHandler = get_role('Note-handler');
		// Same capabilities as a subscriber
		$bookie -> add_cap('read');
		$singer -> add_cap('read');
		$conductor -> add_cap('read'); 
		$choirManager -> add_cap('read'); 
		$secretary -> add_cap('read');
		$noteHandler -> add_cap('read');
	}


	/** This is where you can register custom post types. */
	public function register_post_types()
	{
	}
	/** This is where you can register custom taxonomies. */
	public function register_taxonomies()
	{
	}

	// Enqueue scripts
	public function enqueue_my_scripts()
	{
		// Use jQuery
		wp_enqueue_script('jquery');
		// Use bootstrap-table css and js
		wp_enqueue_style( 'bootstrap-table-style', get_template_directory_uri() . '/src/includes/bootstrap-table.min.css');
		wp_enqueue_script( 'bootstrap-table-js', get_template_directory_uri() . '/src/includes/bootstrap-table.min.js');
		// Use jquery.modal
		wp_enqueue_style( 'jquery-modal-style', get_template_directory_uri() . '/src/includes/jquery.modal.min.css');
		wp_enqueue_script( 'jquery-modal-js', get_template_directory_uri() . '/src/includes/jquery.modal.min.js');

		// Enqueue our stylesheet and JS file with a jQuery dependency.
		wp_enqueue_style('my-styles', get_template_directory_uri() . '/static/css/main.css', 1.0);
		wp_enqueue_script('my-js', get_template_directory_uri() . '/static/js/main.js', array('jquery'), '1.0.0', true);
	}

	/* This is where login page logo url is changed */
	public function tyan_login_url($url)
	{
		return 'https://naiskoor.ee';
	}

	/** This is where you add some context
	 *
	 * @param string $context context['this'] Being the Twig's {{ this }}.
	 */
	public function add_to_context($context)
	{
		$context['foo']   = 'bar';
		$context['stuff'] = 'I am a value set in your functions.php file';
		$context['notes'] = 'These values are available everytime you call Timber::context();';
		$context['current_user'] = new Timber\User();
		$context['custom_logo_url'] = wp_get_attachment_image_url( get_theme_mod('custom_logo'), 'full');
		$context['menu']  = new Timber\Menu();
		$context['site']  = $this;
		return $context;
	}

	public function theme_supports()
	{
		// Add default posts and comments RSS feed links to head.
		add_theme_support('automatic-feed-links');

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support('title-tag');

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support('post-thumbnails');

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		/*
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support(
			'post-formats',
			array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'audio',
			)
		);

		add_theme_support('menus');

		add_theme_support(
			'custom-logo',
			array(
				'height'               => 216,
				'width'                => 676,
				'flex-height'          => false,
				'flex-width'           => false,
				'unlink-homepage-logo' => true,
			)
		);
	}

	/** This Would return 'foo bar!'.
	 *
	 * @param string $text being 'foo', then returned 'foo bar!'.
	 */
	public function myfoo($text)
	{
		$text .= ' bar!';
		return $text;
	}

	/** This is where you can add your own functions to twig.
	 *
	 * @param string $twig get extension.
	 */
	public function add_to_twig($twig)
	{
		$twig->addExtension(new Twig\Extension\StringLoaderExtension());
		$twig->addFilter(new Twig\TwigFilter('myfoo', array($this, 'myfoo')));
		return $twig;
	}
}

new StarterSite();
