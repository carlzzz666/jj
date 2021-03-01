<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

if ( !function_exists( 'getStatusName' ) ) {
    function getStatusName( $status ) {
        
        $data = [
            1 => '买入待确认' ,
            2 => '买入已确认' ,
            3 => '卖出待确认' ,
            4 => '卖出已确认' ,
        ];
        
        return $data[ $status ];
    }
}


if ( !function_exists( 'getMyDateTime' ) ) {
    function getMyDateTime( $time ) {
        
        if ( isset( $time ) && $time != '-' ) {
            return Date( 'Y-m-d H:i:s' , (int)$time );
        }
        return '-';
    }
}

if ( !function_exists( 'returnInfo' ) ) {
    function returnInfo($status=0,$msg='内部错误',$data=[]){
        return [
            'status'=>$status,
            'msg'=>$msg,
            'data'=>$data,
        ];
    }
}