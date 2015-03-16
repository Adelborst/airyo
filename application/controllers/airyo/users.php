<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Users
 *
 * @editor N.Kulchinskiy
 */
class Users extends Airyo {

	public function __construct() {
		parent::__construct();

		$this->load->model('airyo/groups_model');
		$this->load->model('airyo/modules_model');
		$this->load->config('ion_auth', TRUE);
	}

	/**
	 * Получение списка пользователей
	 *
	 * @author N.Kulchinskiy
	 */
	public function index() {
		$this->data['main_menu'] = 'users';

		//$aPaginationConfig = $this->getPaginationConfig();
		//$aPaginationConfig['base_url'] = '/airyo/users/';
		//$aPaginationConfig['total_rows'] = $this->users_model->record_count();

		//$this->pagination->initialize($aPaginationConfig);

		//$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		
		$this->data["users"] = $this->users_model->getUsers(
			array(
				'iUserId' => $this->user->id,
				'iRoleId' => $this->user->role_id,
				//'iLimit' => $aPaginationConfig["per_page"],
				//'iStart' => $page
			)
		);

		foreach ($this->data["users"] as $iKey => $aUser) {
			$this->data["users"][$iKey]->groups = $this->ion_auth->get_users_groups($aUser->id)->result_array();
		}

		$this->data['message'] =  $this->session->flashdata('message') ? $this->session->flashdata('message'):'';
		$this->data['profile_id'] = $this->user->id;
		$this->data['pagination'] = $this->pagination;

		$this->data['view'] = 'airyo/users/list';
	}

	/**
	 * Создание профиля
	 *
	 * @author N.Zakharenko
	 */
	public function add() {
		$this->data['main_menu'] = 'users';

		$oPost = (object) $this->input->post();
		$aSession = array();

		if(!empty($oPost->form_add)) {
			$this->form_validation->set_rules('username', 'Пользователь', 'trim|required|min_length[3]|max_length[25]|alpha_numeric');
			$this->form_validation->set_rules('first_name', 'Имя', 'trim|min_length[2]|xss_clean');
			$this->form_validation->set_rules('email', 'Почтовый адрес', 'trim|required|valid_email|xss_clean');
			//$this->form_validation->set_rules('groups', 'Группа', 'required');
			//$this->form_validation->set_rules('role', 'Роль', 'required');
			$this->form_validation->set_rules('newpass', 'Новый пароль', 'trim|required');
			$this->form_validation->set_rules('passconf', 'Подтверждение пароля', 'trim|required|matches[newpass]');

			if ($this->form_validation->run() == true) {
				$sUsername = strtolower($this->input->post('username'));
				$sEmail    = strtolower($this->input->post('email'));
				$iRoleId   = $this->input->post('role', TRUE);
				$sPassword = $this->input->post('newpass');
				$aGroups = ($this->input->post('groups',TRUE)) ? $this->input->post('groups',TRUE) : array();

				$aAdditionalData = array(
					'first_name' => $this->input->post('first_name')
				);

				if($this->ion_auth->register($sUsername, $sPassword, $sEmail, $aAdditionalData, $aGroups, $iRoleId)) {
					$aMessage = array(
						'type' => 'success',
						'text' => 'Успешное создание пользователя'
					);
					$this->session->set_flashdata('message', $aMessage);
					redirect('airyo/users', 'refresh');
				} else {
					$aMessage = array(
						'type' => 'danger',
						'text' => 'Ошибка при создании пользователя'
					);
				}
			} else {
				$aSession['username'] = $this->input->post('username');
				$aSession['first_name'] = $this->input->post('first_name');
				$aSession['email'] = $this->input->post('email');
				$aSession['groups'] = $this->input->post('groups',TRUE);

				$aMessage = array(
					'type' => 'danger',
					'text' =>  validation_errors()
				);
			}
			/** Оповещение */
			$this->data['message'] =  $aMessage;
		}

		$this->data['session'] =  $aSession;
		$this->data['roles']  = $this->users_model->getRoles($this->user->id);
		$this->data['groups']  = $this->ion_auth->groups()->result_array();
		$this->data['view'] = 'airyo/users/add';
	}

