<?php
namespace app\index\controller;

use think\Controller;

class Jijin extends Controller
{
//    每页条数
    protected $page = 5;
//    状态
    protected $status = null;
    
    /**
     * 显示基金列表
     * @return mixed
     * @throws \think\exception\DbException
     * @author: CaiZeBiao
     * @Time  : 2021/3/1 20:22
     */
    public function index(){
        $this->status = input('status');
        if ($this->status!==false && in_array($this->status,[1,2,3,4])){
//            根据基金状态查询所有数据
            $result = db('fund')->where(['status'=>$this->status])->paginate($this->page)->each(function($v, $k){
                $data = [];
                switch ($this->status){
                    case 4:
//                          获取价格
                            $sell_price = db('fund')->where('id',$v['id'])->value('sell_price');
                            if (!$sell_price){
                                return returnInfo(0,'获取卖出价格失败');
                            }
//                          利润
                            $profit = bcsub($sell_price,$v['buy_price'],2);
                            $data['sell_share'] = $v['sell_share'];
                            $data['sell_worth'] = $v['sell_worth'];
                            $data['sell_final_time'] = $v['sell_final_time'];
                            $data['profit'] = $profit;
                    case 3:
                            $data['sell_price'] = $v['sell_price'];
                            $data['sell_time'] = $v['sell_time'];
                    case 2:
                            $data['buy_share'] = $v['buy_share'];
                            $data['buy_worth'] = $v['buy_worth'];
                            $data['buy_final_time'] = $v['buy_final_time'];
                    case 1:
                            $data['id'] = $v['id'];
                            $data['name'] = $v['name'];
                            $data['code'] = $v['code'];
                            $data['buy_price'] = $v['buy_price'];
                            $data['buy_time'] = $v['buy_time'];
                    break;
                }
                return $data;
            });
            $this->assign('data',$result);
//            根据状态赋值不同模板
            return $this->fetch('index'.$this->status);
        }else{
//            查询所有基金
            $result = db('fund')->paginate($this->page)->each(function($item, $key){
                $data = [];
//                数据格式化
                foreach ($item as $k => $v){
                        if (isset($v)){
                            $data[$k] = $v;
                        }else{
                            $data[$k] = '-';
                        }
                }
                return $data;
            });
    
        }
        $this->assign('data',$result);
        return $this->fetch();
    }
    
    
    /**
     * 添加基金
     * @return array|mixed
     * @author: CaiZeBiao
     * @Time  : 2021/3/1 20:25
     */
    public function add(){
        if ( $this->request->isAjax() ) {
            $data['code'] = input('post.code');
            $data['name'] = input('post.name');
            $data['buy_price'] = input('post.buy_price');
            $data['buy_time'] = strtotime(input('post.buy_time'));
            
//            写入数据
            $insert = db('fund')->insert($data);
            
            if (!$insert){
                return returnInfo(0,'添加失败');
            }
            return returnInfo(1,'添加成功');
        }
        $timeNow = date('Y-m-d H:i:s',time());
        $this->assign('timeNow',$timeNow);
        return $this->fetch();
    }
    
    /**
     * 编辑基金
     * @return array|mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     * @author: CaiZeBiao
     * @Time  : 2021/3/1 20:26
     */
    public function edit(){
        if ( $this->request->isAjax() ) {
            $data['id'] = input('post.id');
            $data['code'] = input('post.code');
            $data['name'] = input('post.name');
            $data['buy_price'] = input('post.buy_price');
            $data['buy_time'] = strtotime(input('post.buy_time'));
            $data['buy_share'] = input('post.buy_share');
            $data['buy_worth'] = input('post.buy_worth');
            $data['buy_final_time'] = strtotime(input('post.buy_final_time'));
            
//            更新数据
            $update = db('fund')->update($data);
            if (!$update){
                return returnInfo(0,'编辑失败');
            }
            
//            更改状态
            $status = db('fund')->where('id',$data['id'])->setField('status', 2);
            if (!$status){
                return returnInfo(0,'更改状态失败');
            }
            return returnInfo(1,'编辑成功');
        }
        
        $id = input('id');
        $result = db('fund')->where(['id'=>$id])->find();
        $timeNow = date('Y-m-d H:i:s',time());
        $this->assign('timeNow',$timeNow);
        $this->assign('data',$result);
        
//        根据不同状态赋值显示不同模板
        if ($result['status']==1){
            return $this->fetch();
        }elseif ($result['status']==2){
            return $this->fetch('sell');
        }elseif ($result['status']==3){
            return $this->fetch('sellok');
        }
    }
    
    
    /**
     * 编辑基金卖出
     * @return array|mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     * @author: CaiZeBiao
     * @Time  : 2021/3/1 20:28
     */
    public function sell(){
        if ( $this->request->isAjax() ) {
            $data['id'] = input('post.id');
            $data['code'] = input('post.code');
            $data['name'] = input('post.name');
            $data['buy_price'] = input('post.buy_price');
            $data['buy_time'] = strtotime(input('post.buy_time'));
            $data['sell_price'] = input('post.sell_price');
            $data['sell_time'] = strtotime(input('post.sell_time'));
            $update = db('fund')->update($data);
            if (!$update){
                return returnInfo(0,'编辑失败');
            }
        
            //            更改状态
            $status = db('fund')->where('id',$data['id'])->setField('status', 3);
            if (!$status){
                return returnInfo(0,'更改状态失败');
            }
            return returnInfo(1,'编辑成功');
        }
    
        $id = input('id');
        $result = db('fund')->where(['id'=>$id])->find();
        $this->assign('data',$result);
        return $this->fetch();
    }
    
    /**
     * 编辑基金确认卖出，获取利润数据
     * @return array
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * @author: CaiZeBiao
     * @Time  : 2021/3/1 20:28
     */
    public function sellok(){
        if ( $this->request->isAjax() ) {
            $data['id'] = input('post.id');
            $data['code'] = input('post.code');
            $data['name'] = input('post.name');
            $data['buy_price'] = input('post.buy_price');
            $data['buy_time'] = strtotime(input('post.buy_time'));
            $data['sell_share'] = input('post.sell_share');
            $data['sell_worth'] = input('post.sell_worth');
            $data['sell_final_time'] = strtotime(input('post.sell_final_time'));
            
//            更新数据
            $update = db('fund')->update($data);
            if (!$update){
                return returnInfo(0,'编辑失败');
            }

//            获取价格
            $sell_price = db('fund')->where('id',$data['id'])->value('sell_price');
            if (!$sell_price){
                return returnInfo(0,'获取卖出价格失败');
            }
//            利润
            $profit = bcsub($sell_price,$data['buy_price'],2);
            //            更改状态
            $status = db('fund')->where('id',$data['id'])->setField(['status'=>4,'profit'=>$profit]);
            if (!$status){
                return returnInfo(0,'更改状态失败');
            }
            return returnInfo(1,'编辑成功');
        }
        

    }
    
}
