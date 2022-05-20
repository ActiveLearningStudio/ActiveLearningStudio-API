<?php
/**
 * @Author      Asim Sarwar
 * Description  Handle Video Direct Url
 * ClassName    VideoDirectUrlController
 */
namespace App\Http\Controllers\Api\V1\Integration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exceptions\GeneralException;

class VideoDirectUrlController extends Controller
{
    /**
     * Get vimeo,komodo direct or playable url
     * @param Request $request
     * @return string
     * @throws GeneralException
     */
    public function getDirectUrl(Request $request) 
    {
      $getParam = $request->only([
        'videoId',
        'type'
      ]);
      if ( isset ($getParam['videoId']) && isset ($getParam['type']) )
      {
        if ($getParam['type'] == 'vimeo') {
          return $this->getVimeoDirectUrl('https://vimeo.com/'.$getParam['videoId']);
        } elseif($getParam['type'] == 'komodo') {
          return $this->getKomodoDirectUrl('https://komododecks.com/recordings/'.$getParam['videoId']);
        }
      }
      throw new GeneralException('Required videoId and type field!');
    }

    /**
     * Get Vimeo direct or playable url
     * @param string $url
     * @return string $directUrl
     * @throws GeneralException
     */
    public function getVimeoDirectUrl($url)
    {
      $directUrl = '';
      $videoInfo = $this->getVimeoVideoInfo($url);
      if ( $videoInfo && count ($videoInfo->request->files->progressive) >= 1 ) {        
        if ( $key = $this->getVimeoQualityVideo($videoInfo->request->files->progressive, '1080p') ) {
          $directUrl = $videoInfo->request->files->progressive[$key]->url;
        } elseif ( $key = $this->getVimeoQualityVideo($videoInfo->request->files->progressive, '720p') ) {
          $directUrl = $videoInfo->request->files->progressive[$key]->url;
        } elseif ( $key = $this->getVimeoQualityVideo($videoInfo->request->files->progressive, '540p') ) {
          $directUrl = $videoInfo->request->files->progressive[$key]->url;
        } elseif ( $key = $this->getVimeoQualityVideo($videoInfo->request->files->progressive, '360p') ) {
          $directUrl = $videoInfo->request->files->progressive[$key]->url;
        }
        return $directUrl;
      }
      throw new GeneralException('No video found!');
    }    

    /**
     * Get Vimeo Video Info w.r.t quality
     * @param string $url
     * @return string $videoInfo
     */
    private function getVimeoVideoInfo($url)
    {
      $videoInfo = null;
      $page = $this->getRemoteContent($url);
      $html = $this->getConfigObjectFromHtml($page, 'clip_page_config = ', 'window.can_preload');
      $json = substr($html, 0, strpos($html, '}};') + 2);
      $videoConfig = json_decode($json);
      if (isset($videoConfig->player->config_url)) {
          $videoObj = json_decode($this->getRemoteContent($videoConfig->player->config_url));
          if (!property_exists($videoObj, 'message')) {
              $videoInfo = $videoObj;
          }
      }
      return $videoInfo;
    }

    /**
     * Get video config object
     * @param mix $string, string $start, $end
     * @return mix
     */
    private function getConfigObjectFromHtml($string, $start, $end)
    {
      $string = ' ' . $string;
      $ini = strpos($string, $start);
      if ($ini == 0) {
        return '';
      }
      $ini += strlen($start);
      $len = strpos($string, $end, $ini) - $ini;
      return substr($string, $ini, $len);
    }

    /**
     * Get Vimeo video object w.r.t quality
     * @param array $videoArray, string $quality
     * @return object
     */
    private function getVimeoQualityVideo($videoArray, $quality)
    {
      return array_search($quality, array_column($videoArray, 'quality'));
    }

    /**
     * Get Komodo direct or playable url
     * @param string $url
     * @return string $directUrl
     * @throws GeneralException
     */
    public function getKomodoDirectUrl($url)
    {
      $page = $this->getRemoteContent($url);
      $html = $this->getConfigObjectFromHtml($page, 'props', ',"scriptLoader"');
      if ($html) {
        $json = '{"props'.$html.'}';      
        $videoConfig = json_decode($json);
        return $videoConfig->props->pageProps->initialPresentationInfo->movieUrl;  
      }
      throw new GeneralException('No video found!');
    }

    /**
     * Get remote content by URL
     * @param string $url remote page URL
     * @return string result content
     */
    private function getRemoteContent($url)
    {
      return file_get_contents($url);
    }
}
