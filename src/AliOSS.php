<?php

namespace x2lib\oss;

use OSS\OssClient;
use x2ts\Configuration;
use x2ts\IComponent;
use x2ts\TConfig;
use x2ts\TGetterSetter;
use x2ts\Toolkit;

/** @noinspection SingletonFactoryPatternViolationInspection
 * @package x2lib\oss
 */
class AliOSS extends OssClient implements IComponent {
    use TConfig;
    use TGetterSetter;

    protected static $_conf = [
        'accessKeyId'     => '',
        'accessKeySecret' => '',
        'endpoint'        => 'oss-cn-beijing.aliyuncs.com',
        'isCName'         => false,
        'securityToken'   => null,
        'timeout'         => [
            'transfer' => 5184000,
            'connect'  => 10,
        ],
    ];

    public function __construct($conf) {
        $args = [
            $conf['accessKeyId'],
            $conf['accessKeySecret'],
            $conf['endpoint'],
            $conf['isCName'],
            $conf['securityToken'],
        ];
        parent::__construct(...$args);
        $this->setTimeout($conf['timeout']['transfer']);
        $this->setConnectTimeout($conf['timeout']['connect']);
    }

    /**
     * @param array  $args
     * @param array  $conf
     * @param string $confHash
     *
     * @return AliOSS
     */
    public static function getInstance(array $args, array $conf, string $confHash): AliOSS {
        if (!array_key_exists($confHash, Configuration::$configuration)) {
            $settings = [];
            Toolkit::override($settings, static::$_conf);
            Toolkit::override($settings, $conf);
            Configuration::$configuration[$confHash] = $settings;
        } else {
            $settings = Configuration::$configuration[$confHash];
        }
        return new AliOSS($settings);
    }
}