<?php
/*
Plugin Name:   Twitter Bootstrap Toolkit
Description:   Easy embed Twitter Bootstrap toolkit.
Version:       0.3.1
Author:        Hassan Derakhshandeh

		This program is free software; you can redistribute it and/or modify
		it under the terms of the GNU General Public License as published by
		the Free Software Foundation; either version 2 of the License, or
		(at your option) any later version.

		This program is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
		GNU General Public License for more details.

		You should have received a copy of the GNU General Public License
		along with this program; if not, write to the Free Software
		Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class Bootstrap_Toolkit {

	private static $instance = null;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @return	A single instance of this class.
	 */
	public function get_instance() {
		if( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
    }

	function __construct() {
		if( is_admin() ) {
			add_action( 'admin_init', array( &$this, 'register_settings' ) );
			add_action( 'admin_menu', array( &$this, 'admin' ) );
		} else {
			add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_bootstrap' ), 1 );
		}
	}

	function admin() {
		$options_page = add_theme_page(
			__( 'Bootstrap Options', 'bootstrap-toolkit' ),
			__( 'Bootstrap', 'bootstrap-toolkit' ),
			'edit_theme_options',
			'bootstrap-options',
			array( &$this, 'options_page' )
		);
		add_action( "admin_print_styles-{$options_page}", array( &$this, 'admin_queue' ) );
	}

	/**
	 * Compiles Bootstrap LESS codes and save the result in database.
	 *
	 * @return mixed
	 */
	function actions( $options ) {
		global $wp_filesystem;

		$uploads = wp_upload_dir();
		if( ! empty( $options['less'] ) ) {
			require_once( plugin_dir_path(__FILE__) . 'lib/lessphp/lessc.inc.php' );
			$lessphp = new lessc;

			$lessphp->setVariables( wp_parse_args( $options['vars'], $this->default_bootstrap_variables() ) );
			$less = file_get_contents( dirname( __FILE__ ) . '/lib/bootstrap/less/mixins.less' );
			foreach( $options['less'] as $raw_less ) {
				$less .= file_get_contents( dirname( __FILE__ ) . '/lib/bootstrap/less/' . $raw_less .'.less' );
			}

			/* compile LESS and minify the result */
			$css = $this->minify_css( $lessphp->parse( $less ) );

			WP_Filesystem();
			$wp_filesystem->put_contents( trailingslashit( $uploads['basedir'] ) . 'bootstrap.css', $css, 0644 );
		} else {
			@unlink( trailingslashit( $uploads['basedir'] ) . 'bootstrap.css' );
		}

		return $options;
	}

	/**
	 * Removes white-spaces and comments to minify the $css.
	 *
	 * Props to Karthik Viswanathan
	 * @link http://www.lateralcode.com/css-minifier/
	 *
	 * @return string $css
	 */
	function minify_css( $css ) {
		$css = preg_replace( '#\s+#', ' ', $css );
		$css = preg_replace( '#/\*.*?\*/#s', '', $css );
		$css = str_replace( '; ', ';', $css );
		$css = str_replace( ': ', ':', $css );
		$css = str_replace( ' {', '{', $css );
		$css = str_replace( '{ ', '{', $css );
		$css = str_replace( ', ', ',', $css );
		$css = str_replace( '} ', '}', $css );
		$css = str_replace( ';}', '}', $css );

		return trim( $css );
	}

	function options_page() {
		$options = get_option( 'bootstrap', array() );
		$options['vars'] = wp_parse_args( $options['vars'], $this->default_bootstrap_variables() );
		include trailingslashit( dirname( __FILE__ ) ) . 'views/admin.php';
	}

	function register_settings() {
		register_setting( 'bootstrap_settings', 'bootstrap', array( &$this, 'actions' ) );
	}

	function admin_queue() {
		wp_enqueue_script( 'bootstrap-admin', plugins_url( 'assets/admin.js', __FILE__ ), array( 'jquery' ) );
		wp_enqueue_style( 'bootstrap-admin', plugins_url( 'assets/admin.css', __FILE__ ) );
	}

	function enqueue_bootstrap() {
		$options = get_option( 'bootstrap', array() );
		$uploads = wp_upload_dir();
		if( isset( $options['js'] ) ) : foreach( $options['js'] as $script ) :
			wp_enqueue_script( $script, plugins_url( "lib/bootstrap/js/{$script}.js", __FILE__ ), array( 'jquery' ), '2.3.1', true );
		endforeach; endif;

		if( ! empty( $options['less'] ) && file_exists( trailingslashit( $uploads['basedir'] ) . 'bootstrap.css' ) ) {
			wp_enqueue_style( 'bootstrap', trailingslashit( $uploads['baseurl'] ) . 'bootstrap.css' );
		}
	}

	function _build_checkbox( $name, $label, $value, $option_group ) {
		?><label class="checkbox"><input name="bootstrap[<?php echo $option_group; ?>][<?php echo $name; ?>]" <?php checked( $name, $value ); ?> type="checkbox" value="<?php echo $name; ?>"> <?php echo $label; ?></label><?php
	}

	function default_bootstrap_variables() {
		return array(
			'black' => '#000', 'grayDarker' => '#222', 'grayDark' => '#333', 'gray' => '#555', 'grayLight' => '#999', 'grayLighter' => '#eee', 'white' => '#fff', 'blue' => '#049cdb', 'blueDark' => '#0064cd', 'green' => '#46a546', 'red' => '#9d261d', 'yellow' => '#ffc40d', 'orange' => '#f89406', 'pink' => '#c3325f', 'purple' => '#7a43b6', 'bodyBackground' => '@white', 'textColor' => '@grayDark', 'linkColor' => '#08c', 'linkColorHover' => 'darken(@linkColor, 15%)', 'sansFontFamily' => '"Helvetica Neue", Helvetica, Arial, sans-serif', 'serifFontFamily' => 'Georgia, "Times New Roman", Times, serif', 'monoFontFamily' => 'Monaco, Menlo, Consolas, "Courier New", monospace', 'baseFontSize' => '14px', 'baseFontFamily' => '@sansFontFamily', 'baseLineHeight' => '20px', 'altFontFamily' => '@serifFontFamily', 'headingsFontFamily' => 'inherit', 'headingsFontWeight' => 'bold', 'headingsColor' => 'inherit', 'fontSizeLarge' => '@baseFontSize * 1.25', 'fontSizeSmall' => '@baseFontSize * 0.85', 'fontSizeMini' => '@baseFontSize * 0.75', 'paddingLarge' => '11px 19px', 'paddingSmall' => '2px 10px', 'paddingMini' => '0 6px', 'baseBorderRadius' => '4px', 'borderRadiusLarge' => '6px', 'borderRadiusSmall' => '3px', 'tableBackground' => 'transparent', 'tableBackgroundAccent' => '#f9f9f9', 'tableBackgroundHover' => '#f5f5f5', 'tableBorder' => '#ddd', 'btnBackground' => '@white', 'btnBackgroundHighlight' => 'darken(@white, 10%)', 'btnBorder' => '#ccc', 'btnPrimaryBackground' => '@linkColor', 'btnPrimaryBackgroundHighlight' => 'spin(@btnPrimaryBackground, 20%)', 'btnInfoBackground' => '#5bc0de', 'btnInfoBackgroundHighlight' => '#2f96b4', 'btnSuccessBackground' => '#62c462', 'btnSuccessBackgroundHighlight' => '#51a351', 'btnWarningBackground' => 'lighten(@orange, 15%)', 'btnWarningBackgroundHighlight' => '@orange', 'btnDangerBackground' => '#ee5f5b', 'btnDangerBackgroundHighlight' => '#bd362f', 'btnInverseBackground' => '#444', 'btnInverseBackgroundHighlight' => '@grayDarker', 'inputBackground' => '@white', 'inputBorder' => '#ccc', 'inputBorderRadius' => '@baseBorderRadius', 'inputDisabledBackground' => '@grayLighter', 'formActionsBackground' => '#f5f5f5', 'inputHeight' => '@baseLineHeight + 10px', 'dropdownBackground' => '@white', 'dropdownBorder' => 'rgba(0,0,0,.2)', 'dropdownDividerTop' => '#e5e5e5', 'dropdownDividerBottom' => '@white', 'dropdownLinkColor' => '@grayDark', 'dropdownLinkColorHover' => '@white', 'dropdownLinkColorActive' => '@white', 'dropdownLinkBackgroundActive' => '@linkColor', 'dropdownLinkBackgroundHover' => '@dropdownLinkBackgroundActive', 'zindexDropdown' => 1000, 'zindexPopover' => 1010, 'zindexTooltip' => 1030, 'zindexFixedNavbar' => 1030, 'zindexModalBackdrop' => 1040, 'zindexModal' => 1050, 'iconSpritePath' => '"../img/glyphicons-halflings.png"', 'iconWhiteSpritePath' => '"../img/glyphicons-halflings-white.png"', 'placeholderText' => '@grayLight', 'hrBorder' => '@grayLighter', 'horizontalComponentOffset' => '180px', 'wellBackground' => '#f5f5f5', 'navbarCollapseWidth' => '979px', 'navbarCollapseDesktopWidth' => '@navbarCollapseWidth + 1', 'navbarHeight' => '40px', 'navbarBackgroundHighlight' => '#ffffff', 'navbarBackground' => 'darken(@navbarBackgroundHighlight, 5%)', 'navbarBorder' => 'darken(@navbarBackground, 12%)', 'navbarText' => '#777', 'navbarLinkColor' => '#777', 'navbarLinkColorHover' => '@grayDark', 'navbarLinkColorActive' => '@gray', 'navbarLinkBackgroundHover' => 'transparent', 'navbarLinkBackgroundActive' => 'darken(@navbarBackground, 5%)', 'navbarBrandColor' => '@navbarLinkColor', 'navbarInverseBackground' => '#111111', 'navbarInverseBackgroundHighlight' => '#222222', 'navbarInverseBorder' => '#252525', 'navbarInverseText' => '@grayLight', 'navbarInverseLinkColor' => '@grayLight', 'navbarInverseLinkColorHover' => '@white', 'navbarInverseLinkColorActive' => '@navbarInverseLinkColorHover', 'navbarInverseLinkBackgroundHover' => 'transparent', 'navbarInverseLinkBackgroundActive' => '@navbarInverseBackground', 'navbarInverseSearchBackground' => 'lighten(@navbarInverseBackground, 25%)', 'navbarInverseSearchBackgroundFocus' => '@white', 'navbarInverseSearchBorder' => '@navbarInverseBackground', 'navbarInverseSearchPlaceholderColor' => '#ccc', 'navbarInverseBrandColor' => '@navbarInverseLinkColor', 'paginationBackground' => '#fff', 'paginationBorder' => '#ddd', 'paginationActiveBackground' => '#f5f5f5', 'heroUnitBackground' => '@grayLighter', 'heroUnitHeadingColor' => 'inherit', 'heroUnitLeadColor' => 'inherit', 'warningText' => '#c09853', 'warningBackground' => '#fcf8e3', 'warningBorder' => 'darken(spin(@warningBackground, -10), 3%)', 'errorText' => '#b94a48', 'errorBackground' => '#f2dede', 'errorBorder' => 'darken(spin(@errorBackground, -10), 3%)', 'successText' => '#468847', 'successBackground' => '#dff0d8', 'successBorder' => 'darken(spin(@successBackground, -10), 5%)', 'infoText' => '#3a87ad', 'infoBackground' => '#d9edf7', 'infoBorder' => 'darken(spin(@infoBackground, -10), 7%)', 'tooltipColor' => '#fff', 'tooltipBackground' => '#000', 'tooltipArrowWidth' => '5px', 'tooltipArrowColor' => '@tooltipBackground', 'popoverBackground' => '#fff', 'popoverArrowWidth' => '10px', 'popoverArrowColor' => '#fff', 'popoverTitleBackground' => 'darken(@popoverBackground, 3%)', 'popoverArrowOuterWidth' => '@popoverArrowWidth + 1', 'popoverArrowOuterColor' => 'rgba(0,0,0,.25)', 'gridColumns' => 12, 'gridColumnWidth' => '60px', 'gridGutterWidth' => '20px', 'gridRowWidth' => '(@gridColumns * @gridColumnWidth) + (@gridGutterWidth * (@gridColumns - 1))', 'gridColumnWidth1200' => '70px', 'gridGutterWidth1200' => '30px', 'gridRowWidth1200' => '(@gridColumns * @gridColumnWidth1200) + (@gridGutterWidth1200 * (@gridColumns - 1))', 'gridColumnWidth768' => '42px', 'gridGutterWidth768' => '20px', 'gridRowWidth768' => '(@gridColumns * @gridColumnWidth768) + (@gridGutterWidth768 * (@gridColumns - 1))', 'fluidGridColumnWidth' => 'percentage(@gridColumnWidth/@gridRowWidth)', 'fluidGridGutterWidth' => 'percentage(@gridGutterWidth/@gridRowWidth)', 'fluidGridColumnWidth1200' => 'percentage(@gridColumnWidth1200/@gridRowWidth1200)', 'fluidGridGutterWidth1200' => 'percentage(@gridGutterWidth1200/@gridRowWidth1200)', 'fluidGridColumnWidth768' => 'percentage(@gridColumnWidth768/@gridRowWidth768)', 'fluidGridGutterWidth768' => 'percentage(@gridGutterWidth768/@gridRowWidth768)'
		);
	}
}
Bootstrap_Toolkit::get_instance();