<?php
namespace PhalApi\Transcoder;

class Lite {
  protected $config;

  /**
* @param string $config['ffmpeg_path']
* @param string $config['qt_faststart_path']
* @param string $config['ffprobe_path']
* @param string $config['keep_original_video']  
* @param string $config['gif']  
* @param string $config['jpg']  
*/
  public function __construct($config = NULL) {
    $this->config = $config;
    if ($this->config === NULL) {
      $this->config = \PhalApi\DI()->config->get('app.transcoder');
    }
  }

  //视频缩略图
  public function get_video_image($input, $output, $fromdurasec = '00:00:01')
  {
    $command = $this->config['ffmpeg_path']." -i ".$input." -an -ss ".$fromdurasec." -r 1 -vframes 1 -f mjpeg -y ".$output;
    if(exec($command)){
      return true;
    };
    return false;
  }

  //视频缩略图
  public function get_video_image_gif($input, $output, $fromdurasec = '00:00:01', $second = 5)
  {
    $command = $this->config['ffmpeg_path']." -i ".$input." -an -ss ".$fromdurasec." -r 1 -vframes ".$second." -y ".$output;
    if(exec($command)){
      return true;
    };
    return false;
  }

  //截取视频前n秒
  public function get_video_part($input, $output, $begin_second = '00:00:01', $end_second = '00:00:05')
  {
    $command = $this->config['ffmpeg_path']." -ss ".$begin_second." -i ".$input." -t ".$end_second." ".$output;
    if(exec($command)){
      return true;
    };
    return false;
  }

  //获取视频的时长
  public function get_video_timeline($input)
  {
    $command = $this->config['ffmpeg_path']." -i ".$input." 2>&1 | grep 'Duration' | cut -d ' ' -f 4 | sed s/,//";
    $timeline = exec($command);
    $time_arr = explode(':', $timeline);
    $timeline = $time_arr[0] * 3600 + $time_arr[1] * 60 + intval($time_arr[2]);
    return $timeline;
  }

  //获取视频的详细信息
  public function get_video_info($input)
  {
    $command = $this->config['ffprobe_path']. " -v quiet -print_format json -show_format -show_streams " .$input;
    exec($command,$out,$status);
    $new = implode('',$out);
    return json_decode($new, true);
  }

  //处理视频格式--转为h264格式
  public function mobile_video_codec($input, $output)
  {
    $command = $this->config['ffmpeg_path']." -y -i ".$input." -vcodec -threads 2 libx264 -metadata:s:v:0 rotate=0 ".$output;
    exec($command);
  }

  //将mp4转为完整的ts
  public function video_to_ts($input, $output)
  {
    $command = $this->config['ffmpeg_path']." -i ".$input." -c copy -bsf h264_mp4toannexb " .$input;
    exec($command);
  }

  //将ts切片，并生成m3u8文件
  public function video_to_m3u8_and_ts($input, $outputM3u8, $outputTs)
  {
    $command = $this->config['ffmpeg_path']." -i ".$input." -c copy -map 0 -f segment -segment_list ".$outputM3u8." -segment_time 10 " .$outputTs;
    exec($command);
  }
}