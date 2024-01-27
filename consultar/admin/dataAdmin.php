<?php
chdir(dirname(__FILE__));
include('../conexBD.php');

class dataAdmin extends conexion
{
	public $BD;
	public $data;
	protected $mysqli;

	function __construct()
	{
		$this->mysqli = parent::connect();
		$this->data = array();
	}

	function allRolUser()
	{
		$sql = "SELECT g.id, g.name FROM `rol` g ";
		$resultado = $this->mysqli->query($sql);
		while ($columna = mysqli_fetch_assoc($resultado)) {
			$data[] = $columna;
		}

		if (isset($data)) {
			return $data;
		}
	}
	function allUser()
	{
		$sql = "SELECT g.id, g.user_name, g.active, r.name as name_rol FROM `user` g, `rol` r where g.id_rol=r.id  ORDER BY g.user_name ASC ";
		$resultado = $this->mysqli->query($sql);
		while ($columna = mysqli_fetch_assoc($resultado)) {
			$data[] = $columna;
		}

		if (isset($data)) {
			return $data;
		}
	}

	function tableUser($resulUser)
	{
		$Elegir = "Elegir";
?>
		<div class="card-box mb-30">
			<div class="pd-20">
			</div>
			<div class="pb-20">
				<table class="data-table table stripe hover nowrap">
					<thead>
						<tr>
							<th class="table-plus datatable-nosort">ID</th>

							<th>Usuario</th>

							<th>Perfil</th>

							<th>Estado</th>
							<th class="datatable-nosort">Accion</th>
						</tr>
					</thead>
					<tbody>

						<?php foreach ($resulUser as $img) { ?>
							<tr>
								<td class="table-plus"><?php echo $img['id'] ?></td>
								<!-- <td><?php echo $img['full_name'] ?></td> -->
								<td><?php echo $img['user_name'] ?></td>
								<!-- <td><?php echo $img['email'] ?></td> -->
								<td><?php echo $img['name_rol'] ?></td>
								<!-- <td><?php echo $img['create_date'] ?></td> -->
								<td><?php if ($img['active'] == 1) {
											echo 'Activo';
										} else {
											echo 'Inactivo';
										}; ?></td>
								<td>
									<div class="dropdown">
										<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
											<i class="dw dw-more"></i>
										</a>
										<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
											<?php if ($img['active'] == 1) { ?>
												<a class="dropdown-item" href="#" onclick="status(event,this,'desactivar',<?php echo $img['id'] ?>,'user')"><i class="dw dw-edit2"></i> Desactivar</a>
											<?php } else { ?>
												<a class="dropdown-item" href="#" onclick="status(event,this,'activar',<?php echo $img['id'] ?>,'user')"><i class="dw dw-edit2"></i> Activar</a>
											<?php }; ?>

											<a class="dropdown-item" href="#" onclick="showView(event,this,'EditPass',<?php echo $img['id'] ?>,'<?php echo $img['user_name'] ?>','<?php echo $img['name_rol'] ?>')"><i class="dw dw-edit2"></i> Editar</a>

											<a class="dropdown-item" href="#" onclick="eliminarUser(event,this,'deletUser',<?php echo $img['id'] ?>)"><i class="dw dw-edit2"></i> Eliminar</a>
										</div>
									</div>
								</td>
							</tr>

						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
<?php
	}

	function allExcel()
	{
		$sql = "SELECT `name_cliente` from `path` GROUP BY `name_cliente`";
		$res = $this->mysqli->query($sql);
		while ($columna = mysqli_fetch_assoc($res)) {
			$arrNameCliente[] = $columna;
		}

		foreach ($arrNameCliente as $key) {

			$data[] = self::excelCliente($key['name_cliente']);
		}

		foreach ($data as $key) {
			$x[] = $key[0];
			$i = 0;
			while ($key[$i + 1] != NULL) {
				$i++;
				$x[] = $key[$i];
			}
		}
		return $x;
	}

	function excelCliente($user_name)
	{
		//---------------mantenimiento Dic seis Genesis amaiz----------------------
		$user_name = str_replace(' ', '', $user_name);
		//-----------------------------------------------------------------------

		$sql = "SELECT COUNT(id), create_date, name_cliente FROM path WHERE path LIKE '%" . $user_name . "%' GROUP BY create_date";

		//---------------mantenimiento Nov-22 Genesis amaiz----------------------
		$sql = "SELECT COUNT(id), create_date, name_cliente FROM path WHERE path LIKE '%" . $user_name . "%' GROUP BY create_date ORDER by registre_date DESC ";

		$resultado = $this->mysqli->query($sql);

		while ($columna = mysqli_fetch_assoc($resultado)) {
			$data[] = $columna;
		}

		if (isset($data)) {
			return $data;
		}
	}

	function dataExcel($name_cliente, $create_date)
	{

		$sql = "SELECT path FROM path WHERE name_cliente='" . $name_cliente . "' AND create_date='" . $create_date . "'";

		$resultado = $this->mysqli->query($sql);
		while ($columna = mysqli_fetch_assoc($resultado)) {
			$data[] = $columna;
		}

		if (isset($data)) {
			return $data;
		}
	}

	function arrAllPathAndDataOfImgCliente($name_cliente, $create_date)
	{
		$sql = "SELECT p.path, p.hora, p.description, u.user_name as monitorista, p.hora_f FROM `path` p, `user` u WHERE p.name_cliente='" . $name_cliente . "' AND p.create_date='" . $create_date . "' AND p.id_monitor=u.id ORDER BY p.hora";
		$resultado = $this->mysqli->query($sql);
		while ($columna = mysqli_fetch_assoc($resultado)) {
			$data[] = $columna;
		}

		if (isset($data)) {
			return $data;
		}
	}

	function pathGroupByMes()
	{
		$sql = "SELECT (extract(month from `registre_date`)) as mes FROM `path` GROUP BY mes";

		$resultado = $this->mysqli->query($sql);
		while ($columna = mysqli_fetch_assoc($resultado)) {
			$data[] = $columna;
		}

		if (isset($data)) {
			return $data;
		}
	}
}
?>