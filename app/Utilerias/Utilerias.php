<?php


namespace App\Utilerias;

use App\Repository\Actions\CategoriaFolioRepoAction;
use Hidehalo\Nanoid\Client;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\MessageBag;
use DateTime;
use stdClass;
use Imagick;
use ImagickPixel;
use Exception;
use App\Services\Actions\Storage\ArchivoSubirS3;
use App\Services\Actions\Storage\StorageService;
use App\Services\Data\CategoriaFolioServiceData;
use Predis\Client AS redisClient;
use NinjaMutex\Mutex;
use NinjaMutex\Lock\PredisRedisLock;
use Carbon\Carbon;
use Litipk\BigNumbers\Decimal;
use App\Constantes;
use App\Constantes\StorageRutas;


class Utilerias
{
    public static function generateId(): string
    {
        $nanoId   = new Client();
        $alphabet = "-0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

        return $nanoId->formatedId($alphabet, 10);
    }

    public static function generateUuidv4()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        // 16 bits for "time_mid"
        mt_rand(0, 0xffff),
        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand(0, 0x0fff) | 0x4000,
        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand(0, 0x3fff) | 0x8000,
        // 48 bits for "node"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    public static function now()
    {
        $timeZone = env('APP_TIMEZONE');
        return Carbon::now(new \DateTimeZone($timeZone))->format('Y-m-d H:i:s');
    }

    public static function obtenerMensajesValidator(MessageBag $excepciones): string
    {
        $mensajes = '';

        foreach ($excepciones->all() as $excepcion)
            $mensajes .= $excepcion . '<br>';

        return $mensajes;
    }

    public static function generarPassword(string $pass): string
    {
        $salt = env('APP_SALT');
        return md5($pass . $salt);
    }

    /**
     * Función que genera una condcion en el SQL para filtrar fechas
     *
     * @param Builder $query
     * @param         $fechaInicio
     * @param         $fechaFin
     * @param string $campo
     */
    public static function buildFechasQuery(&$query, $fechaInicio, $fechaFin, string $campo)
    {
        if (!empty($fechaInicio) && !empty($fechaFin)) {
            $query->whereBetween($campo, [$fechaInicio . ' ' . '00:00:00', $fechaFin . ' ' . '23:59:59']);
        }

        if (!empty($fechaInicio) && empty($fechaFin)) {
            $query->where($campo, '>=', $fechaInicio . ' ' . '00:00:00');
        }

        if (empty($fechaInicio) && !empty($fechaFin)) {
            $query->where($campo, '<=', $fechaFin . ' ' . '23:59:59');
        }
    }

    /**
     * Función que genera una condcion en el SQL para filtrar fechas
     *
     * @param Builder $query
     * @param         $fechaInicio
     * @param         $fechaFin
     * @param string $campo
     */
    public static function buildFechasQueryConHora(&$query, $fechaInicio, $fechaFin, string $campo)
    {
        if (!empty($fechaInicio) && !empty($fechaFin)) {
            $query->whereBetween($campo, [$fechaInicio , $fechaFin ]);
        }

        if (!empty($fechaInicio) && empty($fechaFin)) {
            $query->where($campo, '>=', $fechaInicio);
        }

        if (empty($fechaInicio) && !empty($fechaFin)) {
            $query->where($campo, '<=', $fechaFin);
        }
    }

    /**
     * Función que genera una condcion en el SQL para filtrar fechas
     *
     * @param Builder $query
     * @param         $fechaInicio
     * @param         $fechaFin
     * @param string $campo
     */
    public static function buildFechaHoraQuery(&$query, $fechaInicio, $fechaFin, string $campo)
    {
        if (!empty($fechaInicio) && !empty($fechaFin)) {
            $query->whereBetween($campo, [$fechaInicio, $fechaFin]);
        }

        if (!empty($fechaInicio) && empty($fechaFin)) {
            $query->where($campo, '>=', $fechaInicio);
        }

        if (empty($fechaInicio) && !empty($fechaFin)) {
            $query->where($campo, '<=', $fechaFin);
        }
    }

