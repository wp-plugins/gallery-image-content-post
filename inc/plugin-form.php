<div class="wrap">
	<style scoped="scoped">
		fieldset {
			font-size: 1.5em;
			margin: 1em 0 .5em;
		}

		fieldset label {
			min-width: 100px;
			display: inline-block;
			font-size: 14px;
			font-weight: bold;
		}
	</style>
	<h2><?php _e( 'Gallery Image Content Options', GIC ); ?></h2>
	<!-- Plugin Options Form -->
	<form method="post" action="options.php" enctype="multipart/form-data" novalidate>
		<?php
		settings_fields( GIC_OP );
		$options = get_option( GIC_OP );
		?>
		<fieldset>
			<p>
				<label><?php _e( 'Select type: ', GIC ); ?></label>
				<select name="<?php echo GIC_OP; ?>[type]">
					<option value="gallery" <?php selected( $options['type'], 'gallery' ); ?>><?php _e( 'Gallery image in content post', GIC ); ?></option>
					<option value="single" <?php selected( $options['type'], 'single' ); ?>><?php _e( 'Single image lightbox', GIC ); ?></option>
				</select>
			</p>
			<p>
				<label><?php _e( 'Select Effect: ', GIC ); ?></label>
				<select name="<?php echo GIC_OP; ?>[effect]">
					<option value="zoom" <?php selected( $options['effect'], 'zoom' ); ?>><?php _e( 'Zoom effect', GIC ); ?></option>
					<option value="no-effect" <?php selected( $options['effect'], 'no-effect' ); ?>><?php _e( 'No Effect', GIC ); ?></option>
				</select>
			</p>
			<p>
				<label><?php _e( 'Show title?: ', GIC ); ?></label>
				<select name="<?php echo GIC_OP; ?>[show_title]">
					<option value="false" <?php selected( $options['show_title'], 'false' ); ?>><?php _e( 'No', GIC ); ?></option>
					<option value="true" <?php selected( $options['show_title'], 'true' ); ?>><?php _e( 'Yes', GIC ); ?></option>
				</select>
				<p class="description"><?php _e( 'To display title of current post in bottom gallery (lightbox) choose Yes, default is No', GIC ); ?></p>
			</p>
		</fieldset>
		<p class="submit">
			<?php submit_button( __( 'Save', GIC ), 'primary', 'submit', false ); ?>
		</p>
	</form>
</div>