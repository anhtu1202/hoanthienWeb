<?php 
	$filepath = realpath(dirname(__FILE__));
	require_once ($filepath.'/../lib/database.php');
	require_once ($filepath.'/../helper/format.php'); 
?>
<?php 
	/**
	 * 
	 */
	class taikhoan
	{
		private $db;
		private $fm;

		public function __construct()
		{
			$this->db = new Database();	
			$this->fm = new Format();
		}

		public function show_tk()
		{
			$query = "SELECT * FROM taikhoan ORDER BY id_tk DESC";
				$result = $this->db->select($query);
				if ($result) {
					return $result;
				}
		}	

		public function get_taikhoan($id_tk)
		{
			$query = "SELECT * FROM taikhoan WHERE id_tk = '$id_tk'";
				$result = $this->db->select($query);
				if ($result) {
					return $result;
				}
		}

		public function update_taikhoan($id_tk,$data,$files)
		{
			$name = mysqli_real_escape_string($this->db->link, $data['name']);
			$email = mysqli_real_escape_string($this->db->link, $data['email']);
			$pass = mysqli_real_escape_string($this->db->link, $data['pass']);

			//Kiểm tra hình ảnh & đưa vào folder uploads
			$permited = array('jpg','png','svg','webp');
			$file_name = $_FILES['image']['name'];
			$file_size = $_FILES['image']['size'];
			$file_temp = $_FILES['image']['tmp_name'];

			$file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
			$unique_image = mt_rand(100,10000).".".$file_ext;
			$uploaded_image = "admin/uploads/".$unique_image;

			if ($pass != null) {
				$check = preg_match('/\w/',$password);
				if ($check) {
					$alert = "<span class='error'>Password không chứa kí tự đặc biệt!</span>
					<script type='text/javascript'>
					    $(document).ready(function(){
					        $('#dangki').modal();
					    });
					</script>";
				return $alert;
				}
				$password = md5($pass);
				$query = "UPDATE taikhoan SET pass='$password' WHERE id_tk='$id_tk'";
				$result = $this->db->update($query);
				if ($result) {
					$alert = "<span class='success'>Thay đổi tài khoản thành công!</span>";
					return $alert;
				}
			}
			if ($file_name != "") {

			if ($file_ext != $permited[0] && $file_ext != $permited[1] && $file_ext != $permited[2] && $file_ext != $permited[3]) {
				$alert = "<span class='error'>File tải lên không đúng định dạng!</span>";
				return $alert;
			}else if ($file_size > 8388608) {
				$alert = "<span class='error'>Dung lượng file quá lớn</span>";
				return $alert;
			}else{
			move_uploaded_file($file_temp, $uploaded_image);
				$query = "UPDATE taikhoan 
				SET name='$name',image_tk='$unique_image',email='$email'
				WHERE id_tk='$id_tk'";
				$result = $this->db->update($query);
				if ($result) {
					$alert = "<span class='success'>Thay đổi tài khoản thành công!</span>";
					return $alert;
				}
			}
			}
			$query = "UPDATE taikhoan 
				SET name='$name',email='$email' WHERE id_tk='$id_tk'";
				$result = $this->db->update($query);
				if ($result) {
				$alert = "<span class='success'>Thay đổi tài khoản thành công!</span>";
					return $alert;
				}
		}	
			
	}
 ?>
 