    /**
     * Retorna un arreglo para las consultas de status con el whereIn
     *
     * @param $status
     * @return string
     */
    public static function buildStatusArray($status): array
    {
        return explode(',', $status);
    }

    /**
     * Metodo para obtener armado de tamaño de archivo para humano
     * @param $bytes
     */
    public static function obtenerTamanioArchivoHumano($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . '' . $units[$i];
    }

    /**
     * Formatea los numeros para factura
     *
     * @param $cantidad
     * @param int $decimales
     * @return string
     */
    public static function fmtDecFactura($cantidad, $decimales = 2): string
    {
        return sprintf('%.' . $decimales . 'f', $cantidad);
    }

    /**
     * Rellena con ceros a la derecha alguna cantidad deseada
     *
     * @param float $numero El numero a rellenar con zeros
     * @param int   $ceros cantidad de ceros
     * @return string
     */
    public static function zeroFill($numero, int $ceros = 5): string
    {
        return sprintf('%0' . $ceros . 'd', $numero);
    }

    /**
     * Método para reemplazar los espacios en blanco y * de una cadena por %
     * @param $cadena
     * @return string
     */
    private static function stringReplace($cadena): string
    {
        $cadenaNueva = str_replace(' ', '%', $cadena);
        $cadenaNueva = str_replace('*', '%', $cadenaNueva);

        return $cadenaNueva;
    }

    /**
     * Método para realiza la búsqueda iLike
     * @param $busqueda
     * @param int $arrayLength
     * @return array
     */
    public static function busquedaIlike($busqueda, int $arrayLength): array
    {
        $busquedaArray = [];
        for ($i = 0; $i < $arrayLength; $i++)
            $busquedaArray[] = "%" . self::stringReplace($busqueda) . "%";

        return $busquedaArray;
    }

    /**
     * Método para sumar dias a una fecha sin considerar el tiempo de la fecha
     * @param $fecha
     * @param $numeroSuma
     * @return string
     */
    public static function sumDiasAFechaSinTiempo($fecha, $numeroSuma)
    {
        return Carbon::create($fecha)->addDays($numeroSuma)->format('Y-m-d');
    }

    public static function restDiasAFechaSinTiempo($fecha, $numeroSuma)
    {
        return Carbon::create($fecha)->subDays($numeroSuma)->format('Y-m-d');
    }

    /**
     * Método para restar meses a una fecha sin considerar el tiempo de la fecha
     * @param $fecha
     * @param $numeroSuma
     * @return string
     */
    public static function restMesesAFechaSinTiempo($fecha, $numeroResta)
    {
        return Carbon::create($fecha)->subMonths($numeroResta)->format('Y-m-d');
    }

    /**
     * Método para sumar meses a una fecha sin considerar el tiempo de la fecha
     * @param $fecha
     * @param $numeroSuma
     * @return string
     */
    public static function sumMesesAFechaSinTiempo($fecha, $numeroSuma)
    {
        return Carbon::create($fecha)->addMonths($numeroSuma)->format('Y-m-d');
    }

    /**
     * * Método para obtener el maximo de un folio con y sin ceros
     * siguiendo la numeración 0001 - 9999
     * @param $nombreTabla
     * @param $nombreColumnaFolio
     * @param string $nombreColumnaSerie
     * @param string $serie [string - serie a buscar si validacionSerie es true]
     * @param false $validacionSerie [boolean - si es verdadero aplica where = $nombreColumnaSerie]
     * @return stdClass
     */
    public static function obtenerFolioSerieMax($nombreTabla, $nombreColumnaFolio, $nombreColumnaSerie = "", $serie = "", $validacionSerie = false)
    {
        $respuesta = new stdClass();

        if (!$validacionSerie)
            $query = DB::table($nombreTabla)->max($nombreColumnaFolio);
        else {
            $query = DB::table($nombreTabla)
                ->where($nombreColumnaSerie, $serie)
                ->max($nombreColumnaFolio);
        }

        $max = $query + 1;

        $respuesta->folio = $max;
        $respuesta->folioConCeros = str_pad($max, 4,'0',STR_PAD_LEFT);//agrega 0 a la izquierda 4 posiciones

        return $respuesta;
    }

