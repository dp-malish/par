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

    function gzip_or_xml($file,$filename='index.tmp'){
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

        $f_arr=gzfile($file);
        $this->StartCache();
        foreach($f_arr as $val){$pos=strpos($val,'<loc>');
            if($pos!==false){
                $val=trim(str_replace('<loc>','',$val));
                $val=str_replace('</loc>','',$val);
                echo $val.'<br>';
            }
        }
        $this->StopCacheWithOut($filename);
    }
    //*********************
    public function getUrl($file,$count=4){
        $file=Validator::html_cod($file);
        $file=file_get_contents($this->dir.$file);
        $url=explode('<br>',$file);
        return $url[$count];
    }
}