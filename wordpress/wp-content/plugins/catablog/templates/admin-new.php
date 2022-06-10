<div class="wrap">

	<h2><?php _e("Add New CataBlog Entry", "catablog") ?></h2>
	
	<?php $this->render_catablog_admin_message() ?>
	
	<form id="catablog-create" class="catablog-form clear_float" method="post" action="admin.php?page=catablog-create" enctype="multipart/form-data">
		
		<div id="upload-form-left-col">
			
			<div id="dynamic_title">
				<h3><strong><?php _e("Upload Images To Create New Catalog Item", "catablog"); ?></strong></h3>
				<h3 class="hide"><strong><?php _e("Upload Multiple Images", "catablog"); ?></strong></h3>
			</div>

			<div id="select_images_form">
				<?php /*
				<p><?php _e("Insert Newly Uploaded Images Into:", "catablog"); ?></p>
				
				<p>
					<label for="catablog-upload-cateogry"><?php _e("Category", "catablog"); ?></label>
					<select name="">
						<?php $terms = $this->get_terms(); ?>
						<?php $default_term = $this->get_default_term(); ?>
						<?php foreach($terms as $term): ?>
							<?php $selected = ($term->name == $default_term->name)? "selected='selected'" : ""; ?>
							<?php echo "<option value='{$term->term_id}' $selected>{$term->name}</option>"; ?>
						<?php endforeach ?>
					</select>
				</p>
				
				<p>
					<label for="catablog-upload-gallery"><?php _e("Gallery", "catablog")?></label>
					<select name="">
						<option value="-1"><?php _e("- No Gallery", "catablog"); ?></option>
						<?php $galleries = CataBlogGallery::getGalleries(); ?>
						<?php foreach($galleries as $gallery): ?>
							<?php echo "<option value='{$gallery->getId()}'>{$gallery->getTitle()}</option>"?>
							
						<?php endforeach ?>
					</select>
				</p>
				*/ ?>

				<input type="file" id="new_image" name="new_image" accept="image/*" multiple />
				<input type="submit" name="save" value="<?php _e("Upload", "catablog") ?>" class="button-primary" />
				<?php wp_nonce_field( 'catablog_create', '_catablog_create_nonce', false, true ) ?>

				<p><?php printf(__("Maximum upload file size: %sB", "catablog"), ini_get('upload_max_filesize')); ?></p>

				<p><small>
					<?php _e("Select images on your computer to upload and make new catalog items with.", "catablog"); ?><br />
					<?php _e("You may upload JPEG, GIF and PNG graphic formats only.", "catablog"); ?><br />
					<strong><?php _e("No animated GIFs please.", "catablog"); ?></strong>
				</small></p>

				<p id="catablog-multifile-upload-disabled" class="error hide">
					<?php _e("Multiple file upload has been disabled. <br>You can fix this by upgrading to a supported browser.", "catablog"); ?><br />
					<a href="" target="_blank"><?php _e("Show me which browsers are supported") ?></a>
				</p>
			</div>
<?php /*
			<h3><strong><?php _e("Choose Categories","catablog") ?></strong></h3>
			<ul id="catablog-category-checklist" class="list:category categorychecklist form-no-clear">

				<?php $categories = $this->get_terms() ?>
				<?php $result = new CataBlogItem() ?>
				<?php ?>

				<?php if (count($categories) < 1): ?>
					<li><span><?php _e("You currently have no categories.", 'catablog'); ?></span></li>
				<?php endif ?>

				<?php foreach ($categories as $category): ?>
				<li>
					<label class="catablog-category-row">
						<?php $checked = (in_array($category->term_id, array_keys($result->getCategories())))? 'checked="checked"' : '' ?>
						<input id="in-category-<?php echo $category->term_id ?>" type="checkbox" <?php echo $checked ?> name="categories[]"  value="<?php echo $category->term_id ?>" />
						<?php $default_term = $this->get_default_term() ?>
						<?php if ($category->name != $default_term->name): ?>
							<a href="#delete" class="catablog-category-delete hide"><small><?php _e("[DELETE]", 'catablog'); ?></small></a>
						<?php endif ?>
						<span><?php echo $category->name ?></span>

					</label>
				</li>
				<?php endforeach ?>
			</ul>
			<p><small><?php _e("Check the categories you wish all new catalog items to be put into.", "catablog") ?></small></p>
*/ ?>
		</div>
		
		<div id="upload-form-right-col" class="hide">
			<div id="upload-feedback">
				<p><?php printf(__('currently uploading %s of %s', 'catablog'), '<strong id="current_number"></strong>', '<strong id="total_number"></strong>') ?></p>
				<div id="catablog-progress-current-upload" class="catablog-progress">
					<div class="catablog-progress-bar"></div>
					<small class="catablog-progress-text">&nbsp;</small>
				</div>
				<div id="catablog-progress-all-uploads" class="catablog-progress">
					<div class="catablog-progress-bar"></div>
					<small class="catablog-progress-text">&nbsp;</small>
				</div>
			</div>
			<ul id="new-items-editor"></ul>
		</div>
		
	</form>