    /**
     * * Método para obtener el maximo de un folio con y sin ceros
     * siguiendo la numeración 0001 - 9999
     * @param $nombreTabla
     * @param $nombreColumnaFolio
     * @param string $nombreColumnaSerie
     * @param string $serie [string - serie a buscar si validacionSerie es true]
     * @param false $validacionSerie [boolean - si es verdadero aplica where = $nombreColumnaSerie]
     * @return stdClass
     */
    public static function obtenerFolio($nombreTabla, $nombreColumnaFolio, $nombreColumnaSerie = "", $serie = "", $validacionSerie = false)
    {
        if (!$validacionSerie)
            $query = DB::table($nombreTabla)->max($nombreColumnaFolio);
        else {
            $query = DB::table($nombreTabla)
                ->where($nombreColumnaSerie, $serie)
                ->max($nombreColumnaFolio);
        }

        if ($query == 0) {
            $max = 1001;
        }else{
            $max = $query + 1;
        }

        return $max;
    }

    public static function fechaActualMasMilisegundos()
    {
        $timeZone = env('APP_TIMEZONE');
        return Carbon::now(new \DateTimeZone($timeZone))->format('Y-m-d H:i:s.u');
    }


    /**
     * Método el cual envías una string y regresa la string limpia de caracteres especiales
     * @param $cadena
     * @return false|string
     */
    public static function limpiarCadena($cadena)
    {
        $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿ';
        $modificadas = 'AAAAAAACEEEEIIIIDNOOOOOOUUUUYbsaaaaaaaceeeeiiiidnoooooouuuyyby';
        $cadena = utf8_decode($cadena);
        $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
        return utf8_encode($cadena);
    }

    public static function obtenerMensajeLogEndpoint($mensaje)
    {
        $fecha      = self::fechaActualMasMilisegundos();
        $mensajeLog =  $fecha . " - " . " INFO  -->  " . $mensaje . "\n";
        return $mensajeLog;
    }

    public static function groupBy($array, $column)
    {
        return array_reduce($array, function ($accumulator, $item) use ($column) {
            $key = (is_callable($column)) ? $column($item) : $item[$column];
            if (!array_key_exists($key, $accumulator)) {
                $accumulator[$key] = [];
            }

            array_push($accumulator[$key], $item);
            return $accumulator;
        }, []);
    }

    public static function validarFecha($fecha): bool
    {
        $tempDate = explode('/', $fecha);
        if (count($tempDate) == 1) return false;
        if (strlen($tempDate[2]) > 4) $tempDate[2] = explode(' ', $tempDate[2])[0];

        $validCheckdate = checkdate(intval($tempDate[1]), intval($tempDate[0]), intval($tempDate[2]));
        $validStrtotime =  (bool)strtotime($fecha);

        return $validCheckdate && $validStrtotime;
    }

    public static function obtenerMesFullName(string $format)
    {
        switch ($format) {
            case '1':
                return 'Enero';
            case '2':
                return 'Febrero';
            case '3':
                return 'Marzo';
            case '4':
                return 'Abril';
            case '5':
                return 'Mayo';
            case '6':
                return 'Junio';
            case '7':
                return 'Julio';
            case '8':
                return 'Agosto';
            case '9':
                return 'Septiembre';
            case '10':
                return 'Octubre';
            case '11':
                return 'Noviembre';
            case '12':
                return 'Diciembre';
        }
    }

    public static function obtenerMesName(string $format)
    {
        switch ($format) {
            case '1':
                return 'Ene';
            case '2':
                return 'Feb';
            case '3':
                return 'Mar';
            case '4':
                return 'Abr';
            case '5':
                return 'May';
            case '6':
                return 'Jun';
            case '7':
                return 'Jul';
            case '8':
                return 'Ago';
            case '9':
                return 'Sep';
            case '10':
                return 'Oct';
            case '11':
                return 'Nov';
            case '12':
                return 'Dic';
        }
    }

