<?php
namespace Config;
/**
 * if you are to transfer objects you have to set protocol to msgpack(if you have that pecl ext installed), or php. Because the default JSON deserializer cannot restore a php object.<br />
 * In general, msgpack gain the performance, json is moderate but cannot restore a serialized object, php serialization has the lowest performace. Both json and php serializer are commonly supported.
 * @var array
 */
class EventClient {
    const DEBUG = TRUE;

    public $default = array(
        'protocol' => 'php',
        'user' => 'test',
        'secret_key' => 'test_password',
        'url' => 'http://rpc.event.jumeicd.com/Rpc.php'
    );

    public $psProto = array(
        // rpc server secret key.
        'rpc_secret_key' => '769af463a39f077a0340a189e9c1ec28',
        'user' => 'test_user_key',
        // message/event center subscriber secret key.
        'secret_key' => 'hvTv%IkaSd^Bl0B#',
        'hosts' => "#{mec.rpc.servers}",

        // 连接池相关配置.
        'service' => '', // 在etcd注册的服务名称(MCP连接池配置)
        'dove_key' => 'mec.rpc.servers', // 存放主机配置的dove key(MCP连接池配置), 对应hosts配置的dove key
    );
}
//