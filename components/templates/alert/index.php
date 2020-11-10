<?php if ($this->status){ ?>
<div class="alert alert-success success alert-dismissible fade in" id="alertWidget" role="alert">
<?php } else { ?>
<div class="alert alert-danger success alert-dismissible fade in" id="alertWidget" role="alert">
<?php } ?>
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">×</span>
	</button>
	<strong><?= $this->response ?></strong>
</div>