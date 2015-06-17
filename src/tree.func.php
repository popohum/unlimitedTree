<?php
/**
 * Tree 树型类(无限分类)
 *
 *   $tree= new Tree($result);
 *   $arr=$tree->leaf(0);
 *   $nav=$tree->navi(15);
 *   $men=$tree->leafid(0);
 */
class Tree
{
    private $result;
    private $tmp;
    private $arr;
    private $already = array();

    /**
     * 构造函数
     *
     * @param array $result 树型数据表结果集
     * @param array $fields 树型数据表字段，array(分类id,父id)
     * @param integer $root 顶级分类的父id
     */
    public function __construct($result, $fields = array('id', 'pid'), $root = 0)
    {
        $this->result = $result;
        $this->fields = $fields;
        $this->root = $root;
        $this->handler();
    }

    /**
     * 树型数据表结果集处理
     */
    private function handler()
    {
        foreach ($this->result as $node) {
            $tmp[$node[$this->fields[1]]][] = $node;
        }
        krsort($tmp);
        for ($i = count($tmp); $i > 0; $i--) {
            foreach ($tmp as $k => $v) {
                if (!in_array($k, $this->already)) {
                    if (!$this->tmp) {
                        $this->tmp = array($k, $v);
                        $this->already[] = $k;
                        continue;
                    } else {
                        foreach ($v as $key => $value) {
                            if ($value[$this->fields[0]] == $this->tmp[0]) {
                                $tmp[$k][$key]['child'] = $this->tmp[1];
                                $this->tmp = array($k, $tmp[$k]);
                            }
                        }
                    }
                }
            }
            $this->tmp = null;
        }
        $this->tmp = $tmp;
    }

    /**
     * 反向递归
     * @param array $arr
     * @param string $id
     */
    private function recur_n($arr, $id)
    {
        foreach ($arr as $v) {
            if ($v[$this->fields[0]] == $id) {
                $this->arr[] = $v;
                if ($v[$this->fields[1]] != $this->root)
                    $this->recur_n($arr, $v[$this->fields[1]]);
            }
        }
    }

    /**
     * 正向递归
     * @param array $arr
     */
    private function recur_p($arr)
    {
        foreach ($arr as $v) {
            $this->arr[] = $v[$this->fields[0]];
            if (!empty($v['child']))
                $this->recur_p($v['child']);
        }
    }

    /**
     * 菜单 多维数组
     *
     * @param integer $id 分类id
     * @return array 返回分支，默认返回整个树
     */
    public function leaf($id = 0)
    {
        if(!isset($this->tmp[$id])){
            return null;
        }
        return $this->tmp[$id];
    }

    /**
     * 导航 一维数组
     *
     * @param integer $id 分类id
     * @return array 返回单线分类直到顶级分类
     */
    public function navi($id = 1)
    {
        $this->arr = null;
        $this->recur_n($this->result, $id);
        krsort($this->arr);
        return $this->arr;
    }

    /**
     * 散落 一维数组
     *
     * @param integer $id 分类id
     * @return array 返回leaf下所有分类id
     */
    public function leafid($id)
    {
        $this->arr = null;
        if (isset($this->tmp[$id])) {
            $this->arr[] = $id;
            $this->recur_p($this->leaf($id));
        }
        return $this->arr;
    }
}