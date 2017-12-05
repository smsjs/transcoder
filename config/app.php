<?php
return array(
  'transcoder' => array(
    'ffmpeg_path' => '', //ffmpeg路径
    'qt_faststart_path' => '', //qt_faststart路径
    'ffprobe_path' => '', //ffprobe路径
    'keep_original_video' => true, //是否保持原始视频

    //gif配置
    'gif' => array(
      'create' => true, //是否生成gif
      'size' => '320x240', //尺寸
      'second' => '5', //取N秒
      //批量生成gif图
      'config' => array(
        array(
          'start_time' => '00:00:01',
          'second' => '10',
          'size' => '320x240', //尺寸
          'text' => '测试字幕',
        ),
        array(
          'start_time' => '00:00:15',
        )
      )
    ),

    //jpeg配置
    'jpg' => array(
      'create' => true, //是否生成jpg
      'size' => '320x240', //尺寸
      'count' => 5 //平均截取N张图片
    )
  ),
);