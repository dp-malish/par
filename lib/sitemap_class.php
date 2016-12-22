<?php
class SiteMap extends Cache_File{

    const MAIN_SITE_MAP='sitemap.xml';

    private $root;
    private $site;

    private $xml='<?xml version="1.0" encoding="UTF-8"?>';

    private $standart='xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"';

    function __construct(){
        $this->root=$_SERVER['DOCUMENT_ROOT'];$this->site=$_SERVER['SERVER_NAME'];
        $this->dir=$this->root.'/';
    }

    public function IndexSiteMap(){
        $map=$this->xml.'<sitemapindex '.$this->standart.'>';
        foreach (glob($this->root."/*.xml") as $full_file){
            $search=strpos($full_file,self::MAIN_SITE_MAP);
            if(!$search){
                $map.='<sitemap><loc>'.Opt::PROTOCOL.$this->site.'/';
                $temp=explode('/',$full_file);
                $file=array_pop($temp);
                $map.=$file.'</loc><lastmod>'.
                date("Y-m-d",filemtime($full_file)).'</lastmod></sitemap>';
            }
        }
        $map.='</sitemapindex>';
        return $map;
    }

    public function CreateIndexSiteMap(){
        $this->StartCache();
        echo $this->IndexSiteMap();
        $this->StopCache(self::MAIN_SITE_MAP);
    }

    public function StartSiteMap(){return $this->xml.'<urlset '.$this->standart.'>';}
    public function EndSiteMap(){return '</urlset>';}

    public function StaticFileMap($file,$loc,$changefreq='monthly',$priority='0.5'){
        if(mb_substr($file,0,1)!='/')$file='/'.$file;
        $map='<url><loc>'.Opt::PROTOCOL.$this->site.'/'.$loc.'</loc><lastmod>'.date("Y-m-d",filemtime($this->root.$file)).'</lastmod><changefreq>'.$changefreq.'</changefreq><priority>'.$priority.'</priority></url>';
        return $map;
    }

    public function DBUrlMap($loc,$data,$changefreq='monthly',$priority='0.5'){
        $map='<url><loc>'.Opt::PROTOCOL.$this->site.'/'.$loc.'</loc><lastmod>'.$data.'</lastmod><changefreq>'.$changefreq.'</changefreq><priority>'.$priority.'</priority></url>';
        return $map;
    }
}