<?php
return [
    'adminEmail'        => 'admin@example.com',
    'videoserver'       => 'http://112.126.88.56:3000',
    'vcfservice'        => 'http://112.126.88.56:2000',
    'erp_url'           => 'http://shanghaijinzhun.com/',
    'wechat_sick'       => require 'wechat.sick.php',
    'wechat_doctor'     => require 'wechat.doctor.php',

    //管理员的手机
    'master_vcf_mobile' => ['13611262703', '15001087980', '13910136035'],
    'master_vcf_voice'  => 'Untitled1.wav',

    //测试时，把所有的短信都发到这里
    'allsms2mobile'     => false, //'13910136035', //false

    'mobile_aliases'    => [ //测试帐号 代理 xx号码
        '13910136035' => '13911689892', //'doctor 13911689892', user 15347067230
        '13581901791' => '18612701977',

        '13910510371' => '15103236666',
        '13611262703' => '13701223188', //安

        '15001087980' => '18612800215', //王志农

        '15201677766' => '15811206831', //黄爱宇 15201677766 赵建波
        '18504600670' => '13911689892', //刘玥   杨艳玲
        '18810892696' => '18600521897', //张岩  18810892696 袁云
        '18172837071' => '13548966986', //占玮珉 18172837071 彭镜
        '15624962562' => '18811182130', //沈鑫曌 15624962562 笪宇威
        '18511971751' => '13699149612', //李骅璋 18511971751 矫黎冬
        '13366645747' => '13910838816', //操振华 13366645747 张在强
        '13426052642' => '13911100127', //孙继国 13426052642 卢岩
        '17766549990' => '15869056872', //xx =>徐明霞
        '18210855120' => '15811206831',   //=>赵建波
    ],
];
