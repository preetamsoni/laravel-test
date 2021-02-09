<?php

namespace App\Http\Controllers;

use App\Http\Responses\Output;
use Illuminate\Http\Response;
use UnexpectedValueException;
use Vinkla\Hashids\Facades\Hashids;

class ApiController extends Controller
{

    

    /**
     * Current status code of the given request.
     * 
     * @var integer
     */
    protected $statusCode = Response::HTTP_OK;

    

    /**
     * Get the current status code.
     * 
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Respond with a json array.
     * 
     * @param  array  $array
     * @param  array  $headers
     * @return Illuminate\Http\Response
     */
    protected function respondWithArray($data, Array $message, $code,$statusCode = 200,$activeObject=null, array $headers = [],$total_records=null)
    {
        $meta = [];
        if($activeObject)
        {
            $meta = [
                'total' => $activeObject->total(),
                'lastPage'=>$activeObject->lastPage(),
                'currentPage'=>$activeObject->currentPage(),
                'perPage'=>$activeObject->perPage()
            ];
            
        }
        $response = array(
            'timestamp' => time(),
            'status' => $statusCode,
            'code' => $code,
            'message' => $this->filterArray($message),
            'data' => $data
        );
        $headers[]=JSON_NUMERIC_CHECK;
        return response()->json($response, $statusCode, $headers);
    }

    

    protected function filterArray($myarray)
    {
        foreach ($myarray as $key => $value)
        {
            if (is_null($value) || $value == '')
                unset($myarray[$key]);
        }
        return implode(' ', array_values($myarray));
    }
    
    /**
     * 
     * @param type $hash
     * @return boolean
     */
    function decode($hash)
    {
        $id = Hashids::decode($hash);

        if (!count($id) > 0)
        {
            return false;
        }

        return (int) $id[0];
    }
    /**
     * 
     * @param type $id
     * @return type
     */
    function encode($id)
    {
        return Hashids::encode($id);
    }

    function error($error,$statusCode)
    {
        $error['status'] = 'error';
        return response()->json($error, $statusCode);
    }

    

}
