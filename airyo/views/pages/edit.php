<?$this->load->view('common/header')?>

<div class="container">
<? $this->load->view('common/notice')?>
	<h1 class="page-header">
		<?= $this->lang->line('module_title_pages')?>
	</h1>
	<ul class="breadcrumb">
		<li><a href="/airyo/pages/"><?= $this->lang->line('module_title_pages')?></a></li>
		<li><? echo $page['h1']; ?></li>
	</ul>
	<?php echo form_open_multipart("", 'name="edit" method="POST"');?>
		<? if (! $page['id']) : ?>
			<? if ($page_view_count > 1) : ?>
			<div class="form-group <?php if (form_error('template')) echo 'has-error"'; ?>">
				<label for="template" class="control-label">Шаблон страницы</label>
				<select class="form-control" id="tpl" name="template">
					<? foreach ($page_view as $row) { ?>
						<option value="<?=$row['view']?>"><?=$row['title']?></option>
					<?}?>
				</select>
			</div>
			<? else : ?>
			<input type="hidden" name="template" value="<?=$page_view[0]['view']?>">
			<? endif; ?>
		<? endif; ?>
		<div class="form-group <?php if (form_error('content')) echo 'has-error"'; ?>">
			<label for="description" class="control-label">Html-код страницы</label>
			<textarea rows="20" id="content" name="content" 
								class="form-control" placeholder=""><?= $page['content']; ?></textarea>
		</div>
		<div class="form-group <?php if (form_error('h1')) echo 'has-error"'; ?>">
			<label for="h1" class="control-label">Название</label>
			<input type="text" class="form-control" id="h1" name="h1" 
						 value="<? echo htmlspecialchars($page['h1']); ?>" placeholder="" >
		</div>
		<div class="form-group <?php if (form_error('alias')) echo 'has-error"'; ?>">
			<label for="alias" class="control-label">Адрес</label>
			<input type="text" class="form-control" id="alias" name="alias" 
						 value="<? echo $page['alias']; ?>" placeholder="" >
		</div>
		<div class="checkbox">
			<label>
				<input type="checkbox" id="enabled" value="1" name="enabled"
							 <? if ($page['enabled']) echo 'checked'; ?>> 
				<?= $this->lang->line('enabled')?>
			</label>
		</div>
		<button type="submit" class="btn btn-success" style="float: left;">
			<?= $this->lang->line('save')?>
		</button>
	<?php echo form_close();?>
	<? if (!empty($id)) {
		echo '<a href="#" style="float: right;" onclick="trash('.$id.');">
			'.$this->lang->line('delete_page_link').'
		</a>';
	}?>
</div>

<script type="text/javascript">
	<!--

		function trash(id) {
		//var li = $('#'+id).parent();
		//var tr = td.parent();
		if (confirm('<?= $this->lang->line('delete_page_cofirm')?>')) {
			$.ajax({
				type: 'post',
				url: '/airyo/pages/delete',
				dataType: 'json',
				data: {id:id},
				complete: function() {

				},
				success: function(data, status) {
					if (data.error) {
						alert('<?= $this->lang->line('error_delete')?>');
					}
					if (data.success) {
						location.replace('/airyo/pages');
					}

				},
				error: function (data,status,error)
				{
					alert(error);
				}
			});
		}
	}

	//-->
</script>

<?$this->load->view('common/footer')?>