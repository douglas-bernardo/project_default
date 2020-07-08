<?php
namespace Services\Database;

use Livro\Widgets\Dialog\Message;
use Exception;

abstract class ActiveRecord
{
    /** @var object|null */
    protected $data;

    /** @var \Exception|null */
    protected static $fail;

    /** @var string|null */
    protected $message;

    /** @var string */
    private static $sql;

    /** @var int|null */
    protected static $nresults;

    public function __construct(string $sql) {
        self::$sql = $sql;
    }

    /**
     * Undocumented function
     *
     * @return object|null
     */
    public function data(): ?object
    {
        return $this->data;
    }

    /**
     * Undocumented function
     *
     * @return Exception|null
     */
    public function fail(): ?\Exception
    {
        return $this->fail;
    }

    /**
     * Undocumented function
     *
     * @return string|null
     */
    public function message(): ?string
    {
        return $this->message;
    }

    public static function all(): ?array
    {
        try {

            $results = array();
            $conn = ConnectOracle::connectOracleDB();            
            //conversão dos campos com formato de data
            $stmt = ConnectOracle::parse($conn, "ALTER SESSION SET NLS_DATE_FORMAT = 'YYYY-MM-DD'");
            ConnectOracle::execute($stmt);

            $stmt = ConnectOracle::parse($conn, self::$sql);

            if (!ConnectOracle::execute($stmt)) {
                ConnectOracle::closeCMConnection();
                throw new Exception("Erro na execução");
            }           

           $rows = oci_fetch_all($stmt, $results, null, null, OCI_FETCHSTATEMENT_BY_ROW);

            // if ($rows>0) {
            //    self::$nresults = $rows;
            // }

            // while (($row = oci_fetch_array($stmt, OCI_ASSOC)) != false) {
            //     $results[] = $row;
            // }
            
            self::$nresults = count($results);

            ConnectOracle::closeCMConnection();
            
            return $results;

        } catch (Exception $exeption) {
            new Message('warning', $exeption->getMessage());
            return null;
        }
    }

}
