<div class="text-center">
    <form class="form-ticketsubmit" enctype="multipart/form-data" method="post">
        <?php $this->load_view_admin('customer/view/notifications.php'); ?>
        <h1 class="h3 mb-3 font-weight-normal">Create a New Ticket </h1>
        <p>Submit a new and our team will get back to you as soon as</p>
        <div class="ticket-system-content text-left">
            
            <div class="form-group">
                  <label for="subject">Subject*</label>
                  <input type="text" id="subject" name="subject" class="form-control" autofocus>
            </div>   
            <div class="form-group">
          	<label for="category">Please select the best category for your request*</label>
          	<select class="form-control" name="category" id="category" required="">
                    <option value="Wordpress">Wordpress</option>
                    <option value="Magento">Magento</option>
                    <option value="Other">Other</option>
          	</select>
            </div>
            <div class="form-group">
                  <label for="description">Description</label>
                  <textarea id="description" name="description" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="description">Attachment</label>
                <input type="file" name="myfile" id="media" />
            </div>            
        </div>
        <input type="hidden" name="ticket_create_authorized" >
        <button class="btn btn-primary" type="submit">CREATE</button> &nbsp;
        <a href="<?= site_url() ?>/customer-ticket" class="btn btn-primary">BACK</a>
            
    </form>
</div>
<script src="https://www.google.com/recaptcha/api.js?render=<?= get_captcha_key()?>"></script>
<script>
  grecaptcha.ready(function() {
      grecaptcha.execute('<?= get_captcha_key()?>', {action: 'homepage'}).then(function(token) {
        // pass the token to the backend script for verification
      });
  });
</script>
