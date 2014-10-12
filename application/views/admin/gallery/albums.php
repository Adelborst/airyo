<?php
/**
 * Created by PhpStorm.
 * User: N.Kulchinskiy
 * Date: 07.10.14
 * Time: 22:30
 */
?>
<div class="container">
	<div class="row" id="links">
		<h1 class="page-header">Галерея</h1>

		<?php if ($message) : ?>
			<div class="alert alert-<?=$message['type']?>">
				<a class="close" data-dismiss="alert" href="#">&times;</a>
				<?php if ($message['type']=='success') : ?>
					<span class="glyphicon glyphicon-ok"></span>
				<?php endif; ?>
				<?=$message['text']?>
			</div>
		<? endif; ?>

		<ol class="breadcrumb">
			<li><a href="#">Галерея</a></li>
			<li class="un-styled pull-right"><a href="" class="pull-right" data-toggle="modal" data-target="#createAlbumModal">Создать альбом</a></li>
		</ol>

		<?php foreach($albums as $album) : ?>
			<div class="col-lg-3 col-md-4 col-xs-6 thumb">
				<a class="thumbnail" href="/admin/gallery/<?=$album->label; ?>" title="<?=$album->title; ?>">
					<img src="/gallery/<?=$album->images_path; ?>/<?=$album->images_label; ?>">
					<div class="photo-album-title">
						<p class="photo-title pull-left"><?=$album->title; ?></p>
						<span class="pull-right"><i class="glyphicon glyphicon-camera"></i> <?=$album->images_count; ?></span>
					</div>
				</a>
			</div>
		<?php endforeach; ?>
	</div>
	<div class="text-center">
		<?=$pagination->create_links(); ?>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="createAlbumModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<form role="form" action="/admin/gallery/createalbum" method="post" style="margin-top: 20px">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel">Создать альбом</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="inputTitle">Название</label>
						<input type="text" class="form-control" required="required" name="album_title" id="inputTitle" placeholder="Название" />
					</div>
					<div class="form-group">
						<label for="inputDescription">Описание</label>
						<textarea class="form-control" name="album_description" id="inputDescription" cols="30" rows="6"></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Создать альбом</button>
				</div>
			</div>
		</form>
	</div>
</div>