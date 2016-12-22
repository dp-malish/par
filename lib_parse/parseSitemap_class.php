<?php
//namespace parse;
/**
 * Created by PhpStorm.
 * Date: 21.12.2016
 */
class ParseSitemap extends Cache_File{

    function __construct(){
        $this->dir='../cache_all/parse/';
    }

    function gzip_xml($file,$filename='index.xml'){
        $file=Validator::html_cod($file);
        $gz=strpos($file,'.gz');
        if($gz===false)$this->getXmlMap($file,$filename);
        else $this->getGzipMap($file,$filename);
    }

    private function getXmlMap($file,$filename){
        $file=file_get_contents($file);
        $xml=simplexml_load_string($file);
        $this->StartCache();
        foreach($xml->url as $item){
            $url=$item->loc;
            echo $url.'<br>';
        }
        $this->StopCacheWithOut($filename);
    }

    private function getGzipMap($file,$filename){
        //В php.ini включи allow_url_fopen
        $origFileName=$this->dir.$filename.'.gz';
        $fp=@fopen($file,'rb');
        $fd=@fopen($origFileName,"w");
        if($fp && $fd){
            while(!feof($fp)){
                $st=fread($fp,4096);
                fwrite($fd,$st);
            }
        }
        @fclose($fp);
        @fclose($fd);

        $fp = gzopen($origFileName, 'r');
        $file=file_get_contents($fp);

        echo $origFileName;


        $zip = new ZipArchive;
        if ($zip->open($origFileName) === true){
            //получаем файл info.txt и выводим его на экран:
            echo $zip->getFromName('info.txt');
            $zip->close();
        }else{
            echo 'Не могу найти файл архива!';
        }

        //$fp = gzopen($origFileName,'rb');
        //$fp=file_get_contents($fp);

        //$fp=readgzfile($fp);

        //gzclose($fp);
        //echo $fp->loc;

        //print($fp);


        //$file=readgzfile($file);
        //$file=gzfile($file);

        //$uncompressed = gzinflate($file);
        echo 'k';//$uncompressed;


        //echo $file;

        //$file=file_get_contents($file);
        //$xml=simplexml_load_string($file);
        //$this->StartCache();
        /*foreach($xml->url as $item){
            $url=$item->loc;
            echo $url.'<br>';
        }*/
        //$this->StopCacheWithOut($filename);
    }

}