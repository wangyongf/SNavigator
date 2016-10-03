<?php
/*
 * Copyright (C) 1996-2016 YONGF Inc.All Rights Reserved.
 * Scott Wang blog.54yongf.com | blog.csdn.net/yongf2014
 * 文件名：Index.php
 * 描述：
 *
 * 修改历史
 * 版本号    作者                     日期                    简要描述
 *  1.0         Scott Wang         16-10-1             新增：Create
 *  1.1         Scott Wang          16-10-3             新增：解析链接json，生成导航首页
 */

namespace app\index\controller;

use think\Config;
use think\Controller;

/**
 * 导航首页
 *
 * @author      Scott Wang
 * @version     1.1, 16-10-1
 * @since         LaoMaWeb 1.0
 */
class Index extends Controller
{
    /**
     * 渲染导航首页
     *
     * @return mixed
     */
    public function index()
    {
        $links_html = $this->generate_links_html();
        $this->assign('links_html', $links_html);
        $this->assign(Config::get('view_replace_str'));
        return $this->fetch();
    }

    /**
     * 导航首页链接html生成
     *
     * @return string
     */
    private function generate_links_html()
    {
        $links = $this->parse_json();
        $html = "";
        for ($i = 0; $i < count($links); $i++) {
            if ($i == 0) {
                $html .= "<div class=\"wrap mt20 nav\">";
            } else {
                $html .= "<div class=\"wrap mt1 nav\">";
            }
            $category = $links[$i]['category'];
            $html .= "<div class=\"btcont\">$category</div>";
            $html .= "<ul class=\"nav nav_menu clearfix\">";
            $link_list = $links[$i]['links'];
            for ($j = 0; $j < count($link_list); $j++) {
                $url = $link_list[$j]['url'];
                $title = $link_list[$j]['title'];
                $html .= "<li><a href=\"$url\" target=\"_blank\">$title</a></li>";
            }
            $html .= "</ul>";
            $html .= "</div>";
            if ($i != count($links) - 1) {
                $html .= "<div class=\"fh\"></div>";
            }
        }

        return $html;
    }

    /**
     * 解析json为链接的数组
     *
     * @return mixed
     */
    private function parse_json()
    {
        $json_links = $this->fetch_links();
        $links = json_decode($json_links, true);
        return $links;
    }

    /**
     * 获取链接的json字符串
     *
     * @return string
     */
    private function fetch_links()
    {
        $file_name = "static/index/json/links.json";       //文件相对路径
        $json_links = file_get_contents($file_name);            //获取json内容
        return $json_links;
    }
}