    /**
     * Método para dar formato fecha
     * @param null $fecha ('2020-01-17 16:08:51') o si llega null, toma fecha actual
     * @param int $formato
     * @param bool $showHora
     * @return mixed|string|null
     * @throws Exception
     */
    public static function dateFormat($fecha = null, $formato = 1, $showHora = true)
    {
        $timeZone = env('APP_TIMEZONE');
        $dateTime = new Carbon($fecha != null ? $fecha : Carbon::now(new \DateTimeZone($timeZone)));
        $hora     = $dateTime->format('H:i:s');
        switch ($formato) {
            // yyyy-mm-dd
            case 1:
                $fecha = $dateTime->format('Y-m-d');
                break;
            // dd/mmm/yy (nombre_mes solo 3 caracteres iniciales)
            case 2:
                $dia  = $dateTime->format('d');
                $mes  = $dateTime->format('m');
                $mesNombre = self::obtenerMesFullName($mes);
                $anio = $dateTime->format('y');
                $fecha = $dia . "/" . substr($mesNombre, 0, 3) . "/" . $anio;
                break;
            // dd/mmm/yy (mes completo)
            case 3:
                $dia  = $dateTime->format('d');
                $mes  = $dateTime->format('m');
                $mesNombre = self::obtenerMesFullName($mes);
                $anio = $dateTime->format('y');
                $fecha = $dia . "/" . $mesNombre . "/" . $anio;
                break;
            // dd de mes_nombre del yyyy
            case 4:
                $dia  = $dateTime->format('d');
                $mes  = $dateTime->format('m');
                $mesNombre = self::obtenerMesFullName($mes);
                $anio = $dateTime->format('Y');
                $fecha = $dia . " de " . $mesNombre . " del " . $anio;
                break;
            // yyyy-mm
            case 5:
                $fecha = $dateTime->format('Y-m');
                break;
            //nombre_mes_completo - año
            case 6:
                $anio = $dateTime->format('Y');
                $mes = $dateTime->format('m');
                $mesNombre = self::obtenerMesFullName($mes);
                $fecha = $mesNombre . "-" . $anio;
                break;
            //09 de enero 2021
            case 7:
                $dia = $dateTime->format('d');
                $mes = $dateTime->format('m');
                $anio = $dateTime->format('Y');
                $mesNombre = self::obtenerMesFullName($mes);
                $fecha = $dia . ' de ' . $mesNombre . ' ' . $anio;
                break;
            // dd-mm-yyyy
            case 8:
                $fecha = $dateTime->format('d-m-Y');
                break;
        }

        if ($showHora) $fecha = $fecha . " " . $hora;

        return $fecha;
    }

     /**
     * Método para calcular tiempo entre dos fechas
     * @param         $fechaI
     * @param         $fechaF ('2022-10-06 17:00:00') si llega null, toma fecha actual
     * @param string  $tipoRespuesta
     */
    public static function calcularDiffFechas($fechaI, $fechaF = null, $tipoRespuesta = '')
    {
        $fechaI = new DateTime($fechaI);
        $fechaF = new DateTime($fechaF != null ? $fechaF : Carbon::now(new \DateTimeZone(env('APP_TIMEZONE'))));

        $diff = $fechaI->diff($fechaF);

        // Se queda estos logs para que sepan los formatos que hay
        // Log::info($diff->days.' days total<br>');
        // Log::info($diff->y.' years<br>');
        // Log::info($diff->m.' months<br>');
        // Log::info($diff->d.' days<br>');
        // Log::info($diff->h.' hours<br>');
        // Log::info($diff->i.' minutes<br>');
        // Log::info($diff->s.' seconds<br>');

        switch($tipoRespuesta) {
            // Días transcurridos
            case 'd':
                $valor = $diff->d;
                break;
            // Horas transcurridas día actual (0-23)
            case 'h':
                $valor = $diff->h;
                break;
            // Minutos transcurridos día actual (0-59)
            case 'i':
                $valor = $diff->i;
                break;
            // Total de horas (días+horas+minutos | 123.12)
            case 'totalHoras':
                $minutosPorDias = $diff->d*1440;
                $minutosPorHora = $diff->h*60;
                $minutos        = $diff->i;
                $tiempoTotal    = $minutosPorDias + $minutosPorHora + $minutos;
                $valor = round($tiempoTotal/60, 2);
                break;
            default:
                $valor = $diff;
        }

        return $valor;
    }

