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
		add_action( 'init', array( $this, 'remove_roles' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_my_scripts' ) );
		add_action( 'login_enqueue_scripts', array($this, 'enqueue_my_scripts'));
		add_filter( 'login_headerurl', array($this, 'tyan_login_url'));
		parent::__construct();
	}

	// Create the roles when the theme is applied for the first time
	function create_roles(){
		add_role('singer', 'Laulja');
		add_role('bookie', 'Raamatupidaja');
		add_role('conductor', 'Koorivanem');
		add_role('president', 'President');
		add_role('secretary', 'Sekretär');
		add_role('note-handler', 'Noodihaldur');
		$bookie  = get_role('bookie');
		$singer = get_role('singer');
		$conductor = get_role('conductor');
		$choirManager = get_role('president');
		$secretary = get_role('secretary');
		$noteHandler = get_role('note-handler');
		// Same capabilities as a subscriber
		$bookie -> add_cap('read');
		$singer -> add_cap('read');
		$conductor -> add_cap('read');
		$choirManager -> add_cap('read');
		$secretary -> add_cap('read');
		$noteHandler -> add_cap('read');
	}
	// Delete roles we do not use
	function remove_roles(){
		remove_role( 'subscriber' );
		remove_role( 'contributor' );
		remove_role( 'author' );
		remove_role( 'editor' );
		remove_role( 'choirManager' );
	}
	/** Register custom post types defined in lib/custom-types */
	public function register_post_types()
	{
		require('lib/custom-types.php');
	}

	/** This is where you can register custom taxonomies. */
	public function register_taxonomies()
	{
	}

	/** Enqueue scripts defined in lib/enqueue-scripts */
	public function enqueue_my_scripts()
	{
		require('lib/enqueue-scripts.php');
	}

	/** Change login page logo url */
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
		require('lib/theme-supports.php');
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
