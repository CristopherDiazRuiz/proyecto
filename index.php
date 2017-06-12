<?php
	session_start();
    require 'conexion.php';
   	require 'funcs/funcs.php';
   	
	if(!isset($_SESSION["id_usuario"])){
	    header("Location: login.php");
	}
	$idUsuario = $_SESSION['id_usuario'];
	
	$sql = "SELECT id, nombre FROM usuarios WHERE id ='$idUsuario'";
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();
	
	
?>
<html lang="es">
    <head>
        
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>	
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/bootstrap-theme.css" rel="stylesheet">
		
	    <link href="css/jquery.dataTables.min.css" rel="stylesheet">	
        <script src="js/jquery.dataTables.min.js"></script>
        <script>
        	$(document).ready(function(){
        		$('#mitabla').DataTable({
        			"order": [[1, "asc"]],
        			"language":{
        				"lengthMenu": "Mostrar _MENU_ registros por pagina",
        				"info": "Mostrando pagina _PAGE_ de _PAGES_",
        				"infoEmpty": "No hay registros disponibles",
        				"infoFiltered": "(filtrada de _MAX_ registros)",
        				"loadingRecords": "Cargando...",
        				"processing":     "Procesando...",
        				"search": "Buscar:",
        				"zeroRecords":    "No existen registros con el criterio de búsqueda",
        				"paginate": {
        					"next":       "Siguiente",
        					"previous":   "Anterior"
        				},					
        			},
        			"bProcessing": true,
					"bServerSide": true,
					"sAjaxSource": "server_process.php"
        		});	
        	});	
        </script>
        <link rel="stylesheet" type="text/css" href="CSS.css"> 
        <style type="text/css"> 
            #contenedor {
              width: 95%;
            }
             table th {
              text-align: center;
            }
            #tabla {
              width: 100%;
            }
        </style> 
</head>
	<body>
		
		<div class="container" id="contenedor">
		    <div class="row fondo">
        		<div class="col-sm-12 col-md-12 col-lg-12">
        			<h1 class="text-center text-uppercase">Gestión de Contratos</h1>
        		</div>
        	</div>
			<div class="row">
			    <div class="col-md-9">
    				<a href="registrar.php" class="btn btn-primary" >Nuevo Registro</a>
    				<a href="exportar.php" class="btn btn-success" >Exportar a Excel</a>
    				<a href="importar.php" class="btn btn-warning">Importar Excel</a>
    			</div>
    			<div class="col-sm-3">
    			    <a href="index.php" class="btn btn-info" >Actualizar</a>
    				<a href="cuenta.php" class="btn btn-primary" >Cuenta</a>
    				<a href="logout.php" class="btn btn-danger">Cerrar sesión</a>
    			</div>
			</div>
			
			<br>
			<div class="row table-responsive" id="tabla">
                <table class="display" id="mitabla">
					<thead>
					    
						<tr>
						    <th>Vigencia</th>
							<th>Nombre</th>
							<th>Marca</th>
							<th>Modelo</th>
							<th>Serie</th>
							<th>Proveedor</th>
							<th>Tipo de Convenio</th>
							<th>Fecha de Inicio</th>
							<th>Fecha de Termino</th>
							<th>Licitacion</th>
							<th>Contrato</th>
							<th>Orden de Compra</th>
							<th>Acciones</td>
						</tr>
					</thead>
					
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
		
		<!-- Modal -->
		<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel">Eliminar Registro</h4>
					</div>
					
					<div class="modal-body">
						¿Estas seguro que deseas eliminar este registro?
					</div>
					
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						<a class="btn btn-danger btn-ok">Eliminar</a>
					</div>
				</div>
			</div>
		</div>
		
		<script>
			$('#confirm-delete').on('show.bs.modal', function(e) {
				$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
				
				$('.debug-url').html('Delete URL: <strong>' + $(this).find('.btn-ok').attr('href') + '</strong>');
			});
		</script>	
		
	</body>
</html>
