<? $this->load->view('common/header')?>

<div class="container">
	<h1 class="page-header">Слайдеры</h1>
	<? if (!empty($sliders)) { ?>
	<div class="row">
		<div class="col-md-12">
			<ul class="list-group">
				<? foreach ($sliders as $row) { ?>
				<li class="list-group-item">
					<a href="/airyo/sliders/edit/<?=$row['id']?>"><?=$row['title']?></a>
				</li>
				<? } ?>
			</ul>
		</div>
	</div>
	<? } ?>
</div>

<? $this->load->view('common/footer')?>

