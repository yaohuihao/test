<?php
namespace app\api\controller;
use app\common\model\box\Box as BoxModel;
use app\common\model\box\BoxOrnaments;
use app\common\model\box\BoxRecord;
use app\common\model\ornaments\Ornaments;
use app\common\model\user\BalanceRecord;
use app\common\model\user\User;
use app\common\model\user\WinningRate;

class Box extends Auth{
    protected $auth = [
        '/box/open'
    ];

    /**
     * 箱子详情
     */
    public function get_box_details(){
        $id = authcode(input('id'), 1);
        $box_model = new BoxModel();
        $box = $box_model->getInfo(['id' => $id, 'status' => 1]);
        if (!$box) errorCode('信息错误!');
        $box_ornaments_model = new BoxOrnaments();
        $ornaments_model = new Ornaments();
        $box_record_model = new BoxRecord();
        $box_ornaments = $box_ornaments_model->selList(['bid' => $box['id']], '', '', function ($item) use ($ornaments_model, $box_record_model) {
            $ornaments = $ornaments_model->getInfo(['id' => $item['oid']]);
            //最后一次出的时间
            $cumulative = $box_record_model->countField(['bid' => $item['bid'], 'oid' => $item['oid'], 'type' => 1]);
            $last_time_id = $box_record_model->maxField(['bid' => $item['bid'], 'oid' => $item['oid'], 'type' => 1], 'id');
            $new_item = [
                'oid' => authcode($item['oid']),
                'probability' => floatval($item['probability']),
                'name' => $ornaments['name'],
                'appid' => $ornaments['appid'],
                'abrasion' => $ornaments['abrasion'],
                'quality' => $ornaments['quality'],
                'quality2' => $ornaments['quality2'],
                'img' => $ornaments['img'],
                'open_price' => $ornaments['open_price'],
                'color' => $ornaments['color'],
                'cumulative' => $cumulative,
                'last_time' => $cumulative != 0 ? $box_record_model->countField(['bid' => $item['bid'], 'type' => 1, 'id' => ['gt', $last_time_id]]) + 1 : 0,
            ];
            return $new_item;
        });
        $box['box_ornaments'] = $box_ornaments;
        $box['id'] = authcode($box['id']);
        $new_box_ornaments = [];
        //按价值最大排序
        $box_ornaments = re_order($box_ornaments, 'open_price', SORT_DESC);
        //相同饰品组合在一起
        foreach ($box_ornaments as $k => $v){
            $new_box_ornaments[$v['name']]['arr'][] = $v;
        }
        //计算组合在一起的总概率
        foreach ($new_box_ornaments as $k => $v){
            $new_box_ornaments[$k]['total_probability'] = floatval(sprintf("%01.4f", array_sum(array_column($v['arr'], 'probability'))));
            $new_box_ornaments[$k]['min_open_price'] = min(array_column($v['arr'], 'open_price'));
        }
        $new_box_ornaments = re_order($new_box_ornaments, 'min_open_price', SORT_DESC);
        $new_box_ornaments2 = $box_record_model->selListLimit(['bid' => $id, 'type' => 1], 50, '', 'time desc, id desc', function ($item) use ($ornaments_model) {
            $ornaments = $ornaments_model->getInfo(['id' => $item['oid']]);
            $new_item = [
                'oid' => authcode($item['oid']),
                'probability' => $item['probability'],
                'name' => $ornaments['name'],
                'appid' => $ornaments['appid'],
                'abrasion' => $ornaments['abrasion'],
                'quality' => $ornaments['quality'],
                'quality2' => $ornaments['quality2'],
                'img' => $ornaments['img'],
                'open_price' => $ornaments['open_price'],
                'color' => $ornaments['color'],
            ];
            return $new_item;
        });
        successCode(compact('box', 'new_box_ornaments', 'new_box_ornaments2'));
    }

