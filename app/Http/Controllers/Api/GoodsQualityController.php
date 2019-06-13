<?php


namespace App\Http\Controllers\Api;


use App\Models\Tb\Attr;
use App\Models\Tb\Classify;
use App\Models\Tb\Level;
use App\Models\Tb\Norms;

class GoodsQualityController extends BaseController
{
    public function getGoodsQuality()
    {
        //  获取所有分类
        $classify = Classify::with('attr')
            ->with('norms')
            ->with('level')
            ->get();
        // 变换父子结构
        $re = $this->getFatherSonStruc($classify);

        return $this->success($re);
    }

    public function getFatherSonStruc($data, $name = 'child', $pid = 0)
    {
        $arr = array();
        foreach ($data as $v) {
            if ($v->pid == $pid) {
                $v->$name = $this->getFatherSonStruc($data, $name, $v->classify_id);
                $this->addAttrOption($v->attr); // 加入属性选项
                $this->addNormsOption($v->norms); // 加入属性选项
                $this->addLevelOption($v->level); // 加入属性选项
                $arr[] = $v;
            }
        }
        return $arr;
    }

    // 添加属性选项
    public function addAttrOption($attrArr)
    {
        foreach ($attrArr as &$attr) {
            $attr->attr_option = Attr::whereAttrId($attr['attr_id'])->with('attr_option')->first()->attr_option;
        }
    }

    // 添加规格选项
    public function addNormsOption($normsArr)
    {
        foreach ($normsArr as &$norms) {
            $norms->norms_option = Norms::whereNormsId($norms['norms_id'])->with('norms_option')->first()->norms_option;
        }
    }

    // 添加量级选项
    public function addLevelOption($levelArr)
    {
        foreach ($levelArr as &$level) {
            $level->level_option = Level::whereLevelId($level['level_id'])->with('level_option')->first()->level_option;
        }
    }
}