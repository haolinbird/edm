<?php
/**
 * PHPClient组件配置文件
 *
 * @author Hao Lin <haolinbird@163.com>
 * @date 2020-05-06 10:28:30
 */

namespace Config;

class PHPClient {
    public $rpc_secret_key = "#{ServiceBase.PHPClient.rpc_secret_key}";
    public $monitor_log_dir = "#{ServiceBase.PHPClient.monitor_log_dir}";

    public $Order = array(
        'uri' => "#{order-service.rpcServer}",
        'user' => 'ServiceBase',
        'secret' => '{1BA09530-F9E6-478D-9965-7EB31A59537E}',
        'service' => 'order-service', // 在etcd注册的服务名称（MCP连接池配置）
        'dove_key' => 'order-service.rpcServer', // 存放后端服务机器的dove key(MCP连接池配置),对应uri配置的dove key
    );

}
