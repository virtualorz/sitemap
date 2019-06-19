<?php

namespace Virtualorz\Sitemap;

use Route;

class Sitemap
{
    //
    /**
     * 取得TreeView結構
     * @return Array $stiemap : structed sitemap
     */
    public function getTreeView(){
        $route = Route::getRoutes()->get();
        $sitemap = [];
        foreach($route as $k=>$v){
            if(!isset($v->action['permission']) || $v->action['permission'] == true){
                if(isset($v->action['parent'])){
                    if(!isset($sitemap[$v->action['parent']])){
                        $sitemap[$v->action['parent']] = [];
                    }

                    if(isset($v->action['name'])) {
                        $item = [
                            'id' => $v->action['as'],
                            'text' => $v->action['name'],
                            'state' =>[
                                'checked' => false,
                                'expanded' => true
                            ]
                        ];

                        array_push($sitemap[$v->action['parent']], $item);
                    }
                }
            }
        }

        return $sitemap;
    }

    /**
     * 取的navi path
     * @param $pageName : 頁面名稱
     * @return Array $navi 頁面navi path array
     */
    public function getNaviPath($pageName = null){
        //如果$pageName是null則取出當頁面路徑

        if($pageName == null) {
            $thisPage = Route::getCurrentRoute();
        }
        else{
            $thisPage = Route::getRoutes()->getByName($pageName);
        }
        $path = [];
        while(true){
            $item = [];
            if(isset($thisPage->action['name'])) {
                $item['text'] = $thisPage->action['name'];
                $item['name'] = $thisPage->action['as'];
                $item['url'] = '';
                array_push($path, $item);
            }


            if(isset($thisPage->action['parent'])) {
                $parent_name = $thisPage->action['parent'];
                $parent = Route::getRoutes()->getByName($parent_name);
            }
            else{
                break;
            }

            if(!is_null($parent) && $parent->action['parent'] == 'root'){
                $item = [];
                $item['text'] = $parent->action['name'];
                $item['name'] = $parent->action['as'];
                $item['url'] = Route($parent->action['as']);
                array_push($path,$item);

                break;
            }
            else{
                $thisPage = $parent;
            }
        }

        return array_reverse($path);
    }

    public function getParents($pageName = null){
        //如果$pageName是null則取出當頁面路徑
        if($pageName == null) {
            $thisPage = Route::getCurrentRoute();
        }
        else{
            $thisPage = Route::getRoutes()->getByName($pageName);
        }
        $parents = [];
        while(true){
            $item = [];
            if(isset($thisPage->action['name'])) {
                $item['text'] = $thisPage->action['name'];
                $item['name'] = $thisPage->action['as'];
                $item['url'] = '';
                array_push($path, $item);
            }


            if(isset($thisPage->action['parent'])) {
                $parent_name = $thisPage->action['parent'];
                $parent = Route::getRoutes()->getByName($parent_name);
            }
            else{
                break;
            }

            if($parent->action['parent'] == 'root'){
                array_push($parents,$parent->action['as']);

                break;
            }
            else{
                $thisPage = $parent;
            }
        }

        return $parents;
    }

    /**
     * 取得系統Menu結構
     * @param Array $parmissionArray : 權限結構
     * @return Array $menu 系統選單
     */
    public function getMenu($parmissionArray = null){
        //如果$parmissionArray是null則取出全部Menu

        $route = Route::getRoutes()->get();
        $sitemap = [];
        foreach($route as $k=>$v){
            if(!isset($v->action['permission']) || $v->action['permission'] == true){
                if(isset($v->action['parent'])){
                    if(!isset($sitemap[$v->action['parent']])){
                        $sitemap[$v->action['parent']] = [];
                    }

                    if(isset($v->action['name'])) {
                        if(is_null($parmissionArray) || (!is_null($parmissionArray) && in_array($v->action['as'],$parmissionArray))){
                            if(isset($v->action['label'])){
                                $menu =[
                                    'id' => $v->action['as'],
                                    'name' => $v->action['name']
                                ];
                                $item = [
                                    'id' => $v->action['as'],
                                    'text' => $v->action['name'],
                                    'label' => $v->action['label'],
                                    'fa' => $v->action['fa'],
                                    'menu' => $menu,
                                    'state' =>[
                                        'checked' => false,
                                        'expanded' => true
                                    ]
                                ];
                            }
                            else{
                                $menu = [];
                                if($v->action['parent'] == 'root'){
                                    $menu =[
                                        'id' => $v->action['as'],
                                        'name' => $v->action['name']
                                    ];
                                }
                                $item = [
                                    'id' => $v->action['as'],
                                    'text' => $v->action['name'],
                                    'menu' => $menu,
                                    'state' =>[
                                        'checked' => false,
                                        'expanded' => true
                                    ]
                                ];
                            }

                            array_push($sitemap[$v->action['parent']], $item);
                        }
                    }
                }
            }
        }
        $sitemap = self::routStruct('root',$sitemap);
        $menu = [];
        foreach($sitemap as $k=>$v){
            $menu[$k]['menu'] = $v['menu'];
            $menu[$k]['left'] = [];
            $menu[$k]['map'] = [];
            foreach($v['nodes'] as $k1=>$v1){
                if(isset($v1['label'])){
                    if(!isset($menu[$k]['left'][$v1['label']])){
                        $menu[$k]['left'][$v1['label']] = [
                            'fa' => $v1['fa'],
                            'menu' =>[]
                        ];
                    }
                    array_push($menu[$k]['left'][$v1['label']]['menu'],$v1['menu']);
                    array_push($menu[$k]['map'],$v1['id']);
                    //sud node
                    $sub_node = self::subNodeFroMap($v1['nodes']);
                    foreach($sub_node as $k2=>$v2){
                        array_push($menu[$k]['map'],$v2);
                    }
                }
            }
            array_push($menu[$k]['map'],$v['id']);
        }

        return $menu;
    }

    /**
     * 整理route 結構
     * @param string $parent : parent node name
     * @param Array $sitemap : sitemap for recursion
     * @return Array $stiemap : structed sitemap
     */
    public function routStruct($parent,$sitemap){

        if(isset($sitemap[$parent]) && count($sitemap[$parent]) != 0){
            foreach($sitemap[$parent] as $k=>$v){
                $sitemap[$parent][$k]['nodes'] = self::routStruct($v['id'],$sitemap);
            }

            return $sitemap[$parent];
        }
        else{
            return [];
        }
    }

    /**
     * 整理sub node list
     * @param Array nodes : nodes to recursion
     * @return Array maps : array with sub node list
     */
    public function subNodeFroMap($nodes){

        $map = [];
        foreach($nodes as $k=>$v){
            if(count($v['nodes']) != 0){
                $sub_maps = subNodeFroMap($v['nodes']);
                $map = array_merge($map,$sub_maps);
            }
            else{
                array_push($map,$v['id']);
            }
        }
        return $map;
    }
}
