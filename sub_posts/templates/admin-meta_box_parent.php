
<?php echo $wp_nonce_field ?>
<a href="/wp-admin/admin-ajax.php?action=sub_post_parent_list&post_id=<?php echo $post_id ?>" class="thickbox" title="test thickbox" >Select Parent</a> 
<p>
    <label for="current-parent-post-title" class="">Current Parent Post:</label> <br/>
    <input type="text" name="current-parent-post-title" id="current-parent-post-title" value="<?php echo $post_parent_title; ?>" readonly />
    <input type="text" name="current-parent-post" id="current-parent-post" value="<?php echo $post_parent; ?>" />
</p> 