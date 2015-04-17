
<form>

<ul>
	<?php foreach($parent_list as $parent_list_item): ?>
	<li><label><input type="radio" name="parent_list" value="<?php echo $parent_list_item['ID'] ?>" data-parent-name="<?php echo $parent_list_item['post_title'] ?>"><?php echo $parent_list_item['post_title'] ?></input></label></li>
	<?php endforeach; ?>
</ul>

<input type="button" class="button media-button button-primary button-large media-button-select" id="parent_list_btn" value="Select" />


</form>

<script>
jQuery(document).ready(function(){
    jQuery("#parent_list_btn").click(function(){
    	var radioValue = jQuery("input[name='parent_list']:checked").val();
    	var radioText = jQuery("input[name='parent_list']:checked").data('parent-name');
        if(radioValue){
            window.parent.jQuery('#current-parent-post').val(radioValue);
            window.parent.jQuery('#current-parent-post-title').val(radioText);
		    tb_remove();
        }
    });
    
});
</script>