	/**
	 * Отображение/редактирование профиля
	 *
	 * @param $iId
	 *
	 * @author N.Kulchinskiy
	 */
	public function edit($iId) {

		$oUserData = $this->users_model->getUserById($iId);
		if ($this->user->role_id == 1 AND in_array($oUserData->role_id, array(1, 2))) {
			if ($this->user->id != $oUserData->id) {
				redirect('airyo/users', 'refresh');
			}
		}

		$this->data['main_menu'] = 'users';
		$this->data['styles'] = array(
			'/themes/airyo/css/users.css',
		);

		$oPost = (object) $this->input->post();
		if(!empty($oPost->form_edit)) {
			$aMessage = array();

			if($oPost->form_edit == "profile") {
				$aMessage = $this->updateProfile($iId);
			}
			if($oPost->form_edit == "modules") {
				$aMessage = $this->updateUserModules($iId);
				$aMessage['form'] = $oPost->form_edit;
			}
			elseif($oPost->form_edit == "password") {
				$this->form_validation->set_rules('newpass', 'Новый пароль', 'trim|required');
				$this->form_validation->set_rules('passconf', 'Подтверждение пароля', 'trim|required|matches[newpass]');

				if ($this->form_validation->run() == true) {

					$NewPassword = $oPost->newpass;
					$identity_column = $this->config->item('identity', 'ion_auth');
					$identity = $this->db->select($identity_column)
						->where('id', $iId)
						->get($this->db->dbprefix('users'))
						->row();

					$aMessage = $this->changePassword($identity->$identity_column, $NewPassword);
				} else {
					$aMessage = array(
						'type' => 'danger',
						'text' =>  validation_errors()
					);
				}
				$aMessage['form'] = 'password';
			}
			/** Оповещение */
			$this->data['message'] =  $aMessage;
		}

		$aGroups = $this->ion_auth->groups()->result_array();
		$aUserGroups = $this->groups_model->getUsersGroups(array('iUserId' => $iId, 'bAsArray' => true));
		$userGroups = array();
		foreach ($aUserGroups as $key => $userGroup) {
			$userGroups[] = $userGroup['group_id'];
		}

		$aModules = array();
		$userModules = array();

		if ($iId !== $this->user->id) {
			$aModules = $this->modules_model->getModules();
			$aUserModules = $this->modules_model->getUserModules(array('iUserId' => $iId, 'bAsArray' => true));
			foreach ($aUserModules as $key => $userModule) {
				$userModules[] = $userModule['module_id'];
			}
		}

		$this->data['user']  = $this->users_model->getUserById($iId);
		$this->data['modules']  = $aModules;
		$this->data['user_modules']  = $userModules;
		$this->data['roles']  = $this->users_model->getRoles($this->user->id);
		$this->data['groups']  = $aGroups;
		$this->data['user_groups']  = $userGroups;

		$this->data['view'] = 'airyo/users/edit';
	}