    /**
     * 开启箱子
     */
    public function open(){
        try {
            db()->startTrans();
            $uid = $this->user['id'];
            if (!$uid) throw new \Exception('请先登录!');
            $user_model = new User();
            $user = $user_model->getInfo(['id' => $uid], 'bean, type, transaction_link, winning_add, winning_grade', '', true);
            $id = authcode(input('id'), 1);
            $num = input('num');
            $box_model = new BoxModel();
            $box = $box_model->getInfo(['id' => $id, 'status' => 1]);
            if (!$box) throw new \Exception('信息错误!');
            if (!is_numeric($num) || $num < 1 || $num > 5) throw new \Exception('次数错误!');
            if (!$user['transaction_link']) throw new \Exception('请先设置交易链接');
            $price = $num * $box['price'];
            if (($price > $user['bean'] || $user['bean'] <= 0) && $user['type'] != 4) throw new \Exception('余额不足');
            //扣除余额
            $deduction = $user_model->decField(['id' => $uid], 'bean', $price);
            if (!$deduction) throw new \Exception('余额扣除失败!');
            //查出该箱子下的关联饰品
            $box_ornaments_model = new BoxOrnaments();
            $box_ornaments = $box_ornaments_model->selOrnaments($box['id'], 'b.oid, b.probability, o.open_price', 'o.open_price desc');
            //该箱子可盈利的件数
            $profit_num = $box_ornaments_model->countOrnaments($box['id'], $box['price']);
            //取出饰品id和概率(每个用户概率不同)
            $winning_rate_model = new WinningRate();
            //循环次数

            $ornaments_model = new Ornaments();
            $ornaments_arr = [];
            for ($i = 0; $i < $num; $i++){
                $user = $user_model->getInfo(['id' => $uid], 'bean, type, transaction_link, winning_add, winning_grade');
                if ($user['type'] == 2 || $user['type'] == 3){
                    $profit_rate = +($site['box_increase'] / $profit_num);
                } elseif ($box['compatible'] == 0){
                    $profit_rate = -($box['reduce'] / $profit_num);
                } elseif ($user['winning_add']){
                    $profit_rate_info = $winning_rate_model->getField(['level' => $user['winning_grade']], 'profit_rate');
                    $profit_rate_info = is_json($profit_rate_info) ? json_decode($profit_rate_info, true) : [];
                    $profit_rate = 0;
                    foreach ($profit_rate_info as $k => $v){
                        if ($box['id'] == $v['id']){
                            $profit_rate = $v['profit_rate'] ? $v['profit_rate'] : 0;
                            break;
                        }
                    }
                    $profit_rate = $profit_rate / $profit_num;
                } else {
                    $profit_rate = 0;
                }
                //重新分配每个饰品概率
                foreach ($box_ornaments as $k => $v){
                    if ($profit_rate != 0 && $v['open_price'] > $box['price']){
                        $box_ornaments[$k]['new_probability'] = ($v['probability'] + $profit_rate) * 10000;
                    } else {
                        $box_ornaments[$k]['new_probability'] = $v['probability'] * 10000;
                    }
                }
                //根据概率获取奖项id
                $rid = '';
                //概率数组的总概率精度
                $proSum = @array_sum(array_column($box_ornaments, 'new_probability'));
                //概率数组循环
                foreach ($box_ornaments as $key => $val){
                    $randNum = mt_rand(1, $proSum);
                    if ($randNum <= $val['new_probability'] && ($box['compatible'] == 1 || shipment($uid, $box['price'], $val['open_price']))){
                        $rid = $key;
                        break;
                    } else {
                        $proSum -= $val['new_probability'];
                    }
                }
                //中奖饰品详情
                if ($rid === ''){
                    $box_ornaments_price = array_column($box_ornaments, 'open_price');
                    $rid = array_search(min($box_ornaments_price), $box_ornaments_price);
                    $item_ornaments = $box_ornaments[$rid];
                } else {
                    $item_ornaments = $box_ornaments[$rid];
                }
                $ornaments = $ornaments_model->getInfo(['id' => $item_ornaments['oid']], 'id as oid, appid, name, img, open_price, abrasion, color, quality, quality2');
                //增加抽奖记录
                $record_id = box_record($uid, $box['id'], $item_ornaments['oid'], $box['price'], $ornaments['open_price'], 1);
                $ornaments['oid'] = authcode($ornaments['oid']);
                $ornaments['record_id'] = authcode($record_id);
                array_push($ornaments_arr, $ornaments);
                //添加消费记录
                $order = rand_vif(6, 1) . time() . $uid;
                $balance_record_model = new BalanceRecord();
                //剩余余额
                $surplus = $user_model->getField(['id' => $uid], 'bean');
                $balance_record_data = [
                    'uid' => $uid,
                    'order' => $order,
                    'info' => '开启箱子' . $box['name'],
                    'money' => $box['price'],
                    'surplus' => $surplus,
                    'type' => 2,
                    'model' => 3,
                    'add_time' => time(),
                    'complete_time' => time(),
                    'status' => 1,
                ];
                $balance_record_model->edit($balance_record_data);
            }
            db()->commit();
            $balance = $user_model->getField(['id' => $uid], 'bean');
            successCode(compact('ornaments_arr', 'balance'));
        } catch (\Exception $e){
            //如获取到异常信息，对所有表的删、改、写操作，都会回滚至操作前的状态：
            db()->rollback();
            errorCode($e->getMessage());
        }
    }
}
