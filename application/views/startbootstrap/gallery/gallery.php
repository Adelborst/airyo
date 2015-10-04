<?

$this->css = '<link rel="stylesheet" href="/themes/startbootstrap/css/magnific-popup.css" />';
$this->js = '<script src="/themes/startbootstrap/js/jquery.magnific-popup.min.js"></script>';

?>

<? $this->load->view('startbootstrap/common/header')?>

<!-- Код начала альбомов -->
<div class="container">

<? if(!empty($albums)) : ?>
	
	<? foreach($albums as $album) : ?>
		
		<div class="album" id="album<?=$album->id?>">
			<div class="row">
	        	<div class="col-lg-12">
					<h1 class="page-header"><?=$album->title; ?>
						<small><?=$album->description; ?></small>
					</h1>
				</div>
		
				<? if(!empty($images[$album->id])) : ?>
				<? foreach($images[$album->id] as $image) : ?>

				<div class="col-md-3 portfolio-item">
					<a name="album<?=$album->id?>" href="/<?=$home_folder; ?>/<?=$album->label; ?>/<?=$image->label; ?>" title="<?=$image->description;?>">
						<img src="/<?=$home_folder; ?>/<?=$album->label; ?>/thumbs<?=$preview_size['width']; ?>x<?=$preview_size['height']; ?>/thumbs<?=$image->id; ?><?=$preview_extension; ?>" alt="" class="img-responsive" />
					</a>
				</div>
		
				<? endforeach; ?>
				<? endif ?>
		
			</div>
		</div>
	
	<? endforeach; ?>

<? endif ?>

</div>
<!-- Код окончания альбомов -->

<script>

$(document).ready(function(){

<?

if(!empty($albums)) {
	foreach($albums as $album) {
		echo "
			$('#album".$album->id."').magnificPopup({
				delegate: 'a[name=album".$album->id."]', 
				type: 'image',
				gallery:{enabled:true}
			});
		";
		
	}
}
?>
	
});

</script>

<? $this->load->view('startbootstrap/common/footer')?>