	/**
	 * Обновление профиля
	 *
	 * @param $iId
	 * @return array
	 *
	 * @author N.Kulchinskiy
	 */
	private function updateProfile($iId){
		$aMessage = array();

		$iId = intval($iId);
		if($iId > 0) {
			$this->form_validation->set_rules('username', 'Пользователь', 'trim|required|min_length[3]|max_length[25]|alpha_numeric');
			$this->form_validation->set_rules('first_name', 'Имя', 'trim|min_length[2]|xss_clean');
			//$this->form_validation->set_rules('last_name', 'Фамилия', 'trim|min_length[2]|xss_clean');
			$this->form_validation->set_rules('email', 'Почтовый адрес', 'trim|required|valid_email|xss_clean');
			//$this->form_validation->set_rules('company', 'Название компании', 'trim|min_length[3]|xss_clean');
			//$this->form_validation->set_rules('phone', 'Телефонный номер', 'trim|alpha_dash');
			//$this->form_validation->set_rules('groups', 'Группа', 'required');
			//$this->form_validation->set_rules('role', 'Роль', 'required');

			if ($this->form_validation->run() == true AND $oUserData = $this->users_model->getUserById($iId)) {
				$iRoleId = ($this->input->post('role',TRUE)) ? $this->input->post('role',TRUE) : $oUserData->role_id;

				$aProfileData = array(
					'username'      => $this->input->post('username',TRUE),
					'first_name'    => $this->input->post('first_name',TRUE),
					//'last_name'   => $this->input->post('last_name',TRUE),
					'email'         => $this->input->post('email',TRUE),
					//'company'     => $this->input->post('company',TRUE),
					//'phone'       => $this->input->post('phone',TRUE),
					'role_id'       => $iRoleId
				);

				$aGroups = ($this->input->post('groups',TRUE)) ? $this->input->post('groups',TRUE) : array();
				if ($this->users_model->Update($iId, $aProfileData)) {
					if($aProfileData['role_id'] == 0) {
						$this->modules_model->removeUserModules($iId);
					}

					if($this->ion_auth->remove_from_group(false, $iId)) {
						foreach ($aGroups as $iGroupId) {
							if(!$this->ion_auth->add_to_group($iGroupId, $iId)) {
								return $aMessage = array(
									'type' => 'warning',
									'text' => 'Ошибка при добавлении в группу'
								);
							}
						}
					}

					$aMessage = array(
						'type' => 'success',
						'text' => 'Успешное сохранение профиля'
					);
				}
			} else {
				$aMessage = array(
					'type' => 'danger',
					'text' =>  validation_errors()
				);
			}
		} else {
			$aMessage = array(
				'type' => 'warning',
				'text' =>  'Ошибка при сохранении'
			);
		}

		return $aMessage;
	}

	/**
	 * Модули пользователя
	 *
	 * @param $iId
	 * @return array
	 *
	 * @author N.Kulchinskiy
	 */
	private function updateUserModules($iId){
		$aMessage = array();

		if ($iId = intval($iId) AND $iId > 0) {
			$aModules = $this->input->post('modules',TRUE);
			if($this->modules_model->removeUserModules($iId)) {
				if(!empty($aModules)) {
					foreach ($aModules as $iModuleId) {
						if(!$this->modules_model->addUserModules($iId, $iModuleId)) {
							return $aMessage = array(
								'type' => 'warning',
								'text' => 'Ошибка при добавлении модуля'
							);
						}
					}
				}
			}

			$aMessage = array(
				'type' => 'success',
				'text' => 'Успешное сохранение профиля'
			);
		}

		return $aMessage;
	}

	/**
	 * Изменение пароля
	 *
	 * @param $identity
	 * @param $sNewPassword
	 * @return bool
	 *
	 * @author N.Kulchinskiy
	 */
	private function changePassword($identity, $sNewPassword) {

		$identity_column = $this->config->item('identity', 'ion_auth');

		$oQuery = $this->db->select('id, password, salt')
			->where($identity_column, $identity)
			->limit(1)
			->get($this->db->dbprefix('users'));

		if ($oQuery->num_rows() !== 1) {
			$aMessage = array(
				'type' => 'warning',
				'text' => 'Ошибка при изменении пароля'
			);
		} else {
			$oUser = $oQuery->row();

			$sHashedNewPassword  = $this->ion_auth->hash_password($sNewPassword, $oUser->salt);
			$aData = array(
				'password' => $sHashedNewPassword,
				'remember_code' => NULL,
			);

			if ($this->users_model->Update($oUser->id, $aData)) {
				$aMessage = array(
					'type' => 'success',
					'text' => 'Пароль изменён'
				);
			} else {
				$aMessage = array(
					'type' => 'warning',
					'text' => "Неверный пароль"
				);
			}
		}

		return $aMessage;
	}
}

/* End of file users.php */
/* Location: ./application/controllers/admin/users.php */