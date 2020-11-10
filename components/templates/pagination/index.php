<!-- Пагинация -->
<nav aria-label="Page navigation" id="pagination">
	<ul class="pagination">
		<?php if ($this->active != 1): ?>
		<li>
			<a href="<?=$this->url?>" aria-label="Previous">
				<span aria-hidden="true">&laquo;&laquo;</span>
			</a>
		</li>
		<li>
			<a href="<?php if ($this->active == 2) { ?><?=$this->url?><?php } else { ?><?=$this->url_page.($this->active - 1)?><?php } ?>" aria-label="Previous">
				<span aria-hidden="true">&laquo;</span>
			</a>
		</li>
		<?php endif; ?>
		<?php for ($i = $this->start; $i <= $this->end; $i++): ?>
		<?php if ($i == $this->active) { ?><li class="active"><a href="#"><?=$i?></a></li><?php } else { ?><li><a href="<?php if ($i == 1) { ?><?=$this->url?><?php } else { ?><?=$this->url_page.$i?><?php } ?>"><?=$i?></a></li><?php } ?>
		<?php endfor; ?>
		<?php if ($this->active != $this->count_pages): ?>
		<li>
			<a href="<?=$this->url_page.($this->active + 1)?>" aria-label="Next">
				<span aria-hidden="true">&raquo;</span>
			</a>
		</li>
		<li>
			<a href="<?=$this->url_page.$this->count_pages?>" aria-label="Last">
				<span aria-hidden="true">&raquo;&raquo;</span>
			</a>
		</li>
		<?php endif; ?>
	</ul>
</nav>