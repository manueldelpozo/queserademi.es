<?php
$bootstrap_less_includes = array(
	'scaffolding' => array( 'reset' => 'Normalize and reset', 'scaffolding' => 'Body type and links', 'grid' => 'Grid system', 'layouts' => 'Layouts' ),
	'basecss' => array( 'type' => 'Headings, body, etc', 'code' => 'Code and pre', 'labels-badges' => 'Labels and badges', 'tables' => 'Tables', 'forms' => 'Forms', 'buttons' => 'Buttons', 'sprites' => 'Icons' ),
	'components' => array( 'button-groups' => 'Button groups and dropdowns', 'navs' => 'Navs, tabs, and pills', 'navbar' => 'Navbar', 'breadcrumbs' => 'Breadcrumbs', 'pagination' => 'Pagination', 'pager' => 'Pager', 'thumbnails' => 'Thumbnails', 'alerts' => 'Alerts', 'progress-bars' => 'Progress bars', 'hero-unit' => 'Hero unit', 'media' => 'Media component' ),
	'jscomponents' => array( 'tooltip' => 'Tooltips', 'popovers' => 'Popovers', 'modals' => 'Modals', 'dropdowns' => 'Dropdowns', 'accordion' => 'Collapse', 'carousel' => 'Carousel' ),
	'misc' => array( 'wells' => 'Wells', 'close' => 'Close icon', 'utilities' => 'Utilities', 'component-animations' => 'Component animations' ),
	'responsive' => array( 'responsive-utilities' => 'Visible/hidden classes', 'responsive-767px-max' => 'Narrow tablets and below (<767px)', 'responsive-768px-979px' => 'Tablets to desktops (767-979px)', 'responsive-1200px-min' => 'Large desktops (>1200px)', 'responsive-navbar' => 'Responsive navbar' )
	);
$bootstrap_js_includes = array(
	'1' => array( 'bootstrap-transition' => 'Transitions <small>(required for any animation)</small>', 'bootstrap-modal' => 'Modals', 'bootstrap-dropdown' => 'Dropdowns', 'bootstrap-scrollspy' => 'Scrollspy', 'bootstrap-tab' => 'Togglable tabs', 'bootstrap-tooltip' =>'Tooltips', 'bootstrap-popover' => 'Popovers <small>(requires Tooltips)</small>' ),
	'2' => array( 'bootstrap-affix' => 'Affix', 'bootstrap-alert' => 'Alert messages', 'bootstrap-button' => 'Buttons', 'bootstrap-collapse' => 'Collapse', 'bootstrap-carousel' => 'Carousel', 'bootstrap-typeahead' => 'Typeahead' )
);

?><div class="wrap">
<?php screen_icon( 'themes' ) ?><h2><?php _e( 'Bootstrap Options', 'bootstrap-toolkit' ) ?></h2>
<form method="post" action="options.php">

<?php settings_fields( 'bootstrap_settings' ); ?>
<?php settings_errors(); ?>

<section class="download" id="components">
	<div class="page-header">
	  <!--<a class="button pull-right toggle-all" href="#">Toggle all</a>-->
	  <h1>
		1. Choose components
	  </h1>
	</div>
	<div class="row download-builder">
	  <div class="span3">
		<h3>Scaffolding</h3>
		<?php foreach( $bootstrap_less_includes['scaffolding'] as $option => $label ) $this->_build_checkbox( $option, $label, $options['less'][$option], 'less' ); ?>
		<h3>Base CSS</h3>
		<?php foreach( $bootstrap_less_includes['basecss'] as $option => $label ) $this->_build_checkbox( $option, $label, $options['less'][$option], 'less' ); ?>
	  </div><!-- /span -->
	  <div class="span3">
		<h3>Components</h3>
		<?php foreach( $bootstrap_less_includes['components'] as $option => $label ) $this->_build_checkbox( $option, $label, $options['less'][$option], 'less' ); ?>
		<h3>JS Components</h3>
		<?php foreach( $bootstrap_less_includes['jscomponents'] as $option => $label ) $this->_build_checkbox( $option, $label, $options['less'][$option], 'less' ); ?>
	  </div><!-- /span -->
	  <div class="span3">
		<h3>Miscellaneous</h3>
		<?php foreach( $bootstrap_less_includes['misc'] as $option => $label ) $this->_build_checkbox( $option, $label, $options['less'][$option], 'less' ); ?>
		<h3>Responsive</h3>
		<?php foreach( $bootstrap_less_includes['responsive'] as $option => $label ) $this->_build_checkbox( $option, $label, $options['less'][$option], 'less' ); ?>
	  </div><!-- /span -->
	</div><!-- /row -->