    public static function remplazarAcentos($cadena)
    {
        //Reemplazamos la A y a
        $cadena = str_replace(
            array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
            array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
            $cadena
        );

        //Reemplazamos la E y e
        $cadena = str_replace(
            array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
            array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
            $cadena
        );

        //Reemplazamos la I y i
        $cadena = str_replace(
            array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
            array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
            $cadena
        );

        //Reemplazamos la O y o
        $cadena = str_replace(
            array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
            array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
            $cadena
        );

        //Reemplazamos la U y u
        $cadena = str_replace(
            array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
            array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
            $cadena
        );

        //Reemplazamos la N, n, C y c
        $cadena = str_replace(
            array('Ñ', 'ñ', 'Ç', 'ç'),
            array('N', 'n', 'C', 'c'),
            $cadena
        );

        return $cadena;
    }

    public static function remplazarCaracteresNumero($numero)
    {
        $numero = str_replace(
            array('$', ',', ' ', '-'),
            '',
            $numero
        );

        $numero = floatval($numero);

        return $numero;
    }


    /**
     * Método para ordenar array de objetos respecto a un campo
     * @param $arrIni
     * @param $col
     * @param int $order
     * @return mixed
     */
    public static function arraySortBy(&$arrIni, $col, $order = SORT_ASC)
    {
        $arrAux = array();
        foreach ($arrIni as $key => $row) {
            $arrAux[$key] = is_object($row) ? $arrAux[$key] = $row->$col : $row[$col];
            $arrAux[$key] = strtolower($arrAux[$key]);
        }
        array_multisort($arrAux, $order, $arrIni);

        return $arrIni;
    }

    public static function eliminarEspaciosCaracter($cadena,$caracter){
        $cadenaSinCaracter = str_replace($caracter,'',$cadena);
        $cadenaSinEspacios = str_replace(' ','',$cadenaSinCaracter);
        return $cadenaSinEspacios;
    }

    /**
     * Metodo que elimina todos los elementos vacios en un arreglo dado
     * @param array $datos
     * @return array
     */
    public static function eliminarElementosVacios(array $datos){
        foreach($datos as $index => $valor){
            if(empty($datos[$index]))
                unset($datos[$index]);
        }

        return $datos;
    }

    /** Lunes (1) a domingo (7) formato ISO
     * @param fecha Carbon object
     * @return int
     */
    public static function obtenerDiaSemana($fecha = null){
      $fecha = empty($fecha) ? Carbon::now()->tz(env('APP_TIMEZONE')) : $fecha;

      return $fecha->dayOfWeekIso;
    }

    /** Lunes (1) a domingo (7) formato ISO
     * @param  int dia
     * @return string
     */
    public static function obtenerNombreDiaSemana($dia = 1){
      
      $nombresDiasSemana = [
        1 => 'lunes',
        2 => 'martes',
        3 => 'miercoles',
        4 => 'jueves',
        5 => 'viernes',
        6 => 'sabado',
        7 => 'domingo',
      ];

      return $nombresDiasSemana[$dia] ?? 'no match';
    }


    /**
     * Metodo que obtiene una instancia de mutex
     * @param string $mutexName nombre que llevara el mutex
     */
    public static function getMutex(string $mutexName = 'mutex-default'){
      $client =new redisClient([
        'scheme' => 'tcp',
        'host'   => env('REDIS_HOST'),
        'port'   => env('REDIS_PORT'),
        "password" => env('REDIS_PASSWORD')
      ]);
      $lock = new PredisRedisLock($client);

      return new Mutex($mutexName, $lock);
    }

    /**
     * * Método para obtener el maximo de un folio con y sin ceros
     * siguiendo la numeración 0001 - 9999
     * @param $nombreTabla
     * @param $nombreColumnaFolio
     * @param string $nombreColumnaSerie
     * @param string $serie [string - serie a buscar si validacionSerie es true]
     * @param false $validacionSerie [boolean - si es verdadero aplica where = $nombreColumnaSerie]
     * @return stdClass
     */
    public static function obtenerOrdenMax($nombreTabla, $nombreColumna, $nombreColumnaWhere = "", $condicion)
    {
        $respuesta = new stdClass();

        $query = DB::table($nombreTabla)
            ->where($nombreColumnaWhere, $condicion)
            ->max($nombreColumna);

        $max = $query + 1;

        $respuesta->max = $max;
        $respuesta->maxConCeros = str_pad($max, 4,'0',STR_PAD_LEFT);//agrega 0 a la izquierda 4 posiciones

        return $respuesta;
    }

