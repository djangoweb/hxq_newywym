<?php class ExcelToArray{
    public function __construct() {
    }
    public function read($filename,$encode='utf-8'){
        include_once(IA_ROOT.'/framework/library/phpexcel/PHPExcel.php');
        include_once(IA_ROOT.'/framework/library/phpexcel/PHPExcel/IOFactory.php');
        $extension=strtolower(pathinfo($filename,PATHINFO_EXTENSION));
        if($extension=='xlsx'){
            $objReader=PHPExcel_IOFactory::createReader('Excel2007');
        }elseif($extension=='xls'){
            $objReader=PHPExcel_IOFactory::createReader('Excel5');
        }elseif($extension=='csv'){
            $objReader=PHPExcel_IOFactory::createReader('CSV');//默认输入字符集
            $objReader->setInputEncoding('GBK');//默认的分隔符
            $objReader->setDelimiter(',');//载入文件
        }
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($filename);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow=$objWorksheet->getHighestRow();
        $highestColumn=$objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        $excelData=array();
        for($row=1;$row<=$highestRow;$row++){
            for($col=0;$col<$highestColumnIndex;$col++){
                $excelData[$row][]=(string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
            }
        }
        return $excelData;
    }
}