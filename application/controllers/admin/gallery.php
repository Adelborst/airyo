<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gallery extends CommonAdminController {
	/** @var string  */
	protected $sHomeFolder = 'public/gallery';

	public function __construct() {
		parent::__construct();
		$this->load->helper('file');
		$this->config->load('not_allowed_mimes');
		$this->load->model('gallery_model');

		$this->oData['home_folder'] = $this->sHomeFolder;

		$this->oData['scripts'] = array(
			'/themes/airyo/js/FileUpload/js/vendor/jquery.ui.widget.js',
			'/themes/airyo/js/FileUpload/js/jquery.iframe-transport.js',
			'/themes/airyo/js/FileUpload/js/jquery.fileupload.js',
			'/themes/airyo/js/Gallery/js/ekko-lightbox.js',
			'/themes/airyo/js/gallery.js'
		);
		$this->oData['styles'] = array(
			'/themes/airyo/js/FileUpload/css/jquery.fileupload.css',
			'/themes/airyo/js/FileUpload/css/jquery.fileupload-ui.css',
			'/themes/airyo/js/FileUpload/css/style.css',
			'/themes/airyo/js/Gallery/css/ekko-lightbox.css',
			'/themes/airyo/css/gallery.css'
		);
	}

	public function index() {
		$this->oData['main_menu'] = 'gallery';

		$this->oData['result'] = array();
		$this->oData['message'] =  $this->session->flashdata('message')? $this->session->flashdata('message'):'';

		$this->oData["albums"] = $this->gallery_model->getFetchCountriesAlbums();

		$this->oData['profile_id'] = $this->oUser->id;
		$this->oData['pagination'] = $this->pagination;

		$this->oData['view'] = 'admin/gallery/albums';
	}

	public function getAlbum($sAlbumLabel)
	{
		$aGet = $this->input->get();
		$this->oData['main_menu'] = 'gallery';

		if(isset($aGet['action']) AND $aGet['action'] == 'edit') {
			$this->oData["album"] = $this->gallery_model->getAlbumByLabel($sAlbumLabel);

			$this->oData["images"] = $this->gallery_model->getFetchCountriesImages(array('sAlbumLabel' => $sAlbumLabel));

			$this->oData['profile_id'] = $this->oUser->id;
			$this->oData['view'] = 'admin/gallery/editAlbum';
		} else {
			$this->oData['result'] = array();
			$this->oData['message'] = $this->session->flashdata('message') ? $this->session->flashdata('message') : '';

			$aPaginationConfig = $this->getPaginationConfig();

			$this->oData["album"] = $this->gallery_model->getAlbumByLabel($sAlbumLabel);

			$aPaginationConfig['base_url'] = '/admin/gallery/' . $sAlbumLabel;
			$aPaginationConfig['total_rows'] = $this->oData["album"]->images_count;
			$aPaginationConfig['uri_segment'] = 4;

			$this->pagination->initialize($aPaginationConfig);

			$iPage = ($this->uri->segment($aPaginationConfig['uri_segment'])) ? $this->uri->segment($aPaginationConfig['uri_segment']) : 0;

			$aGalleryConfig = $this->config->item('gallery');
			$this->oData['preview_extension'] = $aGalleryConfig['image_preview_extension'];
			$this->oData['preview_size'] = $aGalleryConfig['image_preview_size'][1];

			$this->oData["images"] = $this->gallery_model->getFetchCountriesImages(array('sAlbumLabel' => $sAlbumLabel, 'iLimit' => $aPaginationConfig["per_page"], 'iStart' => $iPage));
			$this->oData['profile_id'] = $this->oUser->id;
			$this->oData['pagination'] = $this->pagination;
			$this->oData['view'] = 'admin/gallery/album';
		}
	}
	
	/**
	 * Создание нового альбома
	 *
	 * @author N.Kulchinskiy
	 */
	public function createAlbum() {
		$this->form_validation->set_rules('album_title', 'Название', 'required|trim|min_length[2]|xss_clean');
		$this->form_validation->set_rules('album_description', 'Описание', 'trim|min_length[2]|xss_clean');

		if ($this->form_validation->run() == true) {
			$sLabelAlbum = 'album'.time();

			$aAlbumData = array(
				'label'         => $sLabelAlbum,
				'title'         => $this->input->post('album_title',TRUE),
				'description'   => $this->input->post('album_description',TRUE),
				'image_id'      => 1,
				'user_id'       => $this->oUser->id,
				'create_date'   => date('Y-m-d H:i:s'),
				'enable'        => 1
			);

			if($this->gallery_model->addAlbum($aAlbumData)) {
				$sPath = $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.$this->sHomeFolder;

				if (!is_dir($sPath.DIRECTORY_SEPARATOR.$sLabelAlbum)) {
					mkdir($sPath.DIRECTORY_SEPARATOR.$sLabelAlbum);
				} else {
					$this->oData['message'] = $this->session->set_flashdata('message', array(
							'type' => 'danger',
							'text' => 'Альбом с таким именем уже есть'
						)
					);
				}
			} else {
				$this->oData['message'] = $this->session->set_flashdata('message', array(
						'type' => 'danger',
						'text' => 'Ошибка при создании альбома'
					)
				);
			}
		} else {
			$this->oData['message'] = $this->session->set_flashdata('message', array(
					'type' => 'danger',
					'text' => 'Ошибка при проверке названия или описания альбома'
				)
			);
		}

		redirect($_SERVER['HTTP_REFERER'], 'refresh');
	}

	/**
	 * Загрузка изображения в альбом
	 *
	 * @return string
	 *
	 * @author N.Kulchinskiy
	 */
	public function uploadImages(){
		$upload = isset($_FILES['files']) ? $_FILES['files'] : null;
		$aMessage = array();

		if ($upload ) {
			$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.$this->sHomeFolder.DIRECTORY_SEPARATOR.$_POST['album'];
			$config['allowed_types'] = '*';
			$this->load->library('upload', $config);

			$this->upload->initialize($config);
			if (in_array($upload['type'][0], $this->config->item('not_allowed_mimes'))) {
				$aMessage =   array(
						'type' => 'danger',
						'text' => $upload['type'][0].' not allowed mime'
				);

				echo json_encode(array('path'=>'/admin/gallery/'.$_POST['album'], 'err' =>$upload['type'][0].' not allowed mime'));
			}

			$oAlbum = $this->gallery_model->getAlbumByLabel($_POST['album']);

			$upload['name'] = $this->translitString($upload['name'][0]);

			$_FILES['file'] = array (
				'name'=> $upload['name'],
				'type'=> $upload['type'][0],
				'tmp_name'=> $upload['tmp_name'][0],
				'error'=> $upload['error'][0],
				'size'=> $upload['size'][0]
			);

			if ($this->upload->do_upload('file')) {
				$aTmpData = $this->upload->data();

				$aImageData = array(
					'label'         => $aTmpData['file_name'],
					'title'         => $aTmpData['raw_name'],
					'album_id'      => $oAlbum->id,
					'user_id'       => $this->oUser->id,
					'create_date'   => date('Y-m-d H:i:s'),
					'enable'        => 1
				);

				if($iId = $this->gallery_model->addImage($aImageData)) {
					$files_data[] = $aTmpData['full_path'];
                    $aImageData['create_date'] = date('H:i:s d.m.Y', strtotime($aImageData['create_date']));

					/** Создание превьюшек - старт */
					$aGalleryConfig = $this->config->item('gallery');
					$aImagePreviewSize = $aGalleryConfig['image_preview_size'];

					if(!empty($aImagePreviewSize) AND is_array($aImagePreviewSize)) {
						$sFileName = $upload['tmp_name'][0];
						foreach ($aImagePreviewSize as $aSize) {
							$oImage = $this->resizeImage($sFileName, $aSize['width'], $aSize['height']);
							$sPath = $config['upload_path'].DIRECTORY_SEPARATOR.'thumbs' . intval($aSize['width']) . 'x' . intval($aSize['height']);
							if (!is_dir($sPath)) {
								mkdir($sPath);
							}
							$new_name = sprintf("thumbs%s.jpg", $iId);

							imagejpeg($oImage, $sPath.DIRECTORY_SEPARATOR.$new_name);
						}
					}
					/** Создание превьюшек - финиш */

					$aMessage =   array(
						'type' => 'success',
						'text' => 'Файлы загружены'
					);
				} else {
					$aMessage =   array(
						'type' => 'danger',
						'text' => 'Ошибка при загрузке файла'
					);
				}
			} else {
				$files_data[] = $this->upload->display_errors();
			}
		} else {
			$aMessage =   array(
					'type' => 'danger',
					'text' => 'Not files'
			);
		}

		echo json_encode(array('image' => $aImageData, 'user' => $this->oUser, 'message' => $aMessage));
	}

	/**
	 * Сжатие изображения
	 *
	 * @param $old_file
	 * @param $width
	 * @param $height
	 *
	 * @return resource
	 *
	 * @author N.Kulchinskiy
	 */
	public function resizeImage($old_file, $width, $height) {
		list($width_orig, $height_orig, $image_type) = @getimagesize($old_file);
		$img = FALSE;

		// Get the image and create a thumbnail
		switch($image_type)
		{
			case 1:
				$img = @imagecreatefromgif($old_file);
				break;
			case 2:
				$img = @imagecreatefromjpeg($old_file);
				break;
			case 3:
				$img = @imagecreatefrompng($old_file);
				break;
		}

		// Build the thumbnail
		if($width_orig > $height_orig) {
			$width_ratio = $width / $width_orig;
			$new_width   = $width;
			$new_height  = $height_orig * $width_ratio;
		} else {
			$height_ratio = $height / $height_orig;
			$new_width    = $width_orig * $height_ratio;
			$new_height   = $height;
		}

		$new_img = @imagecreatetruecolor($new_width, $new_height);

		// Fill the image black
		@imagefilledrectangle($new_img, 0, 0, $new_width, $new_height, 0);
		@imagecopyresampled($new_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width_orig, $height_orig);

		return $new_img;
	}

	/**
	 * Обновление описания альбома
	 *
	 * @author N.Kulchinskiy
	 */
	public function ajaxEditDescriptionAlbum(){
		$aPost = $this->input->post();
		$aMessage = array();

		if(!empty($aPost)) {
			$sValidData = $this->validateData($aPost['album']);
			if(isset($sValidData['iAlbumId'])) {
				$iId = $sValidData['iAlbumId'];
				unset($sValidData['iAlbumId']);
				if($this->gallery_model->updateAlbum($iId, $sValidData)) {
					$aMessage = array(
						'type' => 'success',
						'text' => 'Альбом обновлён'
					);
				} else {
					$aMessage = array(
						'type' => 'danger',
						'text' => 'Ошибка при обновлении альбома'
					);
				}
			}
		} else {
			$aMessage = array(
				'type' => 'danger',
				'text' => 'Неизвестная ошибка'
			);
		}

		echo json_encode($aMessage);
	}
	
	/**
	 * Обновление содержимого альбома
	 *
	 * @author N.Kulchinskiy
	 */
	public function ajaxEditAlbum(){
		$aPost = $this->input->post();
		$aMessage = array();
		if(!empty($aPost)) {
			$aAlbum = $aPost['album'];

			if(isset($aPost['selected']) AND !empty($aPost['selected'])) {
				$aSelected = array_flip($aPost['selected']);
			}
			for($i = 0; $i < count($aAlbum['id']); $i++) {
				var_dump($aSelected);
die();
				if (isset($aSelected[$aAlbum['id'][$i]])) {
					//$this->removeImage($aAlbum['id'][$i]);

					$aMessage = array(
						'type' => 'success',
						'text' => 'Альбом обновлён'
					);
				} else {
					$aImages = array(
						'image_id' => $aAlbum['id'][$i],
						'title' => $aAlbum['title'][$i],
						'description' => $aAlbum['description'][$i]
					);
					$aValidateData = $this->validateData($aImages);

					if(isset($aValidateData['iImageId'])) {
						$iId = $aValidateData['iImageId'];
						unset($aValidateData['iImageId']);
						if($this->gallery_model->updateImage($iId, $aValidateData)) {
							$aMessage = array(
								'type' => 'success',
								'text' => 'Альбом обновлён'
							);
						} else {
							$aMessage = array(
								'type' => 'danger',
								'text' => 'Ошибка при обновлении альбома'
							);
						}
					}
				}
			}
		} else {
			$aMessage = array(
				'type' => 'danger',
				'text' => 'Неизвестная ошибка'
			);
		}

		echo json_encode($aMessage);
	}

	/**
	 * Удаление изображения
	 *
	 * @param $iId
	 * @return bool
	 *
	 * @author N.Kulchinskiy
	 */
	protected function removeImage($iId){
		if($iImageId = intval($iId) AND $iImageId > 0) {
			$oImage = $this->gallery_model->getImageById($iImageId);

			if($this->deleteAction($oImage->label_album, $oImage->label, $iImageId)) {
				if($this->gallery_model->deleteImage($iImageId)) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Удаление альбома
	 *
	 * @author N.Kulchinskiy
	 */
	public function ajaxRemoveAlbum(){
		$aPost = $this->input->post();
		$aMessage = array();

		if(!empty($aPost)) {
			$iAlbumId = $aPost['iAlbumId'];
			if($oAlbum = $this->gallery_model->getAlbumById($iAlbumId)) {
				if(!$this->gallery_model->getFetchCountriesImages(array('iAlbumId' => $iAlbumId))) {
					if($this->deleteAction($oAlbum->label)) {
						if($this->gallery_model->deleteAlbum($iAlbumId)) {
							$aMessage = array(
								'type' => 'success',
								'text' => 'Альбом удалён'
							);
						}
					} else {
						$aMessage = array(
							'type' => 'danger',
							'text' => 'Ошибка при удалении'
						);
					}
				} else {
					$aMessage = array(
						'type' => 'danger',
						'text' => 'Удалите все изображения из альбома'
					);
				}
			}
		} else {
			$aMessage = array(
				'type' => 'danger',
				'text' => 'Неизвестная ошибка'
			);
		}

		echo json_encode($aMessage);
	}
	
	/**
	 * Удаление директорий и файлов
	 *
	 * @param $sAlbum
	 * @param null $sImage
	 * @param null $iIdImage
	 *
	 * @return bool
	 * @author N.Kulchinskiy
	 */
	private function deleteAction($sAlbum, $sImage = null, $iIdImage = null) {
		$sPath = $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.$this->sHomeFolder.DIRECTORY_SEPARATOR.$sAlbum;

		if (empty($sImage)) {
			$aDirectory = opendir($sPath);
			while(($dir = readdir($aDirectory))) {
				if (is_dir($sPath.DIRECTORY_SEPARATOR.$dir) AND ($dir != ".") && ($dir != "..")) {
					$this->deleteAction($sAlbum.DIRECTORY_SEPARATOR.$dir);
				}
			}
			closedir ($aDirectory);
			rmdir ($sPath);
		} else {
			$iIdImage = intval($iIdImage);
			@unlink($sPath.DIRECTORY_SEPARATOR.$sImage);

			/** Удаление превьюшек - старт */
			$aGalleryConfig = $this->config->item('gallery');
			$aImagePreviewSize = $aGalleryConfig['image_preview_size'];
			$sImageExtension = $aGalleryConfig['image_preview_extension'];

			if(!empty($aImagePreviewSize) AND is_array($aImagePreviewSize)) {
				foreach ($aImagePreviewSize as $aSize) {
					if (is_dir($sPath.DIRECTORY_SEPARATOR.'thumbs'.intval($aSize['width']) . 'x' . intval($aSize['height']))) {
						@unlink($sPath.DIRECTORY_SEPARATOR.'thumbs'.intval($aSize['width']) . 'x' . intval($aSize['height']).DIRECTORY_SEPARATOR.'thumbs'.$iIdImage.$sImageExtension);
					}
				}
			}
			/** Удаление превьюшек - финиш */
		}

		return true;
	}

	/**
	 * Транслитерация строки
	 *
	 * @param $sString
	 * @return string
	 *
	 * @author N.Kulchinskiy
	 */
	function translitString($sString) {
		$aTranslit = array(
			"А" => "A", "Б" => "B", "В" => "V", "Г" => "G",
			"Д" => "D", "Е" => "E", "Ж" => "J", "З" => "Z", "И" => "I",
			"Й" => "Y", "К" => "K", "Л" => "L", "М" => "M", "Н" => "N",
			"О" => "O", "П" => "P", "Р" => "R", "С" => "S", "Т" => "T",
			"У" => "U", "Ф" => "F", "Х" => "H", "Ц" => "TS", "Ч" => "CH",
			"Ш" => "SH", "Щ" => "SCH", "Ъ" => "", "Ы" => "YI", "Ь" => "",
			"Э" => "E", "Ю" => "YU", "Я" => "YA", "а" => "a", "б" => "b",
			"в" => "v", "г" => "g", "д" => "d", "е" => "e", "ж" => "j",
			"з" => "z", "и" => "i", "й" => "y", "к" => "k", "л" => "l",
			"м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r",
			"с" => "s", "т" => "t", "у" => "u", "ф" => "f", "х" => "h",
			"ц" => "ts", "ч" => "ch", "ш" => "sh","щ" => "sch","ъ" => "y",
			"ы" => "yi", "ь" => "", "э" => "e", "ю" => "yu", "я" => "ya",
			" " => "_"
		);
		return strtr($sString, $aTranslit);
	}

	/**
	 * Валидация данных альбома
	 *
	 * @param $aParams
	 * @return array
	 *
	 * @author N.Kulchinskiy
	 */
	private static function validateData($aParams){
		$aValidParams = array();

		// Проверка id альбома
		if(isset($aParams['album_id']) AND $iId = intval($aParams['album_id']) AND $iId > 0) {
			$aValidParams['iAlbumId'] = $iId;
		}
		// Проверка id изобраежния
		if(isset($aParams['image_id']) AND $iId = intval($aParams['image_id']) AND $iId > 0) {
			$aValidParams['iImageId'] = $iId;
		}
		// Проверка названия группы
		if(isset($aParams['title'])) {
			$aValidParams['title'] = htmlspecialchars(strip_tags($aParams['title']));
		}
		// Проверка описания группы
		if(isset($aParams['description'])) {
			$aValidParams['description'] = htmlspecialchars(strip_tags($aParams['description']));
		}

		return $aValidParams;
	}
}

/* End of file gallery.php */
/* Location: ./application/controllers/admin/gallery.php */