<?php
require_once '../models/Database.php';
require_once '../models/Reporte.php';
require_once 'logController.php'; // Registrar la acción de generar reporte
require_once '../libs/fpdf.php'; // Librería para generar PDF (si se usa FPDF)
require_once '../libs/PhpSpreadsheet.php'; // Librería para Excel (si se usa PhpSpreadsheet)

$database = new Database();
$db = $database->getConnection();
$reporte = new Reporte($db);

$action = isset($_GET['action']) ? $_GET['action'] : '';

// Control de acciones para generar y mostrar reportes
switch ($action) {
    // Mostrar el formulario de selección de reportes
    case 'formulario':
        include('../views/reportes/formulario.php');
        break;

    // Generar el reporte basado en el módulo seleccionado y tipo de salida
    case 'generar':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $modulo = $_POST['modulo'];
            $tipo_reporte = $_POST['tipo_reporte'];
            $datos = [];

            // Obtener datos según el módulo seleccionado
            switch ($modulo) {
                case 'ventas':
                    $datos = $reporte->reporteVentas();
                    break;
                case 'compras':
                    $datos = $reporte->reporteCompras();
                    break;
                case 'stock':
                    $datos = $reporte->reporteStockProductos();
                    break;
                case 'inventario':
                    $datos = $reporte->reporteInventario();
                    break;
                case 'productos_categoria':
                    $datos = $reporte->reporteProductosPorCategoria();
                    break;
                case 'ventas_mes':
                    $datos = $reporte->reporteVentasPorMes();
                    break;
                case 'compras_mes':
                    $datos = $reporte->reporteComprasPorMes();
                    break;
                default:
                    header("Location: ../views/reportes/formulario.php?mensaje=Seleccione un módulo válido.");
                    exit();
            }

            // Verificar el tipo de reporte
            if ($tipo_reporte == 'pdf') {
                generarReportePDF($datos, $modulo);
            } elseif ($tipo_reporte == 'excel') {
                generarReporteExcel($datos, $modulo);
            }

            // Registrar la acción de generación de reporte en el log
            registrarAccion($_SESSION['user_id'], "Generar", "Reportes", 0);
        }
        break;

    // Vista por defecto: formulario de selección
    default:
        header("Location: ../views/reportes/formulario.php");
        break;
}

// Función para generar reporte en PDF usando FPDF (debe estar configurada previamente)
function generarReportePDF($datos, $modulo) {
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, "Reporte de " . ucfirst($modulo), 0, 1, 'C');

    // Encabezado del reporte
    foreach ($datos[0] as $key => $value) {
        $pdf->Cell(40, 10, ucfirst($key), 1);
    }
    $pdf->Ln();

    // Datos del reporte
    foreach ($datos as $fila) {
        foreach ($fila as $columna) {
            $pdf->Cell(40, 10, $columna, 1);
        }
        $pdf->Ln();
    }

    $pdf->Output("D", "Reporte_" . $modulo . ".pdf"); // Descargar el PDF
    exit();
}

// Función para generar reporte en Excel usando PhpSpreadsheet (debe estar configurada previamente)
function generarReporteExcel($datos, $modulo) {
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Establecer encabezados
    $col = 1;
    foreach ($datos[0] as $key => $value) {
        $sheet->setCellValueByColumnAndRow($col, 1, ucfirst($key));
        $col++;
    }

    // Insertar datos
    $fila = 2;
    foreach ($datos as $registro) {
        $col = 1;
        foreach ($registro as $dato) {
            $sheet->setCellValueByColumnAndRow($col, $fila, $dato);
            $col++;
        }
        $fila++;
    }

    // Descargar el archivo Excel
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Reporte_' . $modulo . '.xlsx"');
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit();
}
?>
