<?php 

$courseTags = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE meta_key = '_is4wp_learndash_autoenroll'");
$enrolledCourses = array();
foreach ($courseTags as $courseTag) {
  $metaValue = $courseTag -> meta_value;
  $meta_value_clean = ltrim($metaValue, "," );
  if(memb_hasAnyTags(array($meta_value_clean, $contact_id= "false"))) {
    array_push($enrolledCourses, $courseTag ->post_id);
  } else {

  }
}

// In your args for wp query, add your $enrolledCourses array to 'post__in'
$args = array(  
    'post_type' => array('sfwd-courses'),
    'post_status' => 'publish',
    'posts_per_page' => 21, 
    'orderby' => 'date', 
    'order' => 'asc', 
    'post__in' => $enrolledCourses,
  );

?>