    public static function getCarpetaFolio($folio,$rangoInicial = 1,$chunk = 500){
      $encontrado = false;
      $rangoFinal = $rangoInicial;
      $rangoFinal += $chunk;
      do {

        if(self::isBetween($folio,$rangoInicial,$rangoFinal)){
          $encontrado = true;
          return self::getNombreCarpetaFolio($rangoInicial,$rangoFinal);
        }
        $rangoInicial += $chunk;
        $rangoFinal += $chunk;
      } while (!$encontrado);
    }

    private function isBetween($comparar,$rangoInicial,$rangoFinal){
      return $comparar > $rangoInicial && $comparar <= $rangoFinal;
    }

		/**
	 * Método para obtener el folio por tipo y actualizar en su respectivo row
	 * @param array $datos
	 * @throws Exception
	 */
	public static function getSetCategoriaFolio(string $tipoFolio)
	{
		try {
			$folio = CategoriaFolioServiceData::obtenerSiguienteFolio($tipoFolio);
			CategoriaFolioRepoAction::actualizarCategoriaFolio($folio, $tipoFolio);
			return $folio;
		} catch (Exception $e) {
			Log::error("Problema en servicio servicio get set categoria folio => " . $e->getMessage());
			throw new Exception("Problema en servicio get set categoria folio");
		}
	}

    
    /** Método para obtener cadena con cierta longitud de acuerdo a un grupo de caracteres permitidos
     * @param int $longitud
     * @param string $caracteres
     * @return string
     */
    public static function generarCadenaRandom($longitud = 3, $caracteres = null)
    {
        $caracteresDefault = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";

        $caracteresCadena = $caracteres != null ? $caracteres : $caracteresDefault;

        $longitudCadena = strlen($caracteresCadena);

        $cadenaRandom = '';

        for($i = 0; $i < $longitud; $i++) {
            $char = $caracteresCadena[random_int(0, $longitudCadena - 1)];
            $cadenaRandom .= $char;
        }

        return $cadenaRandom;
    }
    
    
    /**
     * obtenerValorNumericoSinCerosIzquierda
     *
     * @param  mixed $valor
     * @return string
     */
    public static function obtenerValorNumericoSinCerosIzquierda(string $valor): string
    {
        $numerosValidos = ['1','2','3','4','5','6','7','8','9'];

        $val = str_ireplace($numerosValidos, 'X', $valor);
        $lon = strlen($val);
        echo "\n longitud del remplazo -> $lon";

        $numeros = "1";
        $longitud = strlen($valor);
        echo "\n longitud -> $longitud";
        $encontrado = stristr($valor, $numeros, true);
        echo "\n valor encontrado -> $encontrado";
        echo "\n" . "$valor";

        $posicion = strpos($val, 'X', 0);
        echo "\n posicion -> $posicion";
        echo "\n valor posicion  > $val[$posicion]";

        $valorCortado = substr($valor, $posicion, $longitud);
        echo "\n valor cortado real -> $valorCortado";
        $valorCortadoReemplazo = substr($val, $posicion, $lon);
        echo "\n valor cortado reemplazo -> $valorCortadoReemplazo";

        return $valorCortado;
    }

    /**
     * obtenerValorNumericoSinCerosIzquierda
     *
     * @param  mixed $valor
     * @return string
     */
    public static function obtenerValorSinEspaciosDerecha(string $valor): string
    {
        echo "\n valor -> $valor";
        echo "\n longitud de valor -> " . strlen($valor);

        $valor = rtrim($valor);
        echo "\n valor nuevo -> $valor";
        echo "\n longitud de valor nuevo -> " . strlen($valor);

        return $valor;
    }
    
