<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2017/10/25
 * Time: 11:45
 */
return [
  'default' => env('FILESYSTEM_DRIVER','oss'),

  'disks' => [
      'AliYun' => [
          'driver'      => 'oss',
          'access_id'   => env('OSS_ACCESS_ID'),
          'access_key'  => env('OSS_ACCESS_KEY'),
          'bucket'      => env('OSS_TEST_BUCKET'),
          'endpoint'    => env('OSS_ENDPOINT'),
          'prefix'      => env('OSS_PREFIX')
      ]
  ]
];