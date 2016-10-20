<?php
return array(
    //'配置项'=>'配置值'
    'DEFAULT_MODULE' => 'Home',  // 默认模块
    'DEFAULT_ACTION' => 'index',
    'MODULE_ALLOW_LIST' => array('Home', 'Admin'),
    //加载db配置
    'LOAD_EXT_CONFIG' => '',
    /* 日志设置 */
    'LOG_RECORD' => false, // 默认不记录日志
    'LOG_TYPE' => 'File', // 日志记录类型 0 系统 1 邮件 3 文件 4 SAPI 默认为文件方式
    'LOG_DEST' => './Logs', // 日志记录目标
    'LOG_EXTRA' => '', // 日志记录额外信息
    'LOG_LEVEL' => 'EMERG,ALERT,CRIT,ERR',// 允许记录的日志级别
    'LOG_FILE_SIZE' => 2097152,    // 日志文件大小限制
    'LOG_EXCEPTION_RECORD' => false, // 是否记录异常信息日志

    'DB_TYPE' => 'mysql',
    'DB_HOST' => 'rdsnrzyjvrjummm.mysql.rds.aliyuncs.com',
    'DB_NAME' => 'gm_weekly',
    'DB_USER' => 'gamma',
    'DB_PWD' => 'Gamma515',
    'DB_PORT' => 3306,

    /*'DB_TYPE' => 'mysql',
    'DB_HOST' => '114.215.155.69',
    'DB_NAME' => 'gm_weekly',
    'DB_USER' => 'root',
    'DB_PWD' => 'Gamma0903',
    'DB_PORT' => 3306,*/
    'DB_PREFIX' => '',
    'URL_MODEL' => 2,
    'URL_ROUTER_ON' => true,
    'URL_MAP_RULES' => array(),
    //发送邮件配置
    'MAIL_HOST' =>'smtp.163.com',//smtp服务器的名称
    'MAIL_SMTPAUTH' =>TRUE, //启用smtp认证
    'MAIL_USERNAME' =>'15737317781@163.com',//发件人的邮箱名
    'MAIL_PASSWORD' =>'ulmskuwgwsjeraey',//163邮箱发件人授权密码
    'MAIL_FROMNAME'=>'Marchsoft',//发件人姓名
    'MAIL_CHARSET' =>'utf-8',//设置邮件编码
    'MAIL_ISHTML' =>TRUE, // 是否HTML格式邮件
);