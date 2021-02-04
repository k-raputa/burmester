<?php

namespace UdgBurmesterTheme\Migration\Traits;

trait InitBase
{

    /**
     * @param string $subname
     * @return array
     */
    private function getDataFromCsv(string $subname): array
    {

        $filename = dirname(__DIR__) . DIRECTORY_SEPARATOR;
        $filename .= $this->getBaseClassname();
        $filename .= '_' . $subname . '.csv';

        if (($handle = fopen($filename, 'r')) === false) {
            die('Error opening file');
        }

        $headers = fgetcsv($handle, 256, ';');
        $array = array();

        while ($row = fgetcsv($handle, 256, ';')) {
            if (count($headers) != count($row)) {
                var_dump($row);
            }
            $array[] = array_combine($headers, $row);
        }

        fclose($handle);

        return $array;
    }

    /**
     * @return string
     */
    protected function getBaseClassname(): string {
        return substr(__CLASS__, strrpos(__CLASS__, "\\") + 1);
    }

    /**
     * @param string $no
     * @return string
     */
    protected function getBaseName(string $no): string
    {
        $baseName = str_replace('.', 'a', $no);
        $baseName = substr($baseName, 0, 22);
        $baseName = str_pad($baseName, 22, 'z');

        return $baseName . '==';
    }

}
