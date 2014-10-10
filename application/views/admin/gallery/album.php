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
			<li>Название альбома</li>
			<li class="un-styled pull-right">

				<form id="fileupload" class="pull-right" action="/admin/files/upload" method="POST" enctype="multipart/form-data">
					<!-- The fileinput-button span is used to style the file input field as button -->
                        <span class="fileinput-button">
                            <a href="#" class="pull-right">Добавить изображения</a>
                            <!-- The file input field used as target for the file upload widget -->
                            <input type="file" name="file" multiple="">
                            <input type="hidden" name="pth" value="test/">
                        </span>
				</form>
			</li>
		</ol>

		<?php if(!empty($images)) : ?>
			<?php foreach($images as $image) : ?>
				<div class="col-lg-3 col-md-4 col-xs-6 thumb">
					<a class="thumbnail" href="/gallery/<?=$album->label; ?>" title="<?=$album->title; ?>">
						<img src="/gallery/<?=$album->images_path; ?>/<?=$album->images_label; ?>">
						<div class="photo-album-title">
							<p class="photo-title pull-left"><?=$album->title; ?></p>
							<span class="pull-right"><i class="glyphicon glyphicon-camera"></i> <?=$album->images_count; ?></span>
						</div>
					</a>
				</div>
			<?php endforeach; ?>
		<?php endif ?>

		<div class="col-lg-3 col-md-4 col-xs-6 thumb">
			<a class="thumbnail" href="http://placehold.it/400x300"  data-gallery="">
				<img class="img-responsive" src="http://placehold.it/400x300" alt="">
			</a>
		</div>
	</div>
	<div class="text-center">
		<?=$pagination->create_links(); ?>
	</div>
</div>
<!-- The Bootstrap Image Gallery lightbox, should be a child element of the document body -->
<div id="blueimp-gallery" class="blueimp-gallery">
	<!-- The container for the modal slides -->
	<div class="slides"></div>
	<!-- Controls for the borderless lightbox -->
	<h3 class="title"></h3>
	<a class="prev">‹</a>
	<a class="next">›</a>
	<a class="close">×</a>
	<a class="play-pause"></a>
	<ol class="indicator"></ol>
	<!-- The modal dialog, which will be used to wrap the lightbox content -->
	<div class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" aria-hidden="true">&times;</button>
					<h4 class="modal-title"></h4>
				</div>
				<div class="modal-body next"></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left prev">
						<i class="glyphicon glyphicon-chevron-left"></i>
						Previous
					</button>
					<button type="button" class="btn btn-primary next">
						Next
						<i class="glyphicon glyphicon-chevron-right"></i>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>