</section>

<section class="download" id="plugins">
	<div class="page-header">
	  <!--<a class="button pull-right toggle-all" href="#">Toggle all</a>-->
	  <h1>
		2. Select jQuery plugins
	  </h1>
	</div>
	<div class="row download-builder">
	  <div class="span3">
		<?php foreach( $bootstrap_js_includes['1'] as $option => $label ) $this->_build_checkbox( $option, $label, $options['js'][$option], 'js' ); ?>
	  </div><!-- /span -->
	  <div class="span3">
		<?php foreach( $bootstrap_js_includes['2'] as $option => $label ) $this->_build_checkbox( $option, $label, $options['js'][$option], 'js' ); ?>
	  </div><!-- /span -->
	  <div class="span3">
	  </div><!-- /span -->
	</div><!-- /row -->
</section>


<section class="download" id="variables">
	<div class="page-header">
	  <!--<a class="button pull-right toggle-all" href="#">Reset to defaults</a>-->
	  <h1>
		3. Customize variables
	  </h1>
	</div>
	<div class="row download-builder">
	  <div class="span3">
		<h3>Scaffolding</h3>
		<label>@bodyBackground</label>
		<input type="text" name="bootstrap[vars][bodyBackground]" class="span3" value="<?php echo esc_attr( $options['vars']['bodyBackground'] ); ?>">
		<label>@textColor</label>
		<input type="text" name="bootstrap[vars][textColor]" class="span3" value="<?php echo esc_attr( $options['vars']['textColor'] ); ?>">

		<h3>Links</h3>
		<label>@linkColor</label>
		<input type="text" name="bootstrap[vars][linkColor]" class="span3" value="<?php echo esc_attr( $options['vars']['linkColor'] ); ?>">
		<label>@linkColorHover</label>
		<input type="text" name="bootstrap[vars][linkColorHover]" class="span3" value="<?php echo esc_attr( $options['vars']['linkColorHover'] ); ?>">
		<h3>Colors</h3>
		<label>@blue</label>
		<input type="text" name="bootstrap[vars][blue]" class="span3" value="<?php echo esc_attr( $options['vars']['blue'] ); ?>">
		<label>@green</label>
		<input type="text" name="bootstrap[vars][green]" class="span3" value="<?php echo esc_attr( $options['vars']['green'] ); ?>">
		<label>@red</label>
		<input type="text" name="bootstrap[vars][red]" class="span3" value="<?php echo esc_attr( $options['vars']['red'] ); ?>">
		<label>@yellow</label>
		<input type="text" name="bootstrap[vars][yellow]" class="span3" value="<?php echo esc_attr( $options['vars']['yellow'] ); ?>">
		<label>@orange</label>
		<input type="text" name="bootstrap[vars][orange]" class="span3" value="<?php echo esc_attr( $options['vars']['orange'] ); ?>">
		<label>@pink</label>
		<input type="text" name="bootstrap[vars][pink]" class="span3" value="<?php echo esc_attr( $options['vars']['pink'] ); ?>">
		<label>@purple</label>
		<input type="text" name="bootstrap[vars][purple]" class="span3" value="<?php echo esc_attr( $options['vars']['purple'] ); ?>">

		<h3>Sprites</h3>
		<label>@iconSpritePath</label>
		<input type="text" name="bootstrap[vars][iconSpritePath]" class="span3" value="<?php echo esc_attr( $options['vars']['iconSpritePath'] ); ?>">
		<label>@iconWhiteSpritePath</label>
		<input type="text" name="bootstrap[vars][iconWhiteSpritePath]" class="span3" value="<?php echo esc_attr( $options['vars']['iconWhiteSpritePath'] ); ?>">

		<h3>Grid system</h3>
		<label>@gridColumns</label>
		<input type="text" name="bootstrap[vars][gridColumns]" class="span3" value="<?php echo esc_attr( $options['vars']['gridColumns'] ); ?>">
		<label>@gridColumnWidth</label>
		<input type="text" name="bootstrap[vars][gridColumnWidth]" class="span3" value="<?php echo esc_attr( $options['vars']['gridColumnWidth'] ); ?>">
		<label>@gridGutterWidth</label>
		<input type="text" name="bootstrap[vars][gridGutterWidth]" class="span3" value="<?php echo esc_attr( $options['vars']['gridGutterWidth'] ); ?>">
		<label>@gridColumnWidth1200</label>
		<input type="text" name="bootstrap[vars][gridColumnWidth1200]" class="span3" value="<?php echo esc_attr( $options['vars']['gridColumnWidth1200'] ); ?>">
		<label>@gridGutterWidth1200</label>
		<input type="text" name="bootstrap[vars][gridGutterWidth1200]" class="span3" value="<?php echo esc_attr( $options['vars']['gridGutterWidth1200'] ); ?>">
		<label>@gridColumnWidth768</label>
		<input type="text" name="bootstrap[vars][gridColumnWidth768]" class="span3" value="<?php echo esc_attr( $options['vars']['gridColumnWidth768'] ); ?>">
		<label>@gridGutterWidth768</label>
		<input type="text" name="bootstrap[vars][gridGutterWidth768]" class="span3" value="<?php echo esc_attr( $options['vars']['gridGutterWidth768'] ); ?>">

	  </div><!-- /span -->
	  <div class="span3">

		<h3>Typography</h3>
		<label>@sansFontFamily</label>
		<input type="text" name="bootstrap[vars][sansFontFamily]" class="span3" value="<?php echo esc_attr( $options['vars']['sansFontFamily'] ); ?>">
		<label>@serifFontFamily</label>
		<input type="text" name="bootstrap[vars][serifFontFamily]" class="span3" value="<?php echo esc_attr( $options['vars']['serifFontFamily'] ); ?>">
		<label>@monoFontFamily</label>
		<input type="text" name="bootstrap[vars][monoFontFamily]" class="span3" value="<?php echo esc_attr( $options['vars']['monoFontFamily'] ); ?>">

		<label>@baseFontSize</label>
		<input type="text" name="bootstrap[vars][baseFontSize]" class="span3" value="<?php echo esc_attr( $options['vars']['baseFontSize'] ); ?>">
		<label>@baseFontFamily</label>
		<input type="text" name="bootstrap[vars][baseFontFamily]" class="span3" value="<?php echo esc_attr( $options['vars']['baseFontFamily'] ); ?>">
		<label>@baseLineHeight</label>
		<input type="text" name="bootstrap[vars][baseLineHeight]" class="span3" value="<?php echo esc_attr( $options['vars']['baseLineHeight'] ); ?>">

		<label>@altFontFamily</label>
		<input type="text" name="bootstrap[vars][altFontFamily]" class="span3" value="<?php echo esc_attr( $options['vars']['altFontFamily'] ); ?>">
		<label>@headingsFontFamily</label>
		<input type="text" name="bootstrap[vars][headingsFontFamily]" class="span3" value="<?php echo esc_attr( $options['vars']['headingsFontFamily'] ); ?>">
		<label>@headingsFontWeight</label>
		<input type="text" name="bootstrap[vars][headingsFontWeight]" class="span3" value="<?php echo esc_attr( $options['vars']['headingsFontWeight'] ); ?>">
		<label>@headingsColor</label>
		<input type="text" name="bootstrap[vars][headingsColor]" class="span3" value="<?php echo esc_attr( $options['vars']['headingsColor'] ); ?>">

		<label>@fontSizeLarge</label>
		<input type="text" name="bootstrap[vars][fontSizeLarge]" class="span3" value="<?php echo esc_attr( $options['vars']['fontSizeLarge'] ); ?>">
		<label>@fontSizeSmall</label>
		<input type="text" name="bootstrap[vars][fontSizeSmall]" class="span3" value="<?php echo esc_attr( $options['vars']['fontSizeSmall'] ); ?>">
		<label>@fontSizeMini</label>
		<input type="text" name="bootstrap[vars][fontSizeMini]" class="span3" value="<?php echo esc_attr( $options['vars']['fontSizeMini'] ); ?>">

		<label>@paddingLarge</label>
		<input type="text" name="bootstrap[vars][paddingLarge]" class="span3" value="<?php echo esc_attr( $options['vars']['paddingLarge'] ); ?>">
		<label>@paddingSmall</label>
		<input type="text" name="bootstrap[vars][paddingSmall]" class="span3" value="<?php echo esc_attr( $options['vars']['paddingSmall'] ); ?>">
		<label>@paddingMini</label>
		<input type="text" name="bootstrap[vars][paddingMini]" class="span3" value="<?php echo esc_attr( $options['vars']['paddingMini'] ); ?>">

		<label>@baseBorderRadius</label>
		<input type="text" name="bootstrap[vars][baseBorderRadius]" class="span3" value="<?php echo esc_attr( $options['vars']['baseBorderRadius'] ); ?>">
		<label>@borderRadiusLarge</label>
		<input type="text" name="bootstrap[vars][borderRadiusLarge]" class="span3" value="<?php echo esc_attr( $options['vars']['borderRadiusLarge'] ); ?>">
		<label>@borderRadiusSmall</label>
		<input type="text" name="bootstrap[vars][borderRadiusSmall]" class="span3" value="<?php echo esc_attr( $options['vars']['borderRadiusSmall'] ); ?>">

		<label>@heroUnitBackground</label>
		<input type="text" name="bootstrap[vars][heroUnitBackground]" class="span3" value="<?php echo esc_attr( $options['vars']['heroUnitBackground'] ); ?>">
		<label>@heroUnitHeadingColor</label>
		<input type="text" name="bootstrap[vars][heroUnitHeadingColor]" class="span3" value="<?php echo esc_attr( $options['vars']['heroUnitHeadingColor'] ); ?>">
		<label>@heroUnitLeadColor</label>
		<input type="text" name="bootstrap[vars][heroUnitLeadColor]" class="span3" value="<?php echo esc_attr( $options['vars']['heroUnitLeadColor'] ); ?>">

		<h3>Tables</h3>
		<label>@tableBackground</label>
		<input type="text" name="bootstrap[vars][tableBackground]" class="span3" value="<?php echo esc_attr( $options['vars']['tableBackground'] ); ?>">
		<label>@tableBackgroundAccent</label>
		<input type="text" name="bootstrap[vars][tableBackgroundAccent]" class="span3" value="<?php echo esc_attr( $options['vars']['tableBackgroundAccent'] ); ?>">
		<label>@tableBackgroundHover</label>
		<input type="text" name="bootstrap[vars][tableBackgroundHover]" class="span3" value="<?php echo esc_attr( $options['vars']['tableBackgroundHover'] ); ?>">
		<label>@tableBorder</label>
		<input type="text" name="bootstrap[vars][tableBorder]" class="span3" value="<?php echo esc_attr( $options['vars']['tableBorder'] ); ?>">

		<h3>Forms</h3>
		<label>@inputBackground</label>
		<input type="text" name="bootstrap[vars][inputBackground]" class="span3" value="<?php echo esc_attr( $options['vars']['inputBackground'] ); ?>">
		<label>@inputBorder</label>
		<input type="text" name="bootstrap[vars][inputBorder]" class="span3" value="<?php echo esc_attr( $options['vars']['inputBorder'] ); ?>">
		<label>@inputBorderRadius</label>
		<input type="text" name="bootstrap[vars][inputBorderRadius]" class="span3" value="<?php echo esc_attr( $options['vars']['inputBorderRadius'] ); ?>">
		<label>@inputDisabledBackground</label>
		<input type="text" name="bootstrap[vars][inputDisabledBackground]" class="span3" value="<?php echo esc_attr( $options['vars']['inputDisabledBackground'] ); ?>">
		<label>@formActionsBackground</label>
		<input type="text" name="bootstrap[vars][formActionsBackground]" class="span3" value="<?php echo esc_attr( $options['vars']['formActionsBackground'] ); ?>">
		<label>@btnPrimaryBackground</label>
		<input type="text" name="bootstrap[vars][btnPrimaryBackground]" class="span3" value="<?php echo esc_attr( $options['vars']['btnPrimaryBackground'] ); ?>">
		<label>@btnPrimaryBackgroundHighlight</label>
		<input type="text" name="bootstrap[vars][btnPrimaryBackgroundHighlight]" class="span3" value="<?php echo esc_attr( $options['vars']['btnPrimaryBackgroundHighlight'] ); ?>">

	  </div><!-- /span -->
	  <div class="span3">

		<h3>Form states &amp; alerts</h3>
		<label>@warningText</label>
		<input type="text" name="bootstrap[vars][warningText]" class="span3" value="<?php echo esc_attr( $options['vars']['warningText'] ); ?>">
		<label>@warningBackground</label>
		<input type="text" name="bootstrap[vars][warningBackground]" class="span3" value="<?php echo esc_attr( $options['vars']['warningBackground'] ); ?>">
		<label>@errorText</label>
		<input type="text" name="bootstrap[vars][errorText]" class="span3" value="<?php echo esc_attr( $options['vars']['errorText'] ); ?>">
		<label>@errorBackground</label>
		<input type="text" name="bootstrap[vars][errorBackground]" class="span3" value="<?php echo esc_attr( $options['vars']['errorBackground'] ); ?>">
		<label>@successText</label>
		<input type="text" name="bootstrap[vars][successText]" class="span3" value="<?php echo esc_attr( $options['vars']['successText'] ); ?>">
		<label>@successBackground</label>
		<input type="text" name="bootstrap[vars][successBackground]" class="span3" value="<?php echo esc_attr( $options['vars']['successBackground'] ); ?>">
		<label>@infoText</label>
		<input type="text" name="bootstrap[vars][infoText]" class="span3" value="<?php echo esc_attr( $options['vars']['infoText'] ); ?>">
		<label>@infoBackground</label>
		<input type="text" name="bootstrap[vars][infoBackground]" class="span3" value="<?php echo esc_attr( $options['vars']['infoBackground'] ); ?>">

		<h3>Navbar</h3>
		<label>@navbarHeight</label>
		<input type="text" name="bootstrap[vars][navbarHeight]" class="span3" value="<?php echo esc_attr( $options['vars']['navbarHeight'] ); ?>">
		<label>@navbarBackground</label>
		<input type="text" name="bootstrap[vars][navbarBackground]" class="span3" value="<?php echo esc_attr( $options['vars']['navbarBackground'] ); ?>">
		<label>@navbarBackgroundHighlight</label>
		<input type="text" name="bootstrap[vars][navbarBackgroundHighlight]" class="span3" value="<?php echo esc_attr( $options['vars']['navbarBackgroundHighlight'] ); ?>">
		<label>@navbarText</label>
		<input type="text" name="bootstrap[vars][navbarText]" class="span3" value="<?php echo esc_attr( $options['vars']['navbarText'] ); ?>">
		<label>@navbarBrandColor</label>
		<input type="text" name="bootstrap[vars][navbarBrandColor]" class="span3" value="<?php echo esc_attr( $options['vars']['navbarBrandColor'] ); ?>">
		<label>@navbarLinkColor</label>
		<input type="text" name="bootstrap[vars][navbarLinkColor]" class="span3" value="<?php echo esc_attr( $options['vars']['navbarLinkColor'] ); ?>">
		<label>@navbarLinkColorHover</label>
		<input type="text" name="bootstrap[vars][navbarLinkColorHover]" class="span3" value="<?php echo esc_attr( $options['vars']['navbarLinkColorHover'] ); ?>">
		<label>@navbarLinkColorActive</label>
		<input type="text" name="bootstrap[vars][navbarLinkColorActive]" class="span3" value="<?php echo esc_attr( $options['vars']['navbarLinkColorActive'] ); ?>">
		<label>@navbarLinkBackgroundHover</label>
		<input type="text" name="bootstrap[vars][navbarLinkBackgroundHover]" class="span3" value="<?php echo esc_attr( $options['vars']['navbarLinkBackgroundHover'] ); ?>">
		<label>@navbarLinkBackgroundActive</label>
		<input type="text" name="bootstrap[vars][navbarLinkBackgroundActive]" class="span3" value="<?php echo esc_attr( $options['vars']['navbarLinkBackgroundActive'] ); ?>">

		<label>@navbarCollapseWidth</label>
		<input type="text" name="bootstrap[vars][navbarCollapseWidth]" class="span3" value="<?php echo esc_attr( $options['vars']['navbarCollapseWidth'] ); ?>">
		<label>@navbarCollapseDesktopWidth</label>
		<input type="text" name="bootstrap[vars][navbarCollapseDesktopWidth]" class="span3" value="<?php echo esc_attr( $options['vars']['navbarCollapseDesktopWidth'] ); ?>">

		<h3>Dropdowns</h3>
		<label>@dropdownBackground</label>
		<input type="text" name="bootstrap[vars][dropdownBackground]" class="span3" value="<?php echo esc_attr( $options['vars']['dropdownBackground'] ); ?>">
		<label>@dropdownBorder</label>
		<input type="text" name="bootstrap[vars][dropdownBorder]" class="span3" value="<?php echo esc_attr( $options['vars']['dropdownBorder'] ); ?>">
		<label>@dropdownLinkColor</label>
		<input type="text" name="bootstrap[vars][dropdownLinkColor]" class="span3" value="<?php echo esc_attr( $options['vars']['dropdownLinkColor'] ); ?>">
		<label>@dropdownLinkColorHover</label>
		<input type="text" name="bootstrap[vars][dropdownLinkColorHover]" class="span3" value="<?php echo esc_attr( $options['vars']['dropdownLinkColorHover'] ); ?>">
		<label>@dropdownLinkBackgroundHover</label>
		<input type="text" name="bootstrap[vars][dropdownLinkBackgroundHover]" class="span3" value="<?php echo esc_attr( $options['vars']['dropdownLinkBackgroundHover'] ); ?>">
	  </div><!-- /span -->
	</div><!-- /row -->
</section>

<?php submit_button() ?>
</form>