    /**
     * esNumero
     *
     * @param  mixed $valor
     * @return bool
     */
    public static function esNumeroEntero(string $valor): bool
    {
        $esNumero = ctype_digit($valor);
        $mensaje  = $esNumero ? "si" : "no";
        return $esNumero;
    }
        
    /**
     * esMismaLongitudCadenas
     *
     * @param  string $valorA
     * @param  string $valorB
     * @return bool
     */
    public static function esMismaLongitudCadenas(string $valorA, string $valorB): bool
    {
        echo "\n longitud A -> " . strlen($valorA);
        echo "\n longitud B -> " . strlen($valorB);

        $esMismaLongitud = strlen($valorA) == strlen($valorB);
        $mensaje  = $esMismaLongitud ? "si" : "no";
        echo "\n misma longitud -> $mensaje";

        return $esMismaLongitud;
    }
    
    /**
     * completarValor a la izq o derecha de acuerdo a una longitud y caractere para completar
     *
     * @param  mixed $valor
     * @param  mixed $valorCompletar
     * @param  mixed $tipo
     * @param  mixed $longitudTotal
     * @return string
     */
    public static function completarValor(
        string $valor, string $valorCompletar, string $tipo, int $longitudTotal): string
    {
        if ($tipo == 'izquierda') {
            $nuevoValor = str_pad($valor, $longitudTotal, $valorCompletar, STR_PAD_LEFT);
        } elseif ($tipo == 'derecha') {
            $nuevoValor = str_pad($valor, $longitudTotal, $valorCompletar, STR_PAD_RIGHT);
        } else {
            throw new Exception('Error desconocido');
        }
        // echo "\n nvalor -> $nuevoValor";

        return $nuevoValor;
    }
    
    /**
     * Utileria que creaa objeto Carbon para manejarlo en el service que lo consume
     * @param  string $fecha
     * @return Carbon
     */
    public static function obtenerObjetoCarbon($fecha = null): Carbon
    {
        $timeZone = env('APP_TIMEZONE');
        $dateTime = $fecha != null ? Carbon::create($fecha) : Carbon::now(new \DateTimeZone($timeZone));

        return $dateTime;
    }

    /**
     * Utileria que crea hash igual al del FRONT
     * @param string $cadena
     * @return string
     */
    public static function getHash(string $cadena) : string
    {
        $longitud = 5;
        $hash = hash('sha256', $cadena);

        $hashCorto = substr($hash, strlen($hash) - $longitud, $longitud);

        return $hashCorto;
    }

  /**
   * Método para encriptar o desencriptar un password
   * @param string $password
   * @param bool $encriptar
   */
  public static function encriptarDesenciptarPasword(string $password, bool $encriptar=true): string
  {
    $clave = env('APP_SALT');
    $metodoCifrado = 'aes-256-cbc';
    $modo = 'iv_de_16_bytes';
    if($encriptar){
      return openssl_encrypt($password, $metodoCifrado, $clave, 0, $modo);
    } else {
      return openssl_decrypt($password, $metodoCifrado, $clave, 0, $modo);
    }
  }

  /**
   * Método para sumar dias a una fecha considerando el tiempo de la fecha
   * @param $fecha
   * @param $numeroSuma
   * @return string
   */
  public static function sumDiasAFechaConTiempo($fecha, $numeroSuma)
  {
    return Carbon::create($fecha)->addDays($numeroSuma)->format('Y-m-d H:i:s');
  }

    /**
     * Método para sumar dias a una fecha sin considerar el tiempo de la fecha
     * @param $fecha
     * @param $numeroSuma
     * @return string
     */
    public static function sumSegundosAFechaConTiempo($fecha, $numeroSuma)
    {
        return Carbon::create($fecha)->addSeconds($numeroSuma)->format('Y-m-d H:i:s');
        //$NuevaFecha = strtotime ( '+30 second' , $NuevaFecha ) ; 
    }

    /**l
     * Utileria para manejar fechas
     * @param string $zonaHoraria Zona horaria del cliente
     * @return string $fechaActual
     */
    public static function fechaActual(string $zonaHoraria)
    {

        $fechaActual = Carbon::now($zonaHoraria)->toDateTimeString();

        return $fechaActual;
    }

}