</div>

<script type="text/javascript">
	var file_input;
	var current_upload;
	var total_uploads;

	var post_url = "<?php echo $this->urls['plugin'] . '/lib/catablog.upload.php' ?>";
	var wp_nonce = "<?php echo wp_create_nonce('catablog_file_upload'); ?>";
	var auth_cookie = "<?php echo (is_ssl() ? $_COOKIE[SECURE_AUTH_COOKIE] : $_COOKIE[AUTH_COOKIE]); ?>";
	var logged_in_cookie = "<?php echo $_COOKIE[LOGGED_IN_COOKIE]; ?>";

	jQuery(document).ready(function($) {

		if (!('FormData' in window)) {
			$('#catablog-multifile-upload-disabled').removeClass('hide');
		}

		file_input = document.getElementById("new_image");
		
		$('#new-items-editor').bind('click', delegateClickForNewItems);
		$('#new-items-editor').bind('keyup', delegateKeyUpForNewItems);
		$('#new-items-editor').bind('keypress', delegateKeyPressForNewItems);

		$("#catablog-create").submit(function(event){
			event.preventDefault();
			
			current_upload = 0;
			total_uploads = file_input.files.length;

			$('#current_number').html(current_upload + 1);
			$('#total_number').html(total_uploads);

			$('#catablog-progress-all-uploads .catablog-progress-bar').width('0%');
			$('#catablog-progress-all-uploads .catablog-progress-text').html('0%');
			
			$('#catablog-progress-current-upload .catablog-progress-bar').width('0%');
			$('#catablog-progress-current-upload .catablog-progress-text').html('0%');

			$('#upload-form-right-col').removeClass('hide');

			uploadFile()
		});
	});
	

	function uploadFile() {
		var file = file_input.files[current_upload];

		var form_data = new FormData();
		form_data.append("new_image", file);
		form_data.append("auth_cookie", auth_cookie);
		form_data.append("logged_in_cookie", logged_in_cookie);
		form_data.append("_wpnonce", wp_nonce);
		form_data.append("categories", []);

		var xhr = new XMLHttpRequest();

		xhr.addEventListener('load', uploadComplete);
		xhr.addEventListener('error', uploadError);
		xhr.upload.addEventListener('progress', uploadProgress);

		xhr.open("POST", post_url);
		xhr.send(form_data);
	}

	function uploadComplete(event) {
		current_upload += 1;

		var percent = Math.ceil((current_upload/total_uploads) * 100) + '%';
		
		jQuery('#catablog-progress-all-uploads .catablog-progress-bar').width(percent);
		jQuery('#catablog-progress-all-uploads .catablog-progress-text').html(percent);

		if (current_upload < total_uploads) {
			jQuery('#current_number').html(current_upload + 1);
			uploadFile()
		}

		jQuery('#new-items-editor').append(event.target.response);
		jQuery('#new-items-editor li:last').show(800);
	}

	function uploadProgress(event) {
		var percent = Math.ceil((event.loaded / event.total) * 100) + '%';
		jQuery('#catablog-progress-current-upload .catablog-progress-bar').width(percent);
		jQuery('#catablog-progress-current-upload .catablog-progress-text').html(percent);
	}

	function uploadError(event) {
		console.error('!! Upload Error', event);
	}

	function delegateClickForNewItems(event) {
		var target_tag = event.target.tagName;
		var target_class = event.target.className;
		if (target_tag == 'INPUT' && target_class == 'button-primary') {
			catablog_micro_save.call(event.target);
		}
	}

	function delegateKeyUpForNewItems(event) {
		var target = event.target;
		if (target.tagName == 'INPUT') {
			switch (target.className) {
			case 'title catablog-micro-editor-field':
				catablog_verify_title.call(target);
				break;
			case 'price catablog-micro-editor-field':
				catablog_verify_price.call(target);
				break;
			case 'order catablog-micro-editor-field':
				catablog_verify_order.call(target);
				break;
			}
		}
	}

	function delegateKeyPressForNewItems(event) {
		var key_pressed = event.type == "keypress";
		var key_code = (event.keyCode ? event.keyCode : event.which);
		if (key_pressed && key_code == 13) {
			catablog_micro_save.call(event.target);
			event.preventDefault();
			return false;
		}
	}

	function catablog_verify_title() {
		var title = jQuery.trim(this.value);
		
		if (title.length < 1 || title.length > 200) {
			if (true == jQuery(this).siblings('.error').hasClass('hide')) {
				jQuery(this).siblings('.error').removeClass('hide');
				jQuery(this).closest('table').find('input.button-primary').attr('disabled', true);
			}
		}
		else {
			if (false == jQuery(this).siblings('.error').hasClass('hide')) {
				jQuery(this).siblings('.error').addClass('hide');
				jQuery(this).closest('table').find('input.button-primary').attr('disabled', false);
			}
		}
	}
	
	function catablog_verify_price() {
		var price = this.value;
		if (!is_float(price)) {
			if (true == jQuery(this).siblings('.error').hasClass('hide')) {
				jQuery(this).siblings('.error').removeClass('hide');
				jQuery(this).closest('table').find('input.button-primary').attr('disabled', true);
			}
		}
		else {
			if (false == jQuery(this).siblings('.error').hasClass('hide')) {
				jQuery(this).siblings('.error').addClass('hide');
				jQuery(this).closest('table').find('input.button-primary').attr('disabled', false);
			}
		}
	}
	
	function catablog_verify_order() {
		var order = this.value;
		if (!is_integer(order)) {
			if (true == jQuery(this).siblings('.error').hasClass('hide')) {
				jQuery(this).siblings('.error').removeClass('hide');
				jQuery(this).closest('table').find('input.button-primary').attr('disabled', true);
			}
		}
		else {
			if (false == jQuery(this).siblings('.error').hasClass('hide')) {
				jQuery(this).siblings('.error').addClass('hide');
				jQuery(this).closest('table').find('input.button-primary').attr('disabled', false);
			}
		}
	}
	
	function catablog_micro_save() {		
		var container = jQuery(this).closest('li');
		var button    = container.find('input.button-primary');
		
		// if the button is disabled, stop the script
		if (button.attr('disabled') != undefined) {
			alert("<?php echo addslashes(__('There are errors, please correct them before saving.', 'catablog')); ?>");
			return false;
		}
		
		// get field values from the DOM
		var item_id      = container.find('input.id').val();
		var title        = container.find('input.title').val();
		var description  = container.find('textarea.description').val();
		var link         = container.find('input.link').val();
		var price        = container.find('input.price').val();
		var product_code = container.find('input.product_code').val();
		var order        = container.find('input.order').val();
		
		if (description == undefined) {
			description = '';
		}
		
		// trim field values
		title        = jQuery.trim(title);
		link         = jQuery.trim(link);
		price        = jQuery.trim(price);
		product_code = jQuery.trim(product_code);
		order        = jQuery.trim(order);
		
		container.find('input').attr('readonly', true);
		container.find('textarea').attr('readonly', true);
		container.next('li').find('input.title').focus().select();
		
		button.attr('disabled', true);
		button.addClass('button button-disabled').removeClass('button-primary');
		button.after('<span class="ajax-save">&nbsp;</span>');
	
		var params = {
			'action'       : 'catablog_micro_save',
			'security'     : '<?php echo wp_create_nonce("catablog-micro-save") ?>',
			'id'           : item_id,
			'title'        : title,
			'description'  : description,
			'link'         : link,
			'price'        : price,
			'product_code' : product_code,
			'order'        : order
		}
		
		jQuery.post(ajaxurl, params, function(data) {
			try {
				var json = jQuery.parseJSON(data);
				if (json.success == false) {
					alert(json.message);
				}
				
				container.hide(800, function() {
					jQuery(this).remove();
				});
			}
			catch(error) {
				alert(error);
			}
		});					
		
		return false;
	}
	
	
	// BIND THE SCREEN SETTINGS AJAX SAVE
	var nonce = '<?php echo wp_create_nonce("catablog-update-screen-settings") ?>';
	jQuery('.hide-catablog-column-tog').bind('change', function(event) {
		saveScreenSettings('#adv-settings input', nonce);
	});
	

</script>
