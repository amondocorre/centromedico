<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ImageProxy extends CI_Controller {
  public function __construct() {
    parent::__construct();
  }
  public function get2($encodedPath = null) {
      if (!$encodedPath) {
          show_404();
          return;
      }
      $decodedPath = base64_decode($encodedPath);
      $remoteUrl = getHttpHost().urlencode($decodedPath);
      //var_dump($remoteUrl,$decodedPath);
      $ch = curl_init($remoteUrl);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HEADER, false);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      $imageData = curl_exec($ch);
      $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
      curl_close($ch);
      if ($imageData) {
          header("Content-Type: " . $contentType);
          echo $imageData;
      } else {
          show_404();
      }
  }
  public function get($encodedPath = null) {
      $path = $this->input->get('path');
      if (!$path) {
          show_404();
          return;
      }
      //$remoteUrl = getHttpHost().urlencode($path);
      $remoteUrl = getHttpHost().($path);
      $ch = curl_init($remoteUrl);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HEADER, false);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      $imageData = curl_exec($ch);
      $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
      curl_close($ch);
      if ($imageData) {
          header("Content-Type: " . $contentType);
          echo $imageData;
      } else {
          show_404();
      }
  }
  public function getBase64List() {
    $input = json_decode($this->input->raw_input_stream, true);

    if (!is_array($input) || !isset($input['paths'])) {
        $response = ['status' => 'error', 'message' => 'Formato inválido'];
        return _send_json_response($this, 400, $response);
    }

    $baseURL = rtrim(getHttpHost(), '/') . '/';
    $result = [];

    foreach ($input['paths'] as $path) {
    $safePath = ltrim(str_replace(['..', '\\'], '', $path), '/');
    $fullUrl = $baseURL . $safePath;

    $ch = curl_init($fullUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $imageData = curl_exec($ch);
    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);

    if ($imageData && strpos($contentType, 'image/') === 0) {

        // ---- COMPRESIÓN ----
        if (function_exists('imagecreatefromstring')) {
            $src = imagecreatefromstring($imageData);
            ob_start();
            imagejpeg($src, null, 60); // calidad 60%
            $imageData = ob_get_clean();
            imagedestroy($src);
            $contentType = 'image/jpeg';
        }

        $result[$path] = 'data:' . $contentType . ';base64,' . base64_encode($imageData);
    } else {
        $result[$path] = null;
    }
}


    $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($result));
}



}
