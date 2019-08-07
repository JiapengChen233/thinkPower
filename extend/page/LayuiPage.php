<?php


namespace page;

use think\Paginator;

class LayuiPage extends Paginator
{

    /*
     * 首页
     */
    protected function home()
    {
        if ($this->currentPage() > 1) {
            return '<a class="layui-laypage-first" href="' . $this->url(1) . '">首页</a>';
        } else {
            return '<a class="layui-laypage-first layui-disabled" href="#">首页</a>';
        }
    }

    /*
     * 上一页
     */
    protected function prev()
    {
        if ($this->currentPage() > 1) {
            return '<a class="layui-laypage-prev" href="' . $this->url($this->currentPage - 1) . '">上一页</a>';
        } else {
            return '<a class="layui-laypage-prev layui-disabled" href="#">上一页</a>';
        }
    }

    /*
     * 下一页
     */
    protected function next()
    {
        if ($this->hasMore) {
            return '<a class="layui-laypage-next" href="' . $this->url($this->currentPage + 1) . '">下一页</a>';
        } else {
            return '<a class="layui-laypage-next layui-disabled" href="#">下一页</a>';
        }
    }

    /*
     * 尾页
     */
    protected function last()
    {
        if ($this->hasMore) {
            return '<a class="layui-laypage-last" href="' . $this->url($this->lastPage) . '">尾页</a>';
        } else {
            return '<a class="layui-laypage-last layui-disabled" href="#">尾页</a>';
        }
    }

    /*
     * 统计信息
     */
    protected function info()
    {
        return '<span class="layui-laypage-count">共 ' . $this->lastPage . ' 页，' . $this->total . ' 条数据</span>';
    }

    /*
     * 页码按钮
     */
    protected function getLinks()
    {
        $block = [
            'first' => null,
            'slider' => null,
            'last' => null
        ];
        $slide = 3;
        $window = $slide * 2;

        if ($this->lastPage < $window + 6) {
            $block['first'] = $this->getUrlRange(1, $this->lastPage);
        } elseif ($this->currentPage <= $window) {
            $block['first'] = $this->getUrlRange(1, $window + 2);
            $block['last'] = $this->getUrlRange($this->lastPage - 1, $this->lastPage);
        } elseif ($this->currentPage > ($this->lastPage - $window)) {
            $block['first'] = $this->getUrlRange(1, 2);
            $block['last'] = $this->getUrlRange($this->lastPage - ($window + 2), $this->lastPage);
        } else {
            $block['first'] = $this->getUrlRange(1, 2);
            $block['slider'] = $this->getUrlRange($this->currentPage - $slide, $this->currentPage + $slide);
            $block['last'] = $this->getUrlRange($this->lastPage - 1, $this->lastPage);
        }

        $html = '';

        if (is_array($block['first'])) {
            $html .= $this->getUrlLinks($block['first']);
        }

        if (is_array($block['slider'])) {
            $html .= $this->getDots();
            $html .= $this->getUrlLinks($block['slider']);
        }

        if (is_array($block['last'])) {
            $html .= $this->getDots();
            $html .= $this->getUrlLinks($block['last']);
        }

        return $html;
    }

    /*
     * 批量生成页码按钮
     */
    protected function getUrlLinks(array $urls)
    {
        $html = '';
        foreach ($urls as $page => $url) {
            $html .= $this->getPageLinkWrapper($url, $page);
        }
        return $html;
    }

    /*
     * 生成普通页码按钮
     */
    protected function getPageLinkWrapper($url, $page)
    {
        if ($page == $this->currentPage()) {
            return $this->getActivePageWrapper($page);
        }
        return $this->getAvailablePageWrapper($url, $page);
    }

    /*
     * 生成一个激活的按钮
     */
    protected function getActivePageWrapper($text)
    {
        return '<span class="current">' . $text . '</span> ';
    }

    /*
     * 生成一个可点击的按钮
     */
    protected function getAvailablePageWrapper($url, $text)
    {
        return '<a href="' . htmlentities($url) . '">' . $text . '</a> ';
    }

    /*
     * 生成省略号按钮
     */
    protected function getDots()
    {
        return $this->getDisabledTextWapper('...');
    }

    /*
     * 生成一个禁用的按钮
     */
    protected function getDisabledTextWapper($text)
    {
        return '<span class="layui-laypage-spr">' . $text . '</span> ';
    }

    /*
     * 渲染分页
     */
    public function render()
    {
        if ($this->hasPages()) {
            if ($this->simple) {
                return sprintf('<div>%s %s %s</div>', $this->prev(), $this->getLinks(), $this->next());
            } else {
                return sprintf('<div>%s %s %s %s %s %s %s</div>', $this->css(), $this->home(), $this->prev(), $this->getLinks(), $this->next(), $this->last(), $this->info());
            }
        }
    }

    /**
     * 分页样式
     */
    protected function css()
    {
        return '<style type="text/css">
                    .layui-laypage-count {
                        border: 0 !important;
                        font-family: Helvetica Neue,Helvetica,PingFang SC,Tahoma,Arial,sans-serif;
                    }
                </style>';
    }
}


?>