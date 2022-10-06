
<div id="noteSection">
    <form class="notes" id="note-form" method="post">
        <label for="notes">Add notes here for this lesson<br></label>
        <input type="hidden" id="userId" name="userId" value="<?php echo $userId; ?>">
        <input type="hidden" id="postId" name="postId" value="<?php echo $postId; ?>">
        <input type="hidden" id="referrer" name="referer" value="http://<?php echo $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];?>">
        <div id="editor"><p><?php echo $notes["note_value"];?></p></div>
        <textarea name="notes" style="display:none" id="notes"></textarea>
        <input class="sm_bio_button center" type="submit" value="Save" style="padding: 9px 40px; margin: 1rem auto;">
    </form>
    <div id="success" class="ajax-response" style="display: none;"><p>Notes have been saved.</p></div>
</div>

<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
// Quill Stuff
  var toolbarOptions = [
    ['bold', 'italic', 'underline', 'strike'],        
    ['blockquote', 'code-block'],

    [{ 'header': 1 }, { 'header': 2 }],               
    [{ 'list': 'ordered'}, { 'list': 'bullet' }],

    [{ 'color': [] }, { 'background': [] }],          
    [{ 'align': [] }],

    ['clean']                                         
];
  var quill = new Quill('#editor', {
    modules: {
      toolbar: toolbarOptions
    },
    theme: 'snow'
  });
  jQuery("#note-form").on("submit",function() {
  jQuery("#notes").val(jQuery("#editor").html());
})

//Ajax submit
jQuery(document).ready(function () {
    jQuery("#note-form").submit(function (event) {
      var formData = {
        userId: jQuery("#userId").val(),
        postId: jQuery("#postId").val(),
        referrer: jQuery("#referrer").val(),
        notes: jQuery("#notes").val(),
      };
  
      jQuery.ajax({
        type: "POST",
        url: "#",
        data: formData,
        dataType: "json",
        encode: true
      })
      jQuery("#success").show();
      setTimeout(function() {
        jQuery("#success").fadeOut("slow");
      }, 2000);
      
      event.preventDefault();
  });
    
});
</script>


