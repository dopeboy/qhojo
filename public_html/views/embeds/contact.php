<div id="contact-modal-<?php echo $entity_id;?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div id="alert-error-modal-<?php echo $entity_id; ?>" class="alert alert-error alert-modal" style="">
        <strong>Error: </strong>
        <span id="error-message-modal-<?php echo $entity_id; ?>"></span>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>                    
    <div id="mh-<?php echo $entity_id; ?>" class="modal-header text-center">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id=""><?php echo $title; ?></h3>
    </div>
    <form class="form-submit" id="contact" action="/user/contact/<?php echo $entity_id; ?>/0" method="post">
        <div id="mb-<?php echo $entity_id; ?>" class="modal-body text-left" style="">
            <p>
            Hey <?php echo $receipient_first_name; ?>,
            </p>
            <textarea id="contact" class=" required" name="message" rows="3" placeholder="Put your question here." style=""></textarea>
            <p>-<?php echo $sender_first_name;?></p>            
            <input type="hidden" name="sender-user-id" value="<?php echo $sender_user_id; ?>">
            <input type="hidden" name="receipient-user-id" value="<?php echo $receipient_user_id; ?>">
            <input type="hidden" name="entity-type" value="<?php echo $entity_type; ?>">
            <input type="hidden" name="entity-id" value="<?php echo $entity_id; ?>">
        </div>

        <div id="mf-<?php echo $entity_id; ?>" class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
            <button class="btn btn-primary">Send Message</button>
        </div>
    </form>
</div>