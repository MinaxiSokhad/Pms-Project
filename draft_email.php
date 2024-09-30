<form method="post" action="" id="send_mail" name="send_mail" enctype="multipart/form-data" class="w-75 mx-auto">
    <?php if (isset($_GET['id'])): ?>
        <input type="hidden" name="id" id="id" value="<?php echo e($emailData['id']); ?>">
    <?php endif; ?>
    <div class="form-group">
        <input type="text" name="name" class="form-control" placeholder="Name" required=""
            value="<?php echo e($oldFormData['name'] ?? '') ?>" />
    </div>

    <div class="form-group">
        <input type="email" name="to_email" class="form-control" placeholder="Email address" required=""
            value="<?php echo e($oldFormData['to_email'] ?? '') ?>" />
    </div>

    <div class="form-group">
        <input type="text" name="subject" class="form-control" placeholder="Subject" required=""
            value="<?php echo e($oldFormData['subject'] ?? '') ?>" />
    </div>

    <div class="form-group">
        <textarea name="message" class="form-control" placeholder="Write your message here..." required=""><?php echo e($oldFormData['message'] ?? '') ?>
    </textarea>
    </div>

    <div class="form-group">
        <input type="file" name="file[]" multiple="multiple">
    </div>

    <div class="submit text-center">
        <input type="submit" name="action" class="btn btn-success " value="Send Email">
        <input type="submit" name="action" class="btn btn-success " value="Save Draft">
    </div